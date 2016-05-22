<?php

namespace fishStore\View\Shared;

define( 'JS_PATH', '/inc/js/' );
define( 'CSS_PATH', '/inc/css/' );

/**
 * Header
 *
 * The header HTML used by all templates
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Header extends \fishStore\Base\View
{
	
	private static $_required_js =	[
										'https://code.jquery.com/jquery-2.2.4.min.js',
										'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js',
										'http://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js', #TODO: Write validators
										'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js',
										'https://use.fontawesome.com/bd3446476a.js',
										JS_PATH . 'global.js'
									];
	
	private static $_required_css =	[
										CSS_PATH . 'global.css'
									];
	
	/**
	 * Display
	 *
	 * Return a string of HTML for output of the view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The finished tag
	 */
	public function Display( \fishStore\Base\Model $model = null )
	{
		global $ini, $html, $Envelope;
		
		$arr_js = array_merge( self::$_required_js, $Envelope['dependencies']['js'] );
		$arr_css = array_merge( self::$_required_css, $Envelope['dependencies']['css'] );
		
		$out = "<!DOCTYPE html>\n".$html->html_beg() . $html->head_beg();
		$out .= $html->title([], $Envelope['title'] );
		
		foreach( $arr_js as $js )
			$out .= $html->script(	[
										'type' => 'text/javascript',
										'src' => $js
									]);
		
		foreach( $arr_css as $css )
		{
			$out .= $html->link(	[
										'rel' => 'stylesheet',
										'href' => $css
									] );
		}
		
		$out .= $html->head_end();
		$out .= $html->body_beg();
		$out .= $html->div_beg( [ 'id' => 'main', 'class' => 'main' ] );
		
		return $out;
	
	} // Display
	
	
	public function GetDependencies() {}
	
	
} // Header
