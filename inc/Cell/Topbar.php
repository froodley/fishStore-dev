<?php

namespace fishStore\Cell;

/**
 * Topbar
 *
 * The site topbar
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Topbar extends \fishStore\Base\Cell
{
	/*
	 * GetHTML
	 *
	 * Return the HTML for the cell
	 *
	 * @param (array) Any data required to build the cell
	 * @return (string) The HTML for the cell
	 */
	public function GetHTML( $data = null )
	{
		global $ini, $html;
		
		// Display the header element
		$out = $html->header(	[ 'class' => 'clearfix' ],
							 
								// Store Logo
								$html->div( [ 'id' => 'store_logo_wrapper' ],
									$html->a( [ 'onclick' => 'fishStore.Link( "/Home/?reload=1" );', 'href' => '#' ],
										$html->img( [
														'id' => 'store_logo',
														'src' => $ini['STORE']['LOGO']
													] )
									)
								) .
								
								//Store Title Bar
								$html->div( [ 'id' => 'store_titlebar_wrapper' ],
											$html->span( [ 'id' => 'store_titlebar_title' ], $ini['STORE']['NAME']) .
											$html->span( [ 'id' => 'store_titlebar_motto' ], $ini['STORE']['MOTTO'])
								) .
								
								//Topbar Divider
								$html->div( [ 'id' => 'topbar_divider' ],
											$html->i( [ 'class' => 'fa fa-ellipsis-v fa-3x' ] )
								) .
								
								//Account Controls
								
								$html->div( [ 'id' => 'account_contr_wrapper', 'class' => 'hidden' ],
											$html->a( [	'id' => 'topbar_profile',
														'href' => '#',
														'onclick' => 'fishStore.Link( "/Profile" );'
														],
														$html->i( [ 'class' => 'fa fa-user' ] ) .
														$html->span( [ 'id' => 'topbar_profile_txt' ],'Profile')
													) .
											$html->a( [	'id' => 'topbar_logout',
														'href' => '#',
														'onclick' => "window.location.replace( \"http://{$ini['STORE']['URL']}/Logout\" );"
														],
														$html->i( [ 'class' => 'fa fa-power-off' ] ) .
														$html->span( [ 'id' => 'topbar_logout_txt' ],'Logout')
													)
								)
							 
							 );
		
		// Top Nav
		$top_nav = new Menu\Top();
		$out .= $top_nav->GetHTML( $data );
		
		return $out;
	}
	
} // Topbar