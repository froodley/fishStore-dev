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
	 * GetHTML
	 *
	 * Return the global page footer
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $ini, $html, $Envelope, $menu_items;
		
		// Ensure dependency arrays exist
		if( !isset( $Envelope['dependencies'] ) )
			$Envelope['dependencies'] = [ 'js' => [], 'css' => [] ];
		
		if( !isset( $Envelope['dependencies']['js'] ) )
			$Envelope['dependencies']['js'] = [];
		elseif( !isset( $Envelope['dependencies']['css'] ) )
			$Envelope['dependencies']['css'] = [];
		
		// Create the output HTML stream
		$head = new \fishStore\View\Shared\Head();
		$topbar = new \fishStore\Cell\Topbar();
		$sidebar = new \fishStore\Cell\Sidebar();
		$footer = new \fishStore\View\Shared\Footer();
		
		$Envelope['dependencies'] = array_merge( $Envelope['dependencies'], $footer->GetDependencies() );
		
		$out =	$head->GetHTML() . $topbar->GetHTML( $menu_items ) .
				$html->div( ['id' => 'inner_wrapper'],
					$sidebar->GetHTML( $menu_items ) .
					$html->div( [ 'id' => 'main' ], $this->_getInnerHTML() )
				) .
				$footer->GetHTML();
		
		return $out;
	} // GetHTML
	
	private function _getInnerHTML()
	{
		$out = 'Home';
		
		return $out;
	}
	
} // Index
