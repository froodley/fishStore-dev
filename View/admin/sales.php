<?php

namespace fishStore\View\admin;


/**
 * sales
 *
 * The admin view for the Sales collection
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class sales extends \fishStore\Base\View
{
	
	/**
	 * GetHTML
	 *
	 * Return the HTML for the Sales admin view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $html, $_ENVELOPE;
		$out = 'Sales';
		
		
		return $out;
	
	} // GetHTML
	
} // sales
