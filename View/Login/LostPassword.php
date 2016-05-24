<?php

namespace fishStore\View\Login;


/**
 * LostPassword
 *
 * The password retrieval view
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class LostPassword extends \fishStore\Base\View
{
	
	/**
	 * GetHTML
	 *
	 * Return the HTML for the Misc index view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $html, $_ENVELOPE;
		
		$out = 'Lost Password';
		
		return $out;
	} // GetHTML
	
	
} // Index
