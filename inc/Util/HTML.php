<?php

namespace fishStore\Util;

define( 'HTML_ATTRIBS_FN', $GLOBALS['base_path'] . '\\etc\\CSV\\allowed_attribs.csv' );
define( 'HTML_EVENT_ATTRIBS_FN', $GLOBALS['base_path'] . '\\etc\\CSV\\event_attribs.csv' );
define( 'HTML_TAGS_FN', $GLOBALS['base_path'] . '\\etc\\CSV\\allowed_tags.csv' );
define( 'HTML_VOID_TAGS_FN', $GLOBALS['base_path'] . '\\etc\\CSV\\void_tags.csv' );

/**
 * HTML
 *
 * HTML tag generator - designed for use with Angular,
 * only allows for HTML5 and ng- attribs
 *
 * Tag names with _beg or _end will generate just the beginning or end tag of a set.
 * Neither will use $contents; _beg will use the attribute array provided.
 * The tag name _verbatim will return the contents verbatim inline ( for consistent use of $html ).
 * The tag name _comment will return the contents in an HTML comment.
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class HTML
{
	// Allow inspection
	public static $allowed_attribs = [];
	public static $event_attribs = [];
	public static $allowed_tags = [];
	public static $void_tags = [];
	
	
	/**
	 * __construct
	 *
	 * Populate array $attribs with allowed HTML5 attributes
	 *
	 * @return (HTML) The HTML generator
	 */
	public function __construct()
	{
		CSV::Load( self::$allowed_attribs, HTML_ATTRIBS_FN, 'HTML' );
		CSV::Load( self::$event_attribs, HTML_EVENT_ATTRIBS_FN, 'HTML' );
		self::$allowed_attribs = array_merge( self::$allowed_attribs, self::$event_attribs );
		
		CSV::Load( self::$allowed_tags, HTML_TAGS_FN, 'HTML' );
		CSV::Load( self::$void_tags, HTML_VOID_TAGS_FN, 'HTML' );
		
		#TODO: Add a list of allowed Angular directives
		
		if( !( count( self::$allowed_attribs ) && count( self::$allowed_tags ) && count( self::$allowed_tags ) ) )
		{
			LogMessage( "Error: Util\HTML.php could not load the CSV files defining the allowed HTML syntax.");
			return null;
		}
		
		array_unshift( self::$allowed_tags, '_comment', '_verbatim' );
		
	} // __construct
	
	
	/**
	 * _generateTag
	 *
	 * Generates a given HTML tag given its attributes and contents.  Tag names ending with _beg or _end will generate
	 * only the beginning or end tag in a set.  _beg will use the provided attributes.
	 * The tag name _verbatim will return the contents verbatim inline ( for consistent use of $html ).
	 * The tag name _comment will return the contents in an HTML comment.
	 *
	 * @param (string) The tag name
	 * @param (array) Associative array; the attributes of the tag
	 * @param (string) The tag contents
	 * @return (string) The finished tag
	 */
	private function _generateTag( $tag_name, $attribs = [], $contents = null )
	{
		global $ini;
		$minify = $ini['SETTINGS']['MINIFY'] === 'true' ? true : false; // Whether to include new lines for easy reading
		
		// Set if this is a begin- or end-only tag
		$beg_tag = false; $end_tag = false;
		$orig_tag = $tag_name; #TODO
		
		$tag_len = strlen( $tag_name );
		$beg_pos = strrpos( $tag_name, '_beg' );
		$end_pos = strrpos( $tag_name, '_end' );
		if ( $beg_pos === $tag_len - 4 )
		{
			$tag_name = substr( $tag_name, 0, $tag_len - 4 );
			$beg_tag = true;
		}
		elseif( $end_pos === $tag_len - 4 )
		{
			$tag_name = substr( $tag_name, 0, $tag_len - 4 );
			$end_tag = true;
		}
		
		// Check tag is allowed
		if( !in_array( $tag_name, self::$allowed_tags ) )
		{
			LogMessage( "HTML Error: Tag name $tag_name not allowed." );
			return false;
		}
		
		// Check attributes are allowed
		$attrib_names = array_keys( $attribs );
		foreach( $attrib_names as $k )
		{
			if( !in_array( $k, self::$allowed_attribs ) && ( strpos( $k, 'ng-' ) !== 0 ) ) // Allow all Angular directives
				unset( $attribs[$k] );
		}
		
		// Build attribute string
		$attrib_str = '';
		
		foreach( $attribs as $k => $v )
		{
			$attrib_str .= "$k='$v' ";
		}
		if( strlen( $attrib_str ) )
			$attrib_str = ' ' . $attrib_str;
		
		
		// Generate the correct kind of tag ( check if this is a begin- or end- only tag, a void tag, a verbatim inline, or a comment )
		if( $beg_tag )
			$tag = "<$tag_name" . "$attrib_str>";
		elseif ( $end_tag )
			$tag = "</$tag_name>";
		elseif( in_array( $tag_name, self::$void_tags ) )
			$tag = "<$tag_name" . "$attrib_str />";
		elseif( $tag_name == '_verbatim' )
			$tag = $contents;
		elseif( $tag_name == '_comment' )
			$tag = "<!-- $contents -->";
		else
			$tag = "<$tag_name" . "$attrib_str>$contents</$tag_name>";
		
		if( $minify )
			$tag .= "\n";
		
		return $tag;
	
	} // _generateTag
	
	
	public function __call( $tag_name, $args )
	{
		// Validate argument types
		if( count( $args ) &&
			(
				!( !isset( $args[0] ) || ( isset( $args[0] ) && is_array( $args[0] ) ) ) ||
				!( !isset( $args[1] ) || ( isset( $args[1] ) && is_string( $args[1] ) ) )
			)
		  )
		{
			LogMessage(	"HTML Error: Could not generate tag $tag_name; " .
						"optional arguments are an associative array of attributes, and a string containing the tag contents." );
			return null;
		}
		
		$attribs	= isset( $args[0] ) ? $args[0] : [];
		$contents	= isset( $args[1] ) ? $args[1] : null;
		
		// Return tag
		return $this->_generateTag( $tag_name, $attribs, $contents );
	
	} // __call
	
} // HTML
