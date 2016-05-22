<?php

namespace fishStore\Util;

/**
 * CSV
 *
 * Read and write CSV files
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class CSV
{
	/**
	* Load
	*
	* Load a CSV into the provided array
	*
	* @param (array) The array to fill
	* @param (string) The file path to load
	* @param (string) The name of the calling class
	* @return (null)
	*/
	public static function Load( &$arr, $fn, $class_name )
	{
		$temp_arr = [];
		$fh = File::OpenRead( $fn , $class_name );
		$flen = filesize( $fn );
		if( !$flen )
		{
			LogMessage( "CSV::Load: File '$fn' was present but empty for $class_name." );
			return null;
		}
		
		$csv_data = fread( $fh, $flen );
		fclose( $fh );
		
		$temp_arr = array_map('trim', explode( ',', $csv_data ) );
		if( !count( $temp_arr ) )
		{
			LogMessage( "CSV::Load: File '$fn' was present but did not contain CSV attribute names for $class_name." );
			return null;
		}
		
		
		$arr = $temp_arr;
	} // Load
	
	
	/**
	* Write
	*
	* Write the provided SDA to a CSV
	*
	* @param (array) The array to write
	* @param (string) The file path to write
	* @param (string) The name of the calling class
	* @return (boolean) Result
	*/
	public static function Write( &$arr, $fn, $class_name )
	{
		$str = '';
		$cnt = count( $arr );
		for( $i = 0; $i < $cnt;  $i++)
		{
			$item = $arr[$i];
			if( is_array( $item ) )
			{
				LogMessage(	"\nError: CSV::Write could not write the array because it is not one-dimensional." .
							"\nFilename: $fn\nArray: " . ArrayToStr($arr) . "\n\n" );
				return false;
			}
			$str .= trim( $item );
			$str .= ( $i < $cnt - 1 ) ? ',' : '';
		}
		
		$fh = File::OpenWrite( $fn . 'v', $class_name );
		if( !$fh )
			return false;
		
		fwrite( $fh, $str );
		fclose( $fh );
		
		return true;
	
	} // Write
	
} // CSV