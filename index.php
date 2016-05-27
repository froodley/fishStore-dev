<?php

// Establish basic application setup, the instantiate and start inc\App.php

$path = dirname(__FILE__);
require_once( $path . '/inc/internal_error.php');

// Fail if mod_rewrite isn't turned on
//if( !function_exists('apache_get_modules') ||
//    !in_array( 'mod_rewrite', apache_get_modules() ) )
//{
//	exit( sprintf( $internal_error, 'The Fish Store', 'Error: mod_rewrite Required', 'no_email@fishstore.default'));
//}

date_default_timezone_set('America/Chicago'); #TODO: Need to set this from INI

set_include_path( get_include_path() . ';.;' . $path . '/inc;');

require_once( $path . '/inc/Globals.php');

$base_path = $path;
$inc_path = $base_path . '/inc/';
$GLOBALS['base_path'] = $base_path;
$GLOBALS['inc_path'] = $inc_path;


register_shutdown_function( "HandleFatals" );

spl_autoload_register( function ($fqcn)
	{
		global $base_path;
		
		$fqcn = preg_replace( '/fishStore\\\\/', '', $fqcn );
		$fqcn = preg_replace( '/\\\\/', '/', $fqcn);
		
		// Try to locate the class file
		foreach( [ 'inc', '' ] as $folder )
		{
			$sub = strlen( $folder ) ? "/$folder/" : '/';
			
			$fn =  $base_path . $sub . $fqcn . '.php';
			
			if( file_exists( $fn ) )
			{
				require_once( $fn );
				return true;
			}
		}
		
		return false;
	}
);


$app = new fishStore\App();
$app->Start();