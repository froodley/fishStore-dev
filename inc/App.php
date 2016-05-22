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
		if( !( count( $ini ) ) )
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
		Factory\Entity::LoadEntities( $entities );
		if( !( count( $entities ) ) )
		{
			LogMessage('Error: Could not load entities');
			exit(sprintf( $internal_error, $ini['STORE']['NAME'], "EntityLoad", $ini['STORE']['E-MAIL'] ));
		}
		
		
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
		global $base_path, $ini, $internal_error;
		
		// Take apart the route
		$request = trim($_SERVER['REQUEST_URI'], '/');
		$orig_req = $request;
		
		preg_match( '/\?/', $request, $matches );
		if( count( $matches ) > 1 )
		{
			LogMessage( "Routing Error: Received an ill-formed query string (too many ?): $orig_req" );
			exit( sprintf(	$internal_error, $ini['STORE']['NAME'],
							"RouteParse", $ini['STORE']['E-MAIL'] ) );
		}
		
		$request = explode( '/', $request );
		$cnt = count( $request );
		if( $cnt > 2 ) #REST: Use $cnt > 3 for non-REST
		{
			LogMessage( "Routing Error: Received an ill-formed query string (too many params): $orig_req" );
			exit( sprintf(	$internal_error, $ini['STORE']['NAME'],
							"RouteParse", $ini['STORE']['E-MAIL'] ) );
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
		
		// Check for file names
		if( strpos( $controller_name, '.' )	> -1 ||
		    //strpos( $action_name, '.' )		> -1 || #REST: Enable if non-REST
			$id && strpos( $id, '.' ) 		> -1 )
		{
			LogMessage( "Routing Error: Received an ill-formed query string (part contained a period): $orig_req" );
			exit( sprintf(	$internal_error, $ini['STORE']['NAME'],
							"RouteParse", $ini['STORE']['E-MAIL'] ) );
		}
		$kvp = [];
		if( strlen( $query ) )
			$kvp = explode( ';', $query);
			
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
			exit( sprintf(	$internal_error, $ini['STORE']['NAME'],
							"RouteParse", $ini['STORE']['E-MAIL'] ) );
		}
		
		$implements = class_implements( $controller_name );
		if( !isset( $implements['fishStore\Interfaces\iController'] ) )
		{
			LogMessage( "Routing Error: Controller '$controller_name' must implement \\fishStore\\Interfaces\\iController." );
			exit( sprintf(	$internal_error, $ini['STORE']['NAME'],
							"RouteParse", $ini['STORE']['E-MAIL'] ) );
		}
		
		
		// class_implements has already called spl_autoload_register for us, no reason to require
		
		$controller = new $controller_name();
		
		if( !method_exists( $controller, $action_name ) )
		{
			LogMessage( "Routing Error: Action '$action_name' does not exist for controller '$controller_name'." );
			exit( sprintf(	$internal_error, $ini['STORE']['NAME'],
							"RouteParse", $ini['STORE']['E-MAIL'] ) );
		}
		
		if( isset( $id ) && !is_int( $id ) )
		{
			LogMessage( "Routing Warning: Discarded non-integer ID value $id for $controller_name->$action_name" );
			$id = null;
		}
		
		print $controller->$action_name( $id, $query );
		
	} // Start
	
	
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