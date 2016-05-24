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
	 * GetHTML
	 *
	 * Return the global page footer
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $ini, $html, $_ENVELOPE;
		
		$out = '';
		
		#TODO - The rest of the footer
		$out .= $html->footer( [],
								
								$html->div( [ 'id' => 'copyright' ], '&copy;' . date('Y') . " {$ini['STORE']['NAME']}" )
							 );
		
		// Close everything
		$out .= $html->div_end() . $html->_comment( [], 'End Site Wrapper' ) . $html->body_end() . $html->html_end();
		
		return $out;
	} // GetHTML
	
	
} // Footer

