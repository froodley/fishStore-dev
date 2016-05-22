<?php

namespace fishStore\View\Home;


/**
 * Index
 *
 * The main page of the site
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Index extends \fishStore\Base\View
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
		
		$out =	$html->div_beg( [ 'style' => 'width: 50%; margin-left: 25%; margin-top: 70px; text-align: center;' ] ) .
				$html->span( [ 'style' => 'font-size: 25px; letter-spacing: .5em;' ], 'It\'s alive!' ) .
				$html->br() . $html->br() .
				$html->span( [ 'style' => 'font-size: 35px; color: red; letter-spacing: 1em;' ], $Envelope['lightning'] ) .
				$html->br() . $html->br() .
				$html->span( [ 'style' => 'font-size: 35px; color: red; letter-spacing: 1em;' ], $Envelope['lightning'] ) .
				$html->br() . $html->br() .
				$html->img(	[	'src' => 'https://kelleepratt.files.wordpress.com/2012/10/young-frankenstein.jpg',
								'alt' => "Puttin' on the ritz..."
							] ) .
				$html->br() . $html->br() .
				$html->_verbatim( [],
								 '<iframe width="340" height="200" src="https://www.youtube.com/embed/w1FLZPFI3jc" frameborder="0" allowfullscreen></iframe>'
								 ).
				$html->div_end();

		return $out;
	} // Display
	
	public function GetDependencies() {}
	
} // Index
