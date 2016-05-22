<?php

namespace fishStore\Interfaces;

/**
 * iController
 *
 * Interface for controller objects defines the contract
 * for REST-ful service
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
interface iController
{
	/**
	* GET
	*
	* Handles GET requests
	*
	* @param (int) An ID number associated with the query, or null
	* @param (string) The query portion of the URL (after the '?')
	* @return (string) The output HTML
	*/
	public function GET( $id, $query );
	
	
	/**
	* POST
	*
	* Handles POST requests
	*
	* @param (int) An ID number associated with the request, or null
	* @return (string) The output HTML
	*/
	public function POST( $id );
	
	
	/**
	* PUT
	*
	* Handles PUT requests
	*
	* @param (int) An ID number associated with the request, or null
	* @return (string) The output HTML
	*/
	public function PUT( $id );
	
	
	/**
	* DELETE
	*
	* Handles DELETE requests
	*
	* @param (int) An ID number associated with the request, or null
	* @param (string) The query portion of the URL (after the '?')
	* @return (string) The output HTML
	*/
	public function DELETE( $id, $query );
	
	
} // Controller