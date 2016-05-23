<?php

namespace fishStore\Cell\Menu;

/**
 * Side
 *
 * The side menu cell
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Side extends \fishStore\Base\Cell
{
	/*
	 * GetHTML
	 *
	 * Return the HTML for the cell
	 *
	 * @param (array) The MDA to build the sidemenu from
	 * @return (string) The HTML for the cell
	 */
	public function GetHTML( $items = null )
	{
		global $html, $ini;
		
		$out =	$html->nav_beg( [ 'class' => 'sidemenu' ] ) .
				$html->ul_beg( [ 'id' => 'sidemenu_ul' ] );
		
		$logged_in = isset( $_SESSION['usr'] ) ? true : false;
		
		foreach( $items as $section => $section_arr )
		{
			$out .= $html->li( [ 'class' => 'sidemenu_section_lbl' ], $section );
			$out .= $html->ul_beg( [ 'class' => 'sidemenu_section_ul' ] );
			
			foreach( $section_arr as $text => $url )
			{
				if( $logged_in && $text == 'Login/Register' )
					continue;
				if( !$logged_in && $section == 'Account' && $text != 'Login/Register' )
					continue;
				
				$out .= $html->li(	[
										'class' => 'sidemenu_subitem menu_item',
										'onclick' => ( $text != 'Logout' ? "fishStore.Link( \"$url\" );" :
													  "window.location.replace( \"http://{$ini['STORE']['URL']}{$url}\" );" ),
									], $text);
			}
				
			$out .= $html->ul_end();
		}
		
		$out .= $html->ul_end() . $html->nav_end();
		
		return $out;
	} // GetHTML
	
} // Side