<?php

namespace fishStore\View\admin;


/**
 * Index
 *
 * The main page for admin interface
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
	 * Return the HTML for the admin interface
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $html, $_ENVELOPE, $admin_menu_items;
		
		// Pull the correct view (done early to allow for JSON responses from inner views)
		
		$inner = '';
		if( isset( $_ENVELOPE['admin_view'] ) )
		{
			$fqcn = '\\fishStore\\View\\admin\\' . $_ENVELOPE['admin_view'];
			$view = new $fqcn();
			
			$inner = $view->GetHTML( $model );
		}
		else
			$inner = $this->_getInnerHTML();
		
		if( is_array( $inner ) )
		{
			if( isset( $inner['json'] ))
			{
				return( $inner['json'] );
			}
			else
			{
				LogMessage( "Error: View\admin\Index.php - Received unknown response from inner view: " . ArrayToStr( $inner ) );
				$inner = $this->_getInnerHTML();
			}
		}
		
		
		// Inject dependencies
		$out = self::InjectDependencies( $_ENVELOPE['dependencies'] );
		
		// Build and inject the admin menu
		$admin_menu = new \fishStore\Cell\Menu\admin();
		$admin_menu = $admin_menu->GetHTML( $admin_menu_items );
		$admin_menu = preg_replace( '/\r|\n/', '', $admin_menu );
		$admin_menu = preg_replace( '/\'/', '"', $admin_menu );
		
		// Necessary to inject this because function needs the admin menu text and must
		// prepend to the container outside this one ( #main_wrapper )
		$out .= $html->script(	[],	"$(document).ready( function() { " .
										"fishStore.admin.InjectMenu( '$admin_menu' );" .
										"$('#admin_menu_ul').delay(50).removeClass('hidden');" .
									"} );" );
		
		$out .= $inner;
		
		return $out;
	} // GetHTML
	
	
	private function _getInnerHTML()
	{
		return 'Admin Home';
	
	} // _getInnerHTML
	
	public function GetDependencies()
	{
		return
		[
			'js' => [ '/View/admin/admin.js' ],
			'css' => [ '/View/admin/admin.css' ]
		];
		
	} // GetDependencies
	
} // Index
