<?php

namespace fishStore\Controller;

/**
 * Register
 * 
 * Allow new user registrations
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Register extends \fishStore\Base\Controller
{
	
	public function GET( $id, $query)
	{
		global $_ENVELOPE;
		
		if( isset( $query[ 'error' ] ) )
			$_ENVELOPE['reg_error'] = $query['error'];
		
		return \fishStore\Factory\ViewFactory::Make();
		
	}
	
	public function POST( $id )
	{
		global $_ENVELOPE, $dbh, $base_path, $ini;
		
		#MINOR: This method is leggy, break into pieces?
		// The other code is going to be just as ugly, 7 params or 50 arrayrefs...
		
		$success = true;
		$error = "An internal error occurred while adding your account.  Please contact an <a class='error_lnk' href='mailto:{$ini['STORE']['E-MAIL']}' >administrator</a>.";
		
		// Grab handles
		$fn		= isset( $_POST['reg_fn'] ) ? $_POST['reg_fn'] : null;
		$mi		= isset( $_POST['reg_mi'] ) ? $_POST['reg_mi'] : null;
		$ln		= isset( $_POST['reg_ln'] ) ? $_POST['reg_ln'] : null;
		$email	= isset( $_POST['reg_email'] ) ? $_POST['reg_email'] : null;
		$phone	= isset( $_POST['reg_phone'] ) ? $_POST['reg_phone'] : null;
		$bday	= isset( $_POST['reg_bday'] ) ? $_POST['reg_bday'] : null;
		$pass	= isset( $_POST['reg_pass'] ) ? $_POST['reg_pass'] : null;
		$pass_conf	= isset( $_POST['reg_pass_conf'] ) ? $_POST['reg_pass_conf'] : null;
		
		// Check required fields
		foreach( [ $fn, $ln, $email, $pass, $pass_conf ] as $k )
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
			$success &= \fishStore\Util\Is::ValidPassword( $pass );
			
			$success &= ( $pass == $pass_conf );
			
			if( !$success )
				$error = 'One or more required fields was filled out incorrectly; please try again.';
		}
		
		
		$bday_dttm = null;
		 // Clear invalid values in non-required fields
		if( $success )
		{
			if( !is_null( $mi ) && !\fishStore\Util\Is::MiddleInitial( $mi ) )
				$mi = null;
			if( !is_null( $phone ) && !\fishStore\Util\Is::USPhone( $phone ) )
				$phone = null;
			if( !is_null( $bday ) && !( $bday_dttm = \fishStore\Util\Is::DateString( $bday ) ) )
				$bday = null;
		}
		
		
		// Check for profile image
		$file = null;
		if( $success && isset( $_FILES['reg_profile_upload'] ) && strlen( $_FILES['reg_profile_upload']['name'] ) )
		{
			$file = $_FILES['reg_profile_upload'];
			if ( $file['error'] !== UPLOAD_ERR_OK )
			{
				LogMessage("Error: Controller\Register.php - Profile image upload failed. Error #{$file['error']}" );
				$file = null;
				$error = 'Your profile image failed to upload.  Please try to upload it again from your profile page.';
			}
			elseif( !\fishStore\Util\Is::Image( $file ) )
			{
				LogMessage("Error: Controller\Register.php - Uploaded file was not an image." );
				$file = null;
				$error = 'Your profile image failed to upload.  Please try to upload it again from your profile page.';
			}
		}
		
		
		// Create User
		$created = false;
		if( $success )
		{
			// Check for existing user with e-mail
			$res = $dbh->Select( 'SELECT usr_id FROM tbl_user WHERE usr_email = ?', [ $email ] );
			if( count( $res ) )
			{
				$success = false;
				$error = 'A user with that e-mail already exists.  ( Need to <a class="error_lnk" href="/Login?LostPassword=1" >recover your password</a>? )';
				#TODO test, write lost password
			}
			else
			{
				// Create User
				$usr = new \fishStore\Entity\User();
				$usr->usr_first_name = $fn;
				$usr->usr_middle_init = $mi;
				$usr->usr_last_name = $ln;
				$usr->usr_email = $email;
				$usr->usr_phone = $phone;
				
				if( !is_null( $bday_dttm ) )
					$usr->usr_birthday = $bday_dttm->format('Y-m-d H:i:s' );
					
				$usr->usr_password = md5( $pass );
				
				if( $file )
					$usr->usr_profile_img = $file['name'];
					
				$usr->usr_is_admin = false; // Defaults to false, just being explicit
				$usr->usr_created = date( 'Y-m-d H:i:s' );
				
				
				$usr_id = $usr->Commit();
				
				if( $usr_id )
				{
					$usr->usr_id = $usr_id;
					
					// Set up the user's profile folder
					$foldername = str_replace( '@', '_', $email );
					$user_path = $base_path . "\\profile\\$foldername\\";
						
						
					// Truncate the folder if it exists
					if( is_dir( $user_path ) )
					{
					  if ( $dh = \fishStore\Util\File::OpenDir( $user_path, 'Controller\Register' ) )
					  {
						while ( ( $fn = readdir( $dh ) ) !== false )
						{
							if( $fn == '.' || $fn == '..' )
								continue;
							
							unlink( $user_path . $fn );
						}
						
						closedir( $dh );
					  }
					}
					else
					{ // Create the folder
						mkdir( $user_path, 0755 );
					}
					
					// Move the user's profile pic to their folder
					if( $file )
						move_uploaded_file( $file['tmp_name'], $user_path . $file['name'] );
					
					// Set up the session and success and return to Home
					$_ENVELOPE['reg_success'] = true;
					$_ENVELOPE['login_success'] = true;
					$_SESSION['usr'] = $usr;
					\fishStore\Util\Session::Store();
					
					$contr = new Home();
					return $contr->GET( null, null );
				}
				
			} // else not exists, create
			
		} // Create User
		
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
} // Fish
