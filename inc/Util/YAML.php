<?php

namespace fishStore\Util;

define( 'YAML_MARKER', 'YAML-File' );

/**
 * YAML
 *
 * Utility class to read and write YAML files
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class YAML
{
	/**
	* Load
	*
	* Load the provided YAML file as a multi-dimensional array
	*
	* @param (array) The array ref to load into
	* @param (string) The file to load from
	* @return (null)
	*/
	public static function Load( &$objects, $fn, $encrypted = false )
	{
		global $crypto;
		
		$fh = File::OpenRead( $fn, 'YAML' );
		if( !$fh )
			return;
		
		if( !filesize( $fn ) )
		{
			LogMessage( "Error: File $fn found empty in YAML::Load" );
			return;
		}
		
		$yaml = fread( $fh, filesize( $fn ) );
		fclose( $fh );
		
		// Conform encryption state
		$yaml_marker_pos = strpos( $yaml,  YAML_MARKER );
		
		if( $encrypted && $yaml_marker_pos === 0 ) // $encryted is on, YAML is decrypted
		{
			// Rewrite the file, but move on with the YAML we have
			self::_writeHelper( $fn, $yaml, true );
		}
		elseif( !$encrypted && $yaml_marker_pos !== 0 ) // $encrypted is off, YAML is encrypted
		{
			// Decrypt the YAML and rewrite the file
			$yaml = $crypto->Decrypt( $yaml );
			self::_writeHelper( $fn, $yaml, false );
		}
		elseif( $encrypted )
			$yaml = $crypto->Decrypt( $yaml );
		
		// Re-check after decryption 
		$yaml_marker_pos = strpos( $yaml,  YAML_MARKER );
		
		if( $yaml_marker_pos != 0 )
			return;
		else
			$yaml = substr( $yaml, $yaml_marker_pos + strlen( YAML_MARKER ) + 1 ); // Remove the YAML marker
		
		$yaml_objects = preg_split( "/\n\s*\n/", $yaml);
		
		foreach( $yaml_objects as $obj_str )
			self::_parseYAML( explode("\n", $obj_str), $objects );
		
	} // Read
	
	/**
	* _parseYAML
	*
	* Helper for Load recursively iterates through the file's lines and creates the MDA
	*
	* @param (array) The current set of lines
	* @param (array ref) The current tier of the MDA
	* @param (int) The current depth in the MDA
	* @return (null)
	*/
	private static function _parseYAML( $lines, &$obj, $depth = 0 )
	{
		$line_cnt = count($lines);
		
		for( $i = 0; $i < $line_cnt; $i++ )
		{
			$line = $lines[$i];
			$matches = [];
			
			preg_match( '/^\s*(\w+)\s*\:\s*(\w+)?/', $line, $matches);
			$match_cnt = count($matches);
			
			if( $match_cnt == 2 )
			{
				$ord_depth = $depth + 1; // 1-based depth for display
				
				$obj[ $matches[1] ] = []; // start a new sub-object
				$sub_lines = [];
				
				// Gather all the lines belonging to this object
				$broke = false;
				for( $j = $i + 1; $j < $line_cnt; $j++ )
				{
					$subln = $lines[$j];
					$sub_matches = [];
					
					preg_match( '/^\t{' . $ord_depth . ',}/', $subln, $sub_matches);
					
					if( count( $sub_matches ) == 1 )
						$sub_lines[] = $subln;
					else
					{
						$broke = true; // Whether to repeat this line
						break; // We've fallen up a level, object end
					}
				}
				
				if( count( $sub_lines ) )
					$i = ($broke) ? $j - 1 : $j;
					
				self::_parseYAML($sub_lines, $obj[ $matches[1] ], $ord_depth );
				
			}
			elseif( $match_cnt == 3)
			{
				$obj[ $matches[1] ] = $matches[2];
			}
		}
	} // _parseYAML
	
	
	/**
	* Write
	*
	* Writes a multi-dimensional array out to a YAML file
	*
	* @param (array) The array to write
	* @param (string) The file to write to
	* @return (null)
	*/
	public static function Write( &$objects, $fn, $encrypt = false )
	{
		$str = self::_parseObj( $objects, '' );
		
		$str = YAML_MARKER . "\n" . $str;
		
		self::_writeHelper( $fn, $str, $encrypt );
		
	} // Write
	
	private static function _writeHelper( $fn, $str, $encrypt = false )
	{
		global $crypto;
		if( $encrypt )
			$str = $crypto->Encrypt( $str );
		
		$fh = File::OpenWrite( $fn, 'YAML' );
		if( !$fh )
			return false;
		
		fwrite( $fh, $str );
		fclose($fh);
	}
	
	/**
	* _parseObj
	*
	* Helper for Write recursively iterates through the MDA and creates the output string
	*
	* @param (array) The current tier of the array
	* @param (string) The current YAML string
	* @param (int) The current depth in the MDA
	* @return (string) The YAML to write to file
	*/
	private static function _parseObj( $object, $str, $depth = 0 )
	{
		#MINOR: Don't love the naming here...
		foreach( $object as $k => $v )
		{
			$str .= str_repeat( "\t", $depth ) . $k . " :";
			if( is_array( $v ) )
			{
				$str .= "\n";
				$str = self::_parseObj( $v, $str, $depth + 1 );
			}
			else
				$str .= " {$v}\n";
				
			if( $depth == 0)
				$str .= "\n";
		}
		
		return $str;
	}
	
} // YAML
