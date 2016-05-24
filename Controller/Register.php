<?php

namespace fishStore\Controller;

/**
 * Register
 * 
 * Allow new user registrations
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Register extends \fishStore\Base\Controller
{
	
	public function GET( $id, $query)
	{
		global $Envelope;
		
		
		return \fishStore\Factory\ViewFactory::Make();
		
	}
	
	public function POST( $id )
	{
		$fh = \fishStore\Util\File::OpenWrite( 'c:\box\test.txt', 'test');
		fwrite($fh, ArrayToStr($_POST));
		fclose($fh);
		
		$contr = new Home();
		return $contr->GET('Home');
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
} // Fish
