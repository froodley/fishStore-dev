<?php

namespace fishStore\Controller;

/**
 * Home
 * 
 * The root controller for fishStore; called if no other controller is
 * specified in the route
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Home extends \fishStore\Base\Controller
{
	
	public function GET( $id, $query)
	{
		global $Envelope;
		
		
		return \fishStore\Factory\ViewFactory::Make();
		
	}
	
	public function POST( $id )
	{
		GET( $id, $query );
	}
	
	public function PUT( $id )
	{
		parse_str(file_get_contents("php://input"),$post_vars);
		GET( $id, $query );
	}
	
	public function DELETE( $id, $query)
	{
		GET( $id, $query );
	}
} // Home
