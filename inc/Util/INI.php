<?php

namespace fishStore\Util;

/**
 * INI
 *
 * Utility class to read and write INI files
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class INI
{
	/**
	* Load
	*
	* Loads the INI file into the provided array
	*
	* @param (array) The array to load into
	* @param (string) The file to load
	* @return (null)
	*/
	public static function Load( &$ini, $fn	)
	{
		$fh = File::OpenRead( $fn, 'INI' );
		if( !$fh )
			return false;
		
		$ini = [];
		$currentSection = 'DEFAULT';
		
		while( !feof( $fh ) )
		{
			$matches = null;
			
			$ln = fgets( $fh );
			if( !$ln || !strlen($ln) )
				continue;
			
			preg_match( "/\[(\w+)\]/", $ln, $matches ); // Section Header
			if( count($matches) != 0 )
			{
				$currentSection = $matches[1];
				$ini[ $currentSection ] = [];
				continue;
			}
			elseif ( preg_match( "/\s*=\s*/", $ln ) ) // K-V pair
			{
				 list( $key, $value ) = preg_split( "/\s*=\s*/", $ln, 2 );
				 $ini[ $currentSection ][ trim($key) ] = trim($value);
			}
		}
		
		fclose( $fh );
		
		#DEBUG
		#LogMessage( ArrayToStr( $ini ) );
		
	} // Load
		
		
	/**
	* Write
	*
	* Writes the INI out to file
	*
	* @param (array) The array to write
	* @param (string) The file to write to
	* @return (null)
	*/
	public static function Write( &$ini, $fn )
	{
		$fh = File::OpenWrite( $fn, 'INI' );
		if( !$fh )
			return false;
		
		$keys = array_keys( $ini );
		$end = count( $keys );
		
		for( $i = 0; $i < $end ; $i++ )
		{
			$s = $keys[ $i ];
			$arr = $ini[ $s ];
			
			if( is_array( $arr ) )
			{
				fwrite( $fh, "[$s]\n" ); // Section Header
				
				foreach( $arr as $k => $v )
					fwrite( $fh, $k . ' = ' . $v . "\n" ); // K-V Pair
			}
			else
					continue; // Discard ill-formed sections
			
			if( $i < $end - 1 )
					fwrite($fh, "\n"); // Separate sections
		}
		
		fclose($fh);
	} // Write
	
} // INI
