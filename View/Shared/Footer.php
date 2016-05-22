<?php

namespace fishStore\View\Shared;

/**
 * Footer
 *
 * The footer HTML used by all templates
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Footer extends \fishStore\Base\View
{
	/**
	 * Display
	 *
	 * Return the global page footer
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function Display( \fishStore\Base\Model $model = null )
	{
		global $html, $Envelope;
		
		$out = '';
		
		#TODO - The rest of the footer
		
		// Close everything
		$out .= $html->div_end() . $html->_comment( [], 'End Main' ) . $html->body_end() . $html->html_end();
		
		return $out;
	} // Display
	
	public function GetDependencies() {}
	
} // Footer

