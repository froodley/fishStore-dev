<?php

namespace fishStore\Util;

/**
 * File
 *
 * Static methods to get file handles
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class File {
	
	private static $_error_open = "%s.php could not open file '%s'";
	
	
	/**
	* OpenDir
	*
	* Opens a directory for reading
	*
	* @param (string) The path
	* @param (string) The class name requesting the handle
	* @return (object) The dirhandle or null
	*/
	public static function OpenDir( $path, $class )
	{
		$dh = opendir( $path );
		if( !$dh )
		{
			LogMessage( sprintf( "%s.php could not open directory '%s'", $class, $path ) );
			return null;
		}
		
		return $dh;
		
	} // OpenDir
	
	
	/**
	* OpenRead
	*
	* Opens a file for reading
	*
	* @param (string) The filename
	* @param (string) The class name requesting the handle
	* @return (object) The filehandle or null
	*/
	public static function OpenRead( $fn, $class )
	{
		$fh = fopen( $fn, 'r');
		if( !$fh )
		{
			LogMessage( sprintf( self::$_error_open, $class, $fn ) );
			return null;
		}
		
		return $fh;
		
	} // OpenRead
	
	
	/**
	* OpenWrite
	*
	* Opens a file for writing
	*
	* @param (string) The filename
	* @param (string) The class name requesting the handle
	* @return (object) The filehandle or null
	*/
	public static function OpenWrite( $fn, $class )
	{
		$fh = fopen( $fn, 'w');
		if( !$fh )
		{
			LogMessage( sprintf( self::$_error_open, $class, $fn ) );
			return null;
		}
		
		return $fh;
		
	} // OpenWrite
	
	
	/**
	* OpenAppend
	*
	* Opens a file for append
	*
	* @param (string) The filename
	* @param (string) The class name requesting the handle
	* @return (object) The filehandle or null
	*/
	public static function OpenAppend( $fn, $class )
	{
		$fh = fopen( $fn, 'a');
		if( !$fh )
		{
			LogMessage( sprintf( self::$_error_open, $class, $fn ) );
			return null;
		}
		
		return $fh;
		
	} // OpenAppend
	
} // File
