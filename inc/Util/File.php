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
	
	private static $_err_open = "%s.php could not open file '%s'";
	
	
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
