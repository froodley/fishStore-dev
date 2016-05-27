<?php

namespace fishStore;

define( 'DEFAULT_CONTROLLER', 'Home' );
// define( 'DEFAULT_ACTION', 'Index' ); #REST: Actions are not used in REST; enable if no longer REST-ful

/**
 * App
 *
 * The driver for fishStore; spins up globals, decides based on
 * the URI what controller to start, destroys globals on destruct
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class App
{
	/**
	* __construct
	*
	* Initialized the globals
	*
	* @return (App) The App object
	*/
	function __construct()
	{
		global $base_path, $ini, $html, $crypto, $dbh, $entities, $internal_error;
		
		// Get params
		Util\INI::Load( $ini, $base_path . '\\config.ini' );
		if( !( count( $ini ) ) || !self::_validateINI() )
		{
			LogMessage('Error: Could not load INI');
			exit( sprintf( $internal_error, "The Fish Store", "INILoad", 'no_email@fishstore.default' ) );
		}
		// Set the correct timezone for the user
		date_default_timezone_set($ini['STORE']['TIMEZONE']);
		
		
		// Get the HTML generator
		$html = new Util\HTML();
		if( !$html )
		{
			LogMessage('Error: Could not load HTML helper');
			exit( sprintf( $internal_error, $ini['STORE']['NAME'], "HTMLLoad", $ini['STORE']['E-MAIL'] ) );
		}
		
		// Get the Crypto object
		$crypto = new Util\Crypto();
		if( !$crypto )
		{
			LogMessage('Error: Could not load Crypto');
			exit( sprintf( $internal_error, $ini['STORE']['NAME'], "CryptoLoad", $ini['STORE']['E-MAIL'] ) );
		}
		
		// Instantiate DBI
		$dbh = new Util\DBH( $ini['DB']['HOST'], $ini['DB']['USER'], $ini['DB']['PASSWORD'], $ini['DB']['DB_NAME'] );
		if( !$dbh )
		{
			LogMessage('Error: Could not load DBH');
			exit( sprintf( $internal_error, $ini['STORE']['NAME'], "DBHLoad", $ini['STORE']['E-MAIL'] ));
		}
		
		// Spin up YAML entity classes
		Factory\EntityFactory::LoadEntities( $entities );
		if( !( count( $entities ) ) )
		{
			LogMessage('Error: Could not load entities');
			exit(sprintf( $internal_error, $ini['STORE']['NAME'], "EntityLoad", $ini['STORE']['E-MAIL'] ));
		}
		
		
		session_start();
		\fishStore\Util\Session::Restore();
		
	} // __construct
	
	
	/**
	* Start
	*
	* Performs the routing
	* Starts the appropriate controller and action
	*
	* @return (null)
	*/
	public function Start()
	{
		
		// Route and continue
		self::_route();
		
	} // Start
	
	
	
	/**
	* _route
	*
	* Performs the routing
	* Starts the appropriate controller and action
	*
	* @return (null)
	*/
	private static function _route()
	{
		#MINOR: Method is kind of long, should be split into pieces
		
		global $base_path, $ini, $internal_error;
	
		// Take apart the route
		$request = trim($_SERVER['REQUEST_URI'], '/');
		$orig_req = $request;
		
		preg_match( '/\?/', $request, $matches );
		if( count( $matches ) > 1 )
		{
			LogMessage( "Routing Error: Received an ill-formed query string (too many ?): $orig_req" );
			return self::_goHome();
		}
		
		$request = explode( '/', $request );
		$cnt = count( $request );
		if( $cnt > 2 ) #REST: Use $cnt > 3 for non-REST
		{
			LogMessage( "Routing Error: Received an ill-formed query string (too many params): $orig_req" );
			return self::_goHome();
		}
		
		
		// Discover controller, action, and params
		$controller_name = DEFAULT_CONTROLLER;
		$action_name = $_SERVER['REQUEST_METHOD'];
		$query = '';
		$id = null;
		switch( $cnt )
		{
			#REST: Enable these cases and disable the active case 2: for non-REST
			//case 3:
			//	$controller_name = $request[0];
			//	$action_name = $request[1];
			//	$query = $request[2];
			//	$query_pos = strpos( $query, '?' );
			//	if( $query_pos > 0 )
			//		list( $id, $query ) = explode( '?', $query );
			//		
			//	break;
			//
			//case 2:
			//	$controller_name = $request[0];
			//	$action_name = $request[1];
			//	$query_pos = strpos( $action_name, '?' );
			//	if( $query_pos == 0 )
			//	{
			//		$query = $action_name;
			//		$action_name = DEFAULT_ACTION;
			//	}
			//	elseif( $query_pos > 0 )
			//		list( $action_name, $query ) = explode( '?' );
			//		
			//	break;
			//
			case 2:
				$controller_name = $request[0];
				$query = $request[1];
				$query_pos = strpos( $query, '?' );
				if( $query_pos > 0 )
					list( $id, $query ) = explode( '?', $query );
					
				break;
			
			
			case 1:
				$controller_name = strlen( $request[0] ) ? $request[0] : DEFAULT_CONTROLLER ;
				$query_pos = strpos( $controller_name, '?' );
				if( $query_pos === 0 )
				{
					$query = $controller_name;
					$controller_name = DEFAULT_CONTROLLER;
				}
				elseif( $query_pos > 0 )
					list( $controller_name, $query ) = explode( '?', $controller_name );
					
				break;
			
			default:
				break;
		}
	
		// Is this a logout request?
		if( $controller_name == 'Logout' )
			self::_logout();
	
	
		
		// Check for file names
		if( strpos( $controller_name, '.' )	> -1 ||
		    //strpos( $action_name, '.' )		> -1 || #REST: Enable if non-REST
			$id && strpos( $id, '.' ) 		> -1 )
		{
			LogMessage( "Routing Error: Received an ill-formed query string (part contained a period): $orig_req" );
			return self::_goHome();
		}
		
		// Tear apart the query
		$kvp = [];
		if( strlen( $query ) )
		{
			if( strpos( $query, '?') !== 0 )
				$kvp = explode( '&', $query);
			else
			{
				$query = substr( $query, 1 );
				$kvp[] = $query;
			}
		}
			
		$query = [];
		foreach( $kvp as $pair )
		{
			list( $k, $v ) = explode( '=', $pair );
			$query[$k] = $v;
		}
	
	
		// Create and start Controller	
		$controller_name = "\\fishStore\\Controller\\" . $controller_name;
		
		if( !class_exists( $controller_name ) )
		{
			LogMessage( "Routing Error: Controller '$controller_name' does not exist." );
			return self::_goHome();
		}
		
		$implements = class_implements( $controller_name );
		if( !isset( $implements['fishStore\Interfaces\iController'] ) )
		{
			LogMessage( "Routing Error: Controller '$controller_name' must implement \\fishStore\\Interfaces\\iController." );
			return self::_goHome();
		}
		
		
		// class_implements has already called spl_autoload_register for us, no reason to require
		
		$controller = new $controller_name();
		
		if( !method_exists( $controller, $action_name ) )
		{
			LogMessage( "Routing Error: Action '$action_name' does not exist for controller '$controller_name'." );
			return self::_goHome();
		}
		
		if( isset( $id ) && !is_int( $id ) )
		{
			LogMessage( "Routing Warning: Discarded non-integer ID value $id for $controller_name->$action_name" );
			$id = null;
		}
		
		print $controller->$action_name( $id, $query );
	} // _route
	
	
	/*
	 * _goHome
	 *
	 * Re-route poorly-formed and erroneous calls to the home page
	 * 
	 */
	private static function _goHome()
	{
		$controller = new \fishStore\Controller\Home();
		print $controller->GET( null, null );
		
	}
	
	
	/**
	* _validateINI
	*
	* Ensures appropriate values are used in key INI fields; populates others with defaults
	*
	* @return (boolean) The result
	*/
	private static function _validateINI()
	{
		global $ini, $default_ini, $base_path;
		
		$ini_error = 'INI validation error: %s failed validation for value %s';
		
		// Validate STORE
		if( !isset( $ini['STORE'] ) )
			$ini['STORE'] = [];
		if( !isset( $ini['STORE']['NAME'] ) )
			$ini['STORE']['NAME'] = $default_ini['STORE']['NAME'];
		if( !isset( $ini['STORE']['MOTTO'] ) )
			$ini['STORE']['MOTTO'] = $default_ini['STORE']['MOTTO'];
		if( !isset( $ini['STORE']['E-MAIL'] ) )
			$ini['STORE']['E-MAIL'] = $default_ini['STORE']['E-MAIL'];
		if( !isset( $ini['STORE']['TIMEZONE'] ) )
			$ini['STORE']['TIMEZONE'] = $default_ini['STORE']['TIMEZONE'];
		if( !isset( $ini['STORE']['URL'] ) )
			$ini['STORE']['URL'] = $default_ini['STORE']['URL'];
		if( !isset( $ini['STORE']['LOGO'] ) )
			$ini['STORE']['LOGO'] = $default_ini['STORE']['LOGO'];
		
		if(	!(\fishStore\Util\Is::WordString( $ini['STORE']['NAME'] ) ) )
		{
			LogMessage( sprintf( $ini_error, "['STORE']['NAME']", $ini['STORE']['NAME'] ) );
			return false;
		}
		
		if( !(\fishStore\Util\Is::Email( $ini['STORE']['E-MAIL'] ) ) )
		{
			LogMessage( sprintf( $ini_error, "['STORE']['E-MAIL']", $ini['STORE']['E-MAIL'] ) );
			return false;
		}
		
		if( !(\fishStore\Util\Is::TimeZoneString( $ini['STORE']['TIMEZONE'] ) ) )
		{
			LogMessage( sprintf( $ini_error, "['STORE']['TIMEZONE']", $ini['STORE']['TIMEZONE'] ) );
			return false;
		}
		
		if( !(\fishStore\Util\Is::URL( $ini['STORE']['URL'] ) ) )
		{
			LogMessage( sprintf( $ini_error, "['STORE']['URL']", $ini['STORE']['URL'] ) );
			return false;
		}
		
		if( !( file_exists( $base_path . $ini['STORE']['LOGO'] ) ) )
		{
			LogMessage( sprintf( $ini_error, "['STORE']['LOGO']", $ini['STORE']['LOGO'] ) );
			return false;
		}
		
		
		// Validate DB
		if(	!isset( $ini['DB'] )				||
			!isset( $ini['DB']['HOST'] )		||
			!isset( $ini['DB']['USER'] )		||
			!isset( $ini['DB']['PASSWORD'] )	||
			!isset( $ini['DB']['DB_NAME'] )
		   )
		{
			return false;
		}
		if( !isset( $ini['DB']['ENCRYPT_ENTITIES'] ) )
			$ini['DB']['ENCRYPT_ENTITIES'] = $default_ini['DB']['ENCRYPT_ENTITIES'];
		
		// Validate SETTINGS
		if( !isset( $ini['SETTINGS'] ) )
			$ini['SETTINGS'] = [];
			
		if( !isset( $ini['SETTINGS']['MINIFY'] ) )
			$ini['SETTINGS']['MINIFY'] = $default_ini['SETTINGS']['MINIFY'];
		if( !isset( $ini['SETTINGS']['SESSION_TIMEOUT'] ) || !is_int( $ini['SETTINGS']['SESSION_TIMEOUT'] ) )
			$ini['SETTINGS']['SESSION_TIMEOUT'] = $default_ini['SETTINGS']['SESSION_TIMEOUT'];
		
		foreach( $ini as $section => $arr )
		{
			foreach( $arr as $k => $v )
			{
				if( $v === 'true' )
					$ini[$section][$k] = true;
				elseif( $v === 'false' )
					$ini[$section][$k] = false;
			}
		}
		
		return true;
	
	} // _validateINI
	
	
	/**
	* _logout
	*
	* Destroy the session in PHP and on the SQL server for the login
	* Re-route to the home page
	*
	* @return (null)
	*/
	private static function _logout()
	{
		global $ini;
		
		$url = '/';
		
		// Prevent anything being sent before the redirect header; destroy the session
		ob_start();
			$url = $ini['STORE']['URL'];
			if( strpos( $url, 'http' ) !== 0 )
				$url = 'http://' . $url;
			
			\fishStore\Util\Session::End();
			
		ob_end_clean();
		
		// Redirect to the home page
		header( "Location: $url" );
		exit();
		
	} // _logout
	
	/**
	* __destruct
	*
	* Destroys globals
	*
	* @return (null)
	*/
	public function __destruct()
	{
		global $ini, $dbh;
		
		if( $dbh )
			$dbh->__destruct();
			
	} // __destruct
	
} // App