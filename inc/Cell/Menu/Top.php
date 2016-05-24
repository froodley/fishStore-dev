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
		
		$out =	$html->div_beg( [ 'id' => 'topmenu_wrapper', 'class' => 'clearfix' ] ).
				$html->nav_beg( [ 'class' => 'topmenu' ] ) .
				$html->ul_beg( [ 'id' => 'topmenu_ul' ] );
		
		foreach( $items as $section => $section_arr )
		{
			$url = '';
			switch( $section )
			{
				case 'Shop':
					$url = '/Shop';
					$id = 'ti_shop';
					break;
				case 'Learn':
					$url = '/Learn';
					$id = 'ti_learn';
					break;
				case 'Account':
					$out .= $html->li( [
								'id' => 'ti_profile',
								'class' => 'topmenu_section_lbl menu_item hidden',
								'onclick' => "fishStore.Link( \"/Profile\" );"
								], 'My Profile' );
					
					$section = 'Login/Register';
					$url = '/Login';
					$id = 'ti_login';
					break;
			}
			
			$out .= $html->li( [
								'id' => $id,
								'class' => 'topmenu_section_lbl menu_item',
								'onclick' => "fishStore.Link( \"$url\" );"
								], $section );

			#TODO: Drop-down menus
		}
		
		$out .= $html->ul_end() . $html->nav_end() . $html->div_end();
		
		return $out;
	} // GetHTML
	
} // Top