<?php

namespace fishStore\Cell\Menu;

/**
 * admin
 *
 * The admin topmenu
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class admin extends \fishStore\Base\Cell
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
				
		$out =	$html->div_beg( [ 'id' => 'admin_menu_wrapper', 'class' => 'clearfix' ] ).
				$html->nav_beg( [ 'class' => 'admin_menu' ] ) .
				$html->ul_beg( [ 'id' => 'admin_menu_ul', 'class' => 'hidden' ] );
		
		$len = 12; // strlen( '/admin?view=' );
		foreach( $items as $item => $url )
		{
			$id = substr( $url, $len );
			$id = 'ai_' . strtolower( $id );
			
			$out .= $html->li( [
								'id' => $id,
								'class' => 'admin_menu_item menu_item',
								'onclick' => "fishStore.Link( %%$url%% );"
								], $item );
		}
		
		$out .= $html->ul_end() . $html->nav_end() . $html->div_end();
		
		return $out;
	} // GetHTML
	
} // admin