<?php

// CONSTANTS //

define( 'JS_PATH', '/inc/js/' );
define( 'CSS_PATH', '/inc/css/' );
define( 'REQ_PATH', '/inc/req/' );
define( 'IMG_PATH', '/inc/img/' );
define( 'PROFILE_PATH', '/profile/' );

// GLOBAL VARIABLES //
/**
 * The file system path for the project
 * @global (string) $base_path
 */
$base_path		= null;
/**
 * The file system path for the /inc folder
 * @global (string) $inc_path
 */
$inc_path		= null;
/**
 * The master class running the application
 * @global (App) $app
 */
$app			= null;
/**
 * System settings
 * @global (array) $ini
 */
$ini			= [];
/**
 * The HTML generator
 * @global (HTML) $html
 */
$html			= null;
/**
 * The cryptographic object
 * @global (fishStore\Util\Crypto) $crypto
 */
$crypto			= null;
/**
 * Database access
 * @global (fishStore\Util\DBH) $dbh
 */
$dbh			= null;
/**
 * The names of all the entity classes that were defined
 * @global (array) $entities
 */
$entities		= [];
/**
 * The envelope used to pass miscellaneous data from the controller to the View
 * @global (array) $_ENVELOPE
 */
$_ENVELOPE		= [];
/**
 * The array defining the site menus
 * @global (array) $menu_items
 */
$menu_items = [
				'Shop' =>
				[
					'Fish' => '/Fish',
					'Bowls, Tanks & Aquariums' => '/Aquarium',
					'Castles, Divers & Trees' => '/Decor',
					'Pumps & Filters' => '/Pump',
					'Fish Food' => '/Feed',
					'Gravel, Water Conditioner, & Misc.' => '/Misc'
				],
				'Learn' =>
				[
					'Saltwater vs. Fresh' => '/Learn?What=WaterType' ,
					'Common Diseases' => '/Learn?What=Diseases',
					'Do Fish Drink Water?' => '/Learn?What=DoFishDrink'
				],
				'Account' =>
				[
					'Login/Register' => '/Login',
					'Admin' => '/admin',
					'My Profile' => '/Profile',
					'My Cart' => '/Cart',
					'Logout' => '/Logout'
				]
			];
/**
 * The array defining the admin menu
 * @global (array) $admin_menu_items
 */
$admin_menu_items = [
				'Fish'			=> '/admin?view=fish',
				'Other Items'	=> '/admin?view=items',
				'Users'			=> '/admin?view=users',
				'Sales'			=> '/admin?view=sales',
				'Sessions'		=> '/admin?view=sessions',
			  ];
			
/**
 * Default values for the INI
 * @global (array) $default_ini
 */
$default_ini =	[
					'STORE' =>
					[
						'NAME' => 'The Fish Store',
						'MOTTO' => 'Fancy a fish?',
						'E-MAIL' => 'no-email@fishstore.default',
						'TIMEZONE' => 'America/Chicago',
						'URL' => '/',
						'LOGO' => '/inc/img/store_logo.gif'
					],
					'DB' =>
					[
						'HOST' => 'localhost',
						'USER' => 'db_admin_uname',
						'PASSWORD' => 'db_admin_password',
						'DB_NAME' => 'fishStore',
						'ENCRYPT_ENTITIES' => true,
					],
					'SETTINGS' =>
					[
						'MINIFY' => true,
						'SESSION_TIMEOUT' => 60
					]
				];



// GLOBAL FUNCTIONS //

/**
* LogMessage
*
* Write a message to the daily system logs
*
* @param (string) The message to log
* @return (null)
*/
function LogMessage( $msg )
{
	global $base_path;
	
	$fh = fopen( $base_path . '\\etc\\logs\\log_' . date('dmY') . '.log', 'a' );
	if( !$fh )
		return; // Nowhere to log to
	
	fwrite( $fh, date( 'Y-m-d H:i:s' ) . "\t" .  $msg . "\n" );
	
	fclose( $fh );
	
} // LogMessage

