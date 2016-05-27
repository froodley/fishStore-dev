<?php

namespace fishStore\View\admin;


/**
 * sessions
 *
 * The admin view for the Sessions collection
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class sessions extends \fishStore\Base\View
{
	
	/**
	 * GetHTML
	 *
	 * Return the HTML for the Sessions admin view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $html, $_ENVELOPE;
		$out = 'Sessions';
		
		
		return $out;
	
	} // GetHTML
	
} // sessions
