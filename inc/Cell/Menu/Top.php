<?php

namespace fishStore\Cell\Menu;

/**
 * Top
 *
 * The top menu cell
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Top extends \fishStore\Base\Cell
{
	/*
	 * GetHTML
	 *
	 * Return the HTML for the cell
	 *
	 * @param (array) The MDA to build the topmenu from
	 * @return (string) The HTML for the cell
	 */
	public function GetHTML( $items = null )
	{
		global $html;
		
		$out =	$html->div_beg( [ 'id' => 'topmenu_wrapper' ] ).
				$html->nav_beg( [ 'class' => 'topmenu' ] ) .
				$html->ul_beg( [ 'id' => 'topmenu_ul' ] );
				
		foreach( $items as $section => $section_arr )
		{
			$url = '';
			switch( $section )
			{
				case 'Shop':
					$url = '/Shop';
					break;
				case 'Learn':
					$url = '/Learn';
					break;
				case 'Account':
					$section = 'Profile';
					$url = '/Profile';
					break;
			}
			
			$out .= $html->li( [
								'class' => 'topmenu_section_lbl menu_item',
								'onclick' => "fishStore.Menu.Select( \"$url\" );"
								], $section );

			#TODO: Drop-down menus
			//$out .= $html->ul_beg( [ 'class' => 'topmenu_section_ul' ] );
			//
			//foreach( $section_arr as $text => $url )
			//	$out .= $html->li(	[
			//							'class' => 'topmenu_subitem menu_item',
			//							'onclick' => "Menu.Select( $url );"
			//						],
			//						$text);
			//	
			//$out .= $html->ul_end();
		}
		
		$out .= $html->ul_end() . $html->nav_end() . $html->div_end();
		
		return $out;
	} // GetHTML
	
} // Top