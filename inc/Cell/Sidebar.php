<?php

namespace fishStore\Cell;

/**
 * Sidebar
 *
 * The view sidebar
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Sidebar extends \fishStore\Base\Cell
{
	/*
	 * GetHTML
	 *
	 * Return the HTML for the cell
	 *
	 * @param (array) Any data required to build the cell
	 * @return (string) The HTML for the cell
	 */
	public function GetHTML( $data = null )
	{
		global $html;
		
		$menu = new Menu\Side();
		$out =	$html->div_beg( [ 'id' => 'sidebar'] ) .
				$menu->GetHTML( $data ) .
				$html->div_end();
		
		return $out;
	}
	
} // Sidebar