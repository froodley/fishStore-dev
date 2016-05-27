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
 * only allows for HTML5, data-, and ng- attribs
 *
 * Tag names with _beg or _end will generate just the beginning or end tag of a set.
 * Neither will use $contents; _beg will use the attribute array provided.
 * The tag name nbsp will return an &nbsp;
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
	 * BuildTable
	 *
	 * Generates an Angular table
	 *
	 * @param (string) The collection name within the ng-controller
	 * @param (array) AA of 'column names' => 'friendly names' to include
	 * @param (string) The prefix for table part id's
	 * @param (array) AA of 'column to link' => 'sprintf link format' to link a column using its value
	 * @param (array) The names of any ng-directives to add to the repeat
	 * 
	 * @return (string) The finished table
	 */
	public function BuildTable( $collection, $cols, $prefix, $links = null, $directives = null )
	{
		$html = $this;
		
		$out =	$html->div_beg(		[ 'id' => $prefix . '_table_wrapper' ] ) .
				$html->table_beg(	[ 'id' => $prefix . '_table' ] ) .
				$html->tbody_beg(	[ 'id' => $prefix . '_tbody' ] );
		
		$internal	= array_keys( $cols );
		$cnt		= count( $internal );
		$friendly	= array_values( $cols );
		
		// Generate header row
		$out .= $html->tr_beg( [ 'id' => $prefix . '_thr' ] );
		
		$i = 0;
		foreach( $friendly as $col )
		{
			$out .= $html->th( ['id' => $prefix . "_hdr$i"], $col );
			$i++;
		}
		
		$out .= $html->tr_end();
		
		$repeat_prms =[	'ng-repeat' => "ent in $collection  track by \$index", 'class' => $prefix . '_tr' ];
		if( isset( $directives ) && is_array( $directives ) )
		{
			foreach( $directives as $dir )
				$repeat_prms[$dir] = null;
		}
		
		
		// Generate row repeater
		$out .= $html->tr_beg( $repeat_prms );
		
		$i = 0;
		foreach( $internal as $col )
		{
			$prms = [ 'class' => $prefix . "_col$i", 'ng-bind' => "ent.$col" ];
			if( isset( $links[$col] ) )
				$prms['ng-click'] = $links[$col];
			
			$out .= $html->td( $prms, '' );
			$i++;
		}
		
		$out .= $html->tr_end();
		
		// No result row
		$out .= $html->tr();
		$out .= $html->tr( [ 'id' => $prefix . '_none_tr', 'ng-hide' => "$collection.length" ],
							 $html->td( [ 'id' => $prefix . '_none_td', 'colspan' => $cnt ],
									'No items found.' )
						 );
		
		
		// The pagination controls
		
		$out .= $html->tr_beg( [ 'id' => $prefix . '_pagination_tr' ] ) .
							 $html->td_beg( [ 'id' => $prefix . '_pagination_td', 'colspan' => $cnt ] ) .
								$html->span( [ 'id' => $prefix . '_pagination_pg1' ],
												$html->i( [ 'class' => 'fa fa-angle-double-left' ] )
								).
								$html->span( [ 'id' => $prefix . '_pagination_back' ],
												$html->i( [ 'class' => 'fa fa-angle-left' ] )
								).
								$html->span( [ 'id' => $prefix . '_pagination_pg' ],
												'1'
								).
								$html->span( [ 'id' => $prefix . '_pagination_fwd' ],
												$html->i( [ 'class' => 'fa fa-angle-right' ] )
								).
								$html->span( [ 'id' => $prefix . '_pagination_last' ],
												$html->i( [ 'class' => 'fa fa-angle-double-right' ] )
								).$html->br().
								
								$html->span( [ 'style' => 'font-style: italic; font-size: .8em; width: 200px;' ],
											"Paginitation is In Progress");

							 $html->td_end() .
				$html->tr_end();
		
		// Close table and return
		$out .=	$html->tbody_end() . $html->table_end() . $html->div_end();
		
		
		
		return $out;
	
	}
	
	
	/**
	 * _generateTag
	 *
	 * Generates a given HTML tag given its attributes and contents.
	 * Only allows HTML5 tags and attributes, as well as data- and ng- (Angular) attributes
	 *
	 * Tag names ending with _beg or _end will generate only the beginning or end tag in a set.
	 * _beg will use the provided attributes.
	 * The tag name nbsp will return an &nbsp;
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
		
		if( $tag_name == 'nbsp' )
			return '&nbsp;';
		
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
			if( !in_array( $k, self::$allowed_attribs ) &&
				( strpos( $k, 'data-' ) !== 0 ) && // Allow all data- attributes
				( strpos( $k, 'ng-' ) !== 0 ) ) // Allow all Angular directives
			{
				unset( $attribs[$k] );
			}
		}
		
		// Build attribute string
		$attrib_str = '';
		
		if( $tag_name == 'script' && !isset( $attribs[ 'type' ] ) )
			$attribs[ 'type' ] = 'text/javascript';
		elseif( $tag_name == 'link' && !isset( $attribs[ 'type' ] ) )
			$attribs[ 'type' ] = 'text/css';
			
		if( $tag_name == 'link' && !isset( $attribs[ 'ref' ] ) )
			$attribs[ 'rel' ] = 'stylesheet';
		
		foreach( $attribs as $k => $v )
		{
			if( !is_null( $v ) && strlen( $v ) )
				$attrib_str .= "$k='$v' ";
			else
				$attrib_str .= "$k "; // Void attribute
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
		
		if( $ini['SETTINGS']['MINIFY'] ) // Whether to include new lines for easy reading
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
