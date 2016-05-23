<?php

namespace fishStore\View\Login;


/**
 * Index
 *
 * The login page for the site
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Index extends \fishStore\Base\View
{
	
	/**
	 * GetHTML
	 *
	 * Return the HTML for the Login index view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $html, $Envelope;
		
		$out = self::InjectDependencies( $Envelope['dependencies'] );
		
		$out .= "<span id='login_txt'>Login</span>";
		return $out;
	} // GetHTML
	
	
	public function GetDependencies()
	{
		return
		[
			'js' => [ '/inc/js/Login.js' ],
			'css' => [ '/inc/css/Login.css' ]
		];
	}
	
	
} // Index
