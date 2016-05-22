<?php

namespace fishStore\Base;

/**
 * Cell
 *
 * The abstract base class for all cells
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
abstract class Cell implements \fishStore\Interfaces\iCell
{
	/*
	 * GetHTML
	 *
	 * Return the HTML for the cell
	 *
	 * @param (array) Any data required to build the cell
	 * @return (string) The HTML for the cell
	 */
	abstract public function GetHTML( $data = null );
	
} // Cell