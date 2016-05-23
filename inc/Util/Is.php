<?php

namespace fishStore\Util;

/**
 * Is
 *
 * Validators for more complicated fields - more will be added as needed
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Is
{
	//public static function GenericValidator( $val )
	//{
	//	preg_match( '', $val, $matches);
	//	return count( $matches ) > 0;
	//}
	
	
	public static function WordString( $string )
	{
		preg_match( '/[^A-Za-z0-9\ \-\!\,\.\:\'\"\?]+/', $string, $matches);
		return count( $matches ) > 0 ? false : true;
	}
	
	public static function Email( $string )
	{
		// regex from http://emailregex.com/, to use something better than /.+@.+\..{,3}/
		preg_match( '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',
				   $string, $matches);
		return count( $matches ) > 0;
	}
	
	public static function URL( $string )
	{
		
		preg_match( '/^(?:https?:\/\/)?(?:[a-z]+\.){2,}(?:\:\d+)?[a-z]/',
				   $string, $matches);
		return ( count( $matches ) > 0 || ( strpos( $string, 'localhost' ) === 0 ? true : false ) );
	}
	
	public static function TimeZoneString( $string )
	{
		preg_match( '/[A-Z][A-Za-z]+\/[A-Z][A-Za-z]+/', $string, $matches);
		return count( $matches ) > 0;
	}
	
} // Is