/**
* LogBug
*
* Wrap LogMessage to make finding left-overs easier
*
* @param (string) The message to log
* @return (null)
*/
function LogBug( $msg )
{
	LogMessage( $msg );
	
} // LogBug


function GetBoolFromString( $string )
{
	return ( $string == 'true' ) ? true : false;
}

/**
* GetFML
*
* Get a user's name as First M. Last
*
* @param (fishStore\Entity\User) The user object
* @return (string) The FML
*/
function GetFML( $usr )
{
	return	$usr->usr_first_name . ' ' .
			( $usr->usr_middle_init ? $usr->usr_middle_init . '. ' : '' ) .
			$usr->usr_last_name;
	
} // GetFML


/**
* HandleFatals
*
* Catch and log fatals
*
* @return (null)
*/
function HandleFatals()
{
	global $ini, $internal_error;
	
	$error = error_get_last();
	if( !$error )
		return;
	
	$type_str = $error['type'];
	switch( $error['type'] )
	{
		case E_ERROR:
			$type_str = E_ERROR;
			break;
		case E_USER_ERROR:
			$type_str = E_USER_ERROR;
			break;
		case E_RECOVERABLE_ERROR:
			$type_str = E_RECOVERABLE_ERROR;
			break;
		default:
			return true;
			break;
	}
	
	LogMessage( "PHP Error: {$type_str} - File:'{$error['file']}'ln{$error['line']} - {$error['message']}" );
	
	exit( sprintf(	$internal_error, ( $ini['STORE'] ? $ini['STORE']['NAME'] : 'The Fish Store' ) ,
					"General Error #{$error['type']}", ( $ini['STORE'] ? $ini['STORE']['E-MAIL'] : 'no_email@fishstore.default' ) ) );
	
} // HandleFatals


/**
* ArrayToStr
*
* Convert an array into its (evaluable) string form
*
* @param (array) The array
* @param (string) Internal use only
* @param (int) Internal use only
* @param (boolean) Internal use only
* @return (string) The resulting string
*/
function ArrayToStr( $arr, $str = '', $depth = 0, $is_last = true )
{
	$str .= '[ ';
	
	$cnt = count( $arr );
	
	$i = 0;
	
	if( !is_array( $arr ) )
	{
		LogMessage( "Error: Non-array passed to ArrayToStr" );
		return;
	}
	
	foreach( $arr as $k => $v )
	{
		$str .=  "'$k' => ";
		if( is_array( $v ) )
			$str = ArrayToStr( $v, $str, $depth + 1, ( $i == $cnt - 1) );
		else
		{
			$v = ( is_numeric( $v ) || \fishStore\Util\Is::TF( $v ) ? $v : "'$v'" );
			$str .= ( $i < $cnt - 1) ? "$v, " : "$v ";
		}
		
		$i++;
	}
	
	$str .= ( $is_last ? ']' : '], ' );
	
	return $str;

} // ArrayToStr



// REFLECTION //

/**
* LogReflection
*
* Spit information about a class to the log
*
* @param (string) The class name
* @return (null)
*/
function LogReflection( $class_name )
{
	#TODO: Deeper reflection insights
	
	LogMessage( "\nLogging Reflection for " . $class_name );
	$ref = new \ReflectionClass( $class_name );
	
	
	$properties = $ref->getProperties();
	$methods = $ref->getMethods();
	
	foreach( $properties as $k => $v )
		LogMessage( 'Member ' . $k . ':' . ArrayToStr( $v ) );
	
	foreach( $methods as $k => $v )
		LogMessage( 'Method ' . $k . ':' . ArrayToStr( $v ) );
	
	LogMessage("End Reflection\n");
	
} // LogReflection


/**
* GetClassMembers
*
* Returns a MDA of the [methods] and [properties] of a class
*
* @param (string) The class name
* @return (array) The MDA
*/
function GetClassMembers( $class_name )
{
	$class_members = [];
	$ref = new \ReflectionClass( $class_name );
	
	$class_members['properties']	= $ref->getProperties();
	$class_members['methods']		= $ref->getMethods();
	
	return $class_members;
	
} // GetClassMembers



