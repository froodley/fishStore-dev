<?php

namespace fishStore\Interfaces;

/**
 * iCell
 *
 * Interface for Cells; essentially re-usable widgets
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
interface iCell
{
	/*
	 * GetHTML
	 *
	 * Return the HTML for the cell
	 *
	 * @param (array) Any data required to build the cell
	 * @return (string) The HTML for the cell
	 */
	public function GetHTML( $data = null );
	
} // iCell