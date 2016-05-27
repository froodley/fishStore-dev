<?php

namespace fishStore\Controller;

/**
 * admin
 * 
 * The controller for the admin
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class admin extends \fishStore\Base\Controller
{
	
	public function GET( $id, $query)
	{
		global $_ENVELOPE;
		$model = null;
		
		if( !isset( $_SESSION['usr'] ) || $_SESSION['usr']->usr_is_admin != true )
		{
			LogMessage( 'Critical Error: Un-authorized attempt to access /admin was blocked.' );
			$contr = new Home();
			$query = [];
			$query['reload'] = 1;
			return $contr->GET( null, $query );
		}
		
		if( isset( $query['view'] ) )
		{
			// Anything general to all views regardless of action
			$_ENVELOPE['admin_view'] = $query['view'];
			
			// GET model for single or collection
			if( isset( $query['load'] ) )
			{
				if( $query['load'] == 'single' && !is_null( $id ) )
				{
					// Load single item model
				}
				elseif( $query['load'] == 'coll' )
				{
					$data = [];
					//if( isset( $query['pg'] ) )
					//	$data['pg'] = $query['pg'];
					
					$fqcn = '\\fishStore\\Model\\admin\\' . $_ENVELOPE['admin_view'] . 'Collection';
					
					$model = new $fqcn( $data );
				}
				else
					LogMessage( 'Error: Controller\admin.php - Received an invalid load request.');
			}
		}
		
		return \fishStore\Factory\ViewFactory::Make( $model );
		
	}
	
	public function POST( $id )
	{
		global $_ENVELOPE, $dbh, $ini;
		
		#TODO: SWITCH
		return self::_modifyUser();
		
	}
	
	public function PUT( $id )
	{
		parse_str(file_get_contents("php://input"),$post_vars);
		GET( $id, $query );
	}
	
	public function DELETE( $id, $query)
	{
		GET( $id, $query );
	}
	
	private function _modifyUser()
	{
		global $_ENVELOPE, $dbh, $ini;
		
		$success = true;
		$error = "An internal error occurred while modifying the user.  Please contact an <a class='error_lnk' href='mailto:{$ini['STORE']['E-MAIL']}' >administrator</a>.";
		
		// Grab handles
		$id		= isset( $_POST['usr_id'] ) ? $_POST['usr_id'] : null;
		$fn		= isset( $_POST['usr_first_name'] ) ? $_POST['usr_first_name'] : null;
		$mi		= isset( $_POST['usr_mi'] ) ? $_POST['usr_mi'] : null;
		$ln		= isset( $_POST['usr_last_name'] ) ? $_POST['usr_last_name'] : null;
		$email	= isset( $_POST['usr_email'] ) ? $_POST['usr_email'] : null;
		$phone	= isset( $_POST['usr_phone'] ) ? $_POST['usr_phone'] : null;
		$bday	= isset( $_POST['usr_birthday'] ) ? $_POST['usr_birthday'] : null;
		$pass	= isset( $_POST['usr_password'] ) ? $_POST['usr_password'] : null;
		$is_admin		= isset( $_POST['usr_is_admin'] ) ? GetBoolFromString( $_POST['usr_is_admin'] ) : null;
		$is_suspended	= isset( $_POST['usr_is_suspended'] ) ? GetBoolFromString( $_POST['usr_is_suspended'] ) : null;
		
		
		// Check required fields
		foreach( [ $id, $fn, $ln, $email ] as $k )
		{
			if( $success && is_null( $k ) )
			{
				$success = false;
				$error = 'One or more required fields was not completed; please try again.';
			}
		}
		
		// Validate required fields
		if( $success )
		{
			$email = strtolower( $email );
			
			$success &= \fishStore\Util\Is::FirstLastName( $fn );
			$success &= \fishStore\Util\Is::FirstLastName( $ln );
			$success &= \fishStore\Util\Is::Email( $email );
			
			if( !$success )
				$error = 'One or more required fields was filled out incorrectly; please try again.';
		}
		
		 // Clear invalid values in non-required fields
		if( $success )
		{
			if( !is_null( $pass ) && !\fishStore\Util\Is::ValidPassword( $pass ) )
				$pass = null;
			if( !is_null( $mi ) && !\fishStore\Util\Is::MiddleInitial( $mi ) )
				$mi = null;
			if( !is_null( $phone ) && !\fishStore\Util\Is::USPhone( $phone ) )
				$phone = null;
			if( !is_null( $bday ) && !( $bday_dttm = \fishStore\Util\Is::DateString( $bday ) ) )
				$bday = null;
			if( !is_null( $is_admin ) && !\fishStore\Util\Is::TF( $is_admin ) )
				$is_admin = false;
			if( !is_null( $is_suspended ) && !\fishStore\Util\Is::TF( $is_suspended ) )
				$is_suspended = false;
		}
		// Modify User
		$created = false;
		if( $success )
		{
			$ent = new \fishStore\Entity\User();
			$ent->usr_id = $id;
			$ent->usr_first_name = $fn;
			$ent->usr_middle_init = $mi;
			$ent->usr_last_name = $ln;
			$ent->usr_email = $email;
			$ent->usr_phone = $phone;
			
			if( !is_null( $bday_dttm ) )
				$ent->usr_birthday = $bday_dttm->format('Y-m-d H:i:s' );
				
			$ent->usr_password = md5( $pass );
			
			$ent->usr_is_admin = $is_admin;
			$ent->usr_is_suspended = $is_suspended;
			
			$ent->usr_modified = date( 'Y-m-d H:i:s' );
			
			$ent->dirty_cols = [	'usr_first_name', 'usr_middle_init', 'usr_last_name', 'usr_email', 'usr_phone',
									'usr_birthday', 'usr_password', 'usr_password', 'usr_is_admin',  'usr_is_suspended',
									'usr_modified' ];
			
			
			$res = $ent->Update();
			
			if( !$res )
			{
				$success = false;
				// Use the default error
			}
		} // Modify User
		
		
		if( !$success )
		{
			$query = [];
			$query['error'] = $error;
			
			return $this->GET( null, $query );
		}
		else
		{
			$data = [];
			$fqcn = '\\fishStore\\Model\\admin\\usersCollection';
			$model = new $fqcn( $data );
			
			$_ENVELOPE['admin_view'] = 'users';
			
			return \fishStore\Factory\ViewFactory::Make( $model );
		}
		
	}
} // admin
