<?php

namespace fishStore\Base;

/**
 * Controller
 *
 * The abstract base class for all controllers
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
abstract class Controller implements \fishStore\Interfaces\iController
{
	
	public function GET( $id, $query)
	{
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
	
} // Controller



