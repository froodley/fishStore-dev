<?php

namespace fishStore\Controller;

/**
 * Aquarium
 * 
 * The controller for the Aquarium section of the shop
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Aquarium extends \fishStore\Base\Controller
{
	
	public function GET( $id, $query)
	{
		global $_ENVELOPE;
		
		
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
} // Aquarium
