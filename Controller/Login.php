<?php

namespace fishStore\Controller;

/**
 * Login
 * 
 * The controller for the initial Login view
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Login extends \fishStore\Base\Controller
{
	
	public function GET( $id, $query)
	{
		global $_ENVELOPE;
		
		$lost_password = isset( $query['LostPassword'] );
		
		if( $lost_password )
			return \fishStore\Factory\ViewFactory::Make( 'LostPassword' );
		else
		{
			if( isset( $query[ 'error' ] ) )
				$_ENVELOPE['login_error'] = $query['error'];
			
			return \fishStore\Factory\ViewFactory::Make();
		}
		
	}
	
	public function POST( $id )
	{
		global $_ENVELOPE, $ini, $dbh;
		
		$usr_email = $_POST['login_usr'] ?: null;
		$usr_pass = $_POST['login_pass'] ?: null;
		$success = true;
		$error = "An internal error occurred while logging in.  Please contact an <a id='error_lnk' href='mailto:{$ini['STORE']['E-MAIL']}' >administrator</a>.";
		$credential_fail = $error = 'Please check your e-mail and password and try again.<br/>( Need to <a id="error_lnk" href="/Login?LostPassword=1" >recover your password</a>? )';
		
		
		// Check for params
		if( is_null( $usr_email ) || is_null( $usr_pass) )
		{
			$success = false;
			$error = "You must fill in your e-mail and password to log in.  Please try again!";
		}
		
		// Validate params
		if( $success )
		{
			$success &= \fishStore\Util\Is::Email( $usr_email );
			$success &= \fishStore\Util\Is::ValidPassword( $usr_pass );
		}
		if( !$success )
			$error = $credential_fail;
		
		
		// Look for the user
		if( $success )
		{
			$res = $dbh->Select(	'SELECT usr_id FROM tbl_user WHERE usr_email = ? AND usr_password = ?',
									[ $usr_email, md5( $usr_pass ) ] );
			
			if( count( $res ) != 1 )
			{
				$success = false;
				$error = $credential_fail;
			}
			else
			{
				$usr = \fishStore\Base\Entity::GetByID( 'User', $res[0]['usr_id'] );
				if( !$usr )
				{
					$success = false;
					LogMessage( "Error: Controller\Login.php -  Could not build user object for usr_id $id" );
					// Use the default $error here
				}
				else
				{
					$_ENVELOPE['login_success'] = true;
					$_SESSION['usr'] = $usr;
					\fishStore\Util\Session::Store();
					
					$contr = new Home();
					return $contr->GET( null, null );
				}
			}
		}
		
		$query = [];
		if( !$success )
			$query['error'] = $error;
		
		return $this->GET( null, $query );
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
} // Login
