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
		global $ini, $html, $_ENVELOPE, $menu_items;
		
		// Ensure dependency arrays exist
		if( !isset( $_ENVELOPE['dependencies'] ) )
			$_ENVELOPE['dependencies'] = [ 'js' => [], 'css' => [] ];
		
		if( !isset( $_ENVELOPE['dependencies']['js'] ) )
			$_ENVELOPE['dependencies']['js'] = [];
		elseif( !isset( $_ENVELOPE['dependencies']['css'] ) )
			$_ENVELOPE['dependencies']['css'] = [];
		
		// Create the output HTML stream
		$head = new \fishStore\View\Shared\Head();
		$topbar = new \fishStore\Cell\Topbar();
		$sidebar = new \fishStore\Cell\Sidebar();
		$footer = new \fishStore\View\Shared\Footer();
		
		$out =	$head->GetHTML() . $topbar->GetHTML( $menu_items ) .
				$html->div( ['id' => 'inner_wrapper', 'class' => 'clearfix'],
					$sidebar->GetHTML( $menu_items ) .
					$html->div(	[ 'id' => 'main_wrapper' ],
								$html->div( [ 'id' => 'main' ],
									$this->GetInnerHTML( $model )
								)
							)
				) .
				$footer->GetHTML();
		
		return $out;
	} // GetHTML
	
	
	/**
	 * GetInnerHTML
	 *
	 * Just the inner contents of the #main div for the Home controller
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetInnerHTML( \fishStore\Base\Model $model = null )
	{
		global $_ENVELOPE, $html, $ini, $inc_path;
		$out = ''
		;
		// Create the login marker
		if( isset( $_SESSION['usr'] ) )
		{
			$usr_name =	GetFML( $_SESSION['usr'] );
			$is_login =	isset( $_ENVELOPE[ 'login_success' ] ) ? '1' : '0';
			$is_reg =	isset( $_ENVELOPE[ 'reg_success' ] ) ? '1' : '0';
			$is_admin =	$_SESSION['usr']->usr_is_admin ? '1' : '0';
			
			// NOTE: Even if the user sets these attributes and fires the JS,
			// security will not allow them into admin
			$out .= $html->input(	[
										'type' => 'hidden', 'id' => 'logged_in',
										'data-user-fml' => $usr_name ,
										'data-is-login' => $is_login ,
										'data-is-reg' => $is_reg,
										'data-is-admin' => $is_admin
									]
								);
		}
		
		$out .= $html->div_beg( [ 'id' => 'home_img_cont' ] );
		$out .= $html->img( [ 'src' => '/inc/img/aquarium.jpg', 'id' => 'home_img', 'name' => 'home_img'] );
		$out .= $html->label( [ 'for' => 'home_img', 'id' => 'home_img_lbl' ], "Welcome to {$ini['STORE']['NAME']}" );
		$out .= $html->div_end();
		
		return $out;
	}
	
	public function GetDependencies()
	{
		return
		[
			'js' => [ '/View/Home/Home.js' ],
			'css' => [ '/View/Home/Home.css' ]
		];
	}
	
} // Index
