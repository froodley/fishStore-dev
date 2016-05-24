<?php

namespace fishStore\View\Aquarium;


/**
 * Index
 *
 * The main page for the Aquarium section
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
	 * Return the HTML for the Aquarium index view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $html, $_ENVELOPE;
		
		$out = 'Aquarium';
		
		return $out;
	} // GetHTML
	
	
} // Index
