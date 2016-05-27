<?php

namespace fishStore\View\Login;


/**
 * Index
 *
 * The login page for the site
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
	 * Return the HTML for the Login index view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $html, $_ENVELOPE;
		
		$out = self::InjectDependencies( $_ENVELOPE['dependencies'] );
		
		$out .= $html->div( [ 'id' => 'login_wrapper' ],
								$html->div( [ 'id' => 'login_header', 'class' => 'clearfix' ] ,
											$html->span( [ 'id' => 'login_header_lbl' ],
														$html->i( [ 'class' => 'fa fa-user' ] ) .
														'Login'
													) .
											$html->span( [ 'id' => 'login_register' ], 'Need to ' .
														$html->a(	[ 'id' => 'login_register_lnk',
																	'onclick' => 'fishStore.Link( "/Register" );'
																	], 'Register?'
																)
													)
								) .
								
								( isset( $_ENVELOPE['login_error'] ) ?
									$html->div( [ 'id' => 'login_error_row', 'class' => 'clearfix' ], 
												$html->span( [ 'id' => 'login_error', 'class' => 'error_lbl' ],
																		$_ENVELOPE['login_error']
															)
								) . $html->hr() : '' ).
								
								$html->form( [ 'id' => 'login_form' ],
												$html->div( ['id' => 'login_usr_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'login_usr',
																	'id' => 'login_usr_lbl' ],
																	'E-Mail:' ) .
													
													$html->span( [ 'class' => 'required' ] ) .
													$html->input( [	'type' => 'email',
																	'id' => 'login_usr',
																	'name' => 'login_usr',
																	'required' => '',
																	'placeholder' => 'user@domain.com' ]
																)
												) .
												$html->div( ['id' => 'login_pass_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'login_pass',
																	'id' => 'login_pass_lbl' ],
																	'Password:' ) .
													
													$html->span( [ 'class' => 'required' ] ) .
													$html->input( [	'type' => 'password',
																	'id' => 'login_pass',
																	'name' => 'login_pass',
																	'required' => '',
																	'onkeyup' => 'fishStore.ProcessKey( event, "Enter", "fishStore.Login.Submit" );',
																	'placeholder' => str_repeat( '&bullet;', 20 ) ] )
												) .
												$html->div( ['id' => 'login_button_row', 'class' => 'clearfix' ],
													$html->input( [ 'type' => 'button',
																	'id' => 'login_submit',
																	'value' => 'Submit',
																	'onclick' => 'fishStore.Login.Submit();'] ) .
													$html->input( [ 'type' => 'button',
																	'id' => 'login_reset',
																	'value' => 'Reset',
																	'onclick' => 'fishStore.ResetForm( "login_form" );' ] )
													
												)
											)
						);
		
		$out .= $html->script([ 'type' => 'text/javascript' ], "$('#login_form').validate();" );
		
		return $out;
	
	} // GetHTML
	
	
	public function GetDependencies()
	{
		return
		[
			'js' => [ '/View/Login/Login.js' ],
			'css' => [ '/View/Login/Login.css' ]
		];
		
	} // GetDependencies
	
	
} // Index
