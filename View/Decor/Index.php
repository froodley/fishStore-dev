<?php

namespace fishStore\View\Decor;


/**
 * Index
 *
 * The main page for the Decor section
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
	 * Return the HTML for the Decor index view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $html, $_ENVELOPE;
		
		$out = 'Decor';
		
		return $out;
	} // GetHTML
	
	
} // Index
