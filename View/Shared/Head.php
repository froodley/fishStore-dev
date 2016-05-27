<?php

namespace fishStore\View\Shared;

/**
 * Head
 *
 * The boilerplate HTML used by all templates
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Head extends \fishStore\Base\View
{
	
	private static $_required_js =	[
										'https://code.jquery.com/jquery-2.2.4.min.js',
										'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js',
										'http://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js',
										'http://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js',
										//'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js', #TODO
										'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.js',
										JS_PATH . 'global.js',
										JS_PATH . 'angular.js',
										JS_PATH . 'directives.js',
										JS_PATH . 'doc_ready.js',
										JS_PATH . 'models.js',
										JS_PATH . 'validators.js'
									];
	
	private static $_required_css =	[
										CSS_PATH . 'global.css',
										CSS_PATH . 'clearfix.css',
										CSS_PATH . 'layout.css',
										CSS_PATH . 'menu.css',
										CSS_PATH . 'sidebar.css',
										CSS_PATH . 'topbar.css',
										CSS_PATH . 'footer.css',
										REQ_PATH . 'font-awesome-4.6.3/css/font-awesome.min.css', // font-awesome's CDN is very slow
									];
	
	/**
	 * GetHTML
	 *
	 * Return a string of HTML for output of the view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The finished tag
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $ini, $html, $_ENVELOPE;
		
		$arr_js = array_merge( self::$_required_js, $_ENVELOPE['dependencies']['js'] );
		$arr_css = array_merge( self::$_required_css, $_ENVELOPE['dependencies']['css'] );
		
		$out = "<!DOCTYPE html>\n".$html->html_beg() . $html->head_beg();
		$out .= $html->title([], $_ENVELOPE['title'] );
		
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
		$out .= $html->body_beg( [ ] ) . $html->div_beg( [ 'id' => 'site_wrapper' ] );
		
		return $out;
	
	} // GetHTML
	
	
	
} // Head
