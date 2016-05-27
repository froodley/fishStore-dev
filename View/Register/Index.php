<?php

namespace fishStore\View\Register;


/**
 * Index
 *
 * The registration page for the site
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
		
		$out .= $html->div( [ 'id' => 'reg_outer_wrapper' ],
				$html->div( [ 'id' => 'reg_inner_wrapper' ],
								$html->div( [ 'id' => 'reg_header', 'class' => 'clearfix' ] ,
											$html->span( [ 'id' => 'reg_header_lbl' ],
														$html->i( [ 'class' => 'fa fa-user' ] ) .
														'Register for an Account'
													)
								) .
								
								( isset( $_ENVELOPE['reg_error'] ) ?
									$html->div( [ 'id' => 'reg_error_row', 'class' => 'clearfix' ], 
												$html->span( [ 'id' => 'reg_error', 'class' => 'error_lbl' ],
																		$_ENVELOPE['reg_error']
															)
								) . $html->hr() : '' ).
								
								$html->div( [ 'id' => 'reg_req_lbl_row', 'class' => 'clearfix' ], 
									$html->span( [ 'id' => 'reg_req_lbl', 'class' => 'required_lbl' ],
															'* - Required'
												)
								) .
								$html->form( [ 'id' => 'reg_form' ],
												$html->div( ['id' => 'reg_fn_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'reg_fn',
																	'id' => 'reg_fn_lbl' ],
																	'First Name:' ) .
													
													$html->span( [ 'class' => 'required' ] ) .
													$html->input( [	'type' => 'text',
																	'id' => 'reg_fn',
																	'name' => 'reg_fn',
																	//'minlength' => 2,
																	//'required' => '',
																	'placeholder' => 'John' ]
																)
												) .
												$html->div( ['id' => 'reg_mi_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'reg_mi',
																	'id' => 'reg_mi_lbl' ],
																	'Middle Initial:' ) .
													
													$html->span( [ 'class' => 'not-required' ] ) .
													$html->input( [	'type' => 'text',
																	'id' => 'reg_mi',
																	'name' => 'reg_mi',
																	'maxlength' => 1,
																	'placeholder' => 'H' ]
																)
												) .
												$html->div( ['id' => 'reg_ln_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'reg_ln',
																	'id' => 'reg_ln_lbl' ],
																	'Last Name:' ) .
													
													$html->span( [ 'class' => 'required' ] ) .
													$html->input( [	'type' => 'text',
																	'id' => 'reg_ln',
																	'name' => 'reg_ln',
																	//'minlength' => 2,
																	//'required' => '',
																	'placeholder' => 'Fishowner' ]
																)
												) .
												$html->div( ['id' => 'reg_email_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'reg_email',
																	'id' => 'reg_email_lbl' ],
																	'E-mail:' ) .
													
													$html->span( [ 'class' => 'required' ] ) .
													$html->input( [	'type' => 'email',
																	'id' => 'reg_email',
																	'name' => 'reg_email',
																	//'required' => '',
																	'placeholder' => 'email@domain.com' ]
																)
												) .
												$html->div( ['id' => 'reg_phone_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'reg_phone',
																	'id' => 'reg_phone_lbl' ],
																	'Phone:' ) .
													
													$html->span( [ 'class' => 'not-required' ] ) .
													$html->input( [	'type' => 'tel',
																	'id' => 'reg_phone',
																	'name' => 'reg_phone',
																	'placeholder' => '(557) 555-1212' ]
																)
												) .
												$html->div( ['id' => 'reg_bday_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'reg_bday',
																	'id' => 'reg_bday_lbl' ],
																	'Birthday:' ) .
													
													$html->span( [ 'class' => 'not-required' ] ) .
													$html->input( [	'type' => 'date',
																	'id' => 'reg_bday',
																	'name' => 'reg_bday',
																	'placeholder' => '05/12/1975' ]
																)
												) .
												$html->div( ['id' => 'reg_profile_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'reg_profile',
																	'id' => 'reg_profile_lbl' ],
																	'Profile Picture:' ) .
													
													$html->div( [ 'id' => 'reg_profile_wrapper' ],
																$html->img( [	'id' => 'reg_profile_preview',
																				'src' => '/inc/img/default_profile_img.png' ]
																			)
															  ) .
													
																$html->label( [	'for' => 'reg_profile_upload',
																				'class' => 'file-upload',
																				'id' => 'reg_profile_upload_lbl' ],
																				'Upload Image' ) .
																$html->input( [	'type' => 'file',
																				'class' => 'hidden',
																				'id' => 'reg_profile_upload',
																				'name' => 'reg_profile_upload',
																				'accept' => ".png,.jpg,.gif"
																			] )
												) .
												$html->div( ['id' => 'reg_pass_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'reg_pass',
																	'id' => 'reg_pass_lbl' ],
																	'Password:' ) .
													
													$html->span( [ 'class' => 'required' ] ) .
													$html->input( [	'type' => 'password',
																	'id' => 'reg_pass',
																	'name' => 'reg_pass',
																	//'minlength' => 8
																	//'required' => '',
																	'placeholder' => str_repeat( '&bullet;', 20 ) ] )
												) .
												$html->div( ['id' => 'reg_pass_conf_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'reg_pass_conf',
																	'id' => 'reg_pass_conf_lbl' ],
																	'Confirm Password:' ) .
													
													$html->span( [ 'class' => 'required' ] ) .
													$html->input( [	'type' => 'password',
																	'id' => 'reg_pass_conf',
																	'name' => 'reg_pass_conf',
																	//'required' => '',
																	'placeholder' => str_repeat( '&bullet;', 20 ) ] )
												) .
												$html->div( ['id' => 'reg_button_row', 'class' => 'clearfix' ],
													$html->input( [ 'type' => 'button',
																	'id' => 'reg_submit',
																	'value' => 'Submit',
																	'onclick' => 'fishStore.Register.Submit();'] ) .
													$html->input( [ 'type' => 'button',
																	'id' => 'reg_reset',
																	'value' => 'Reset',
																	'onclick' => 'fishStore.ResetForm( "reg_form" );' ] )
													
												)
											)
						));
		
		$out .= $html->script([ 'type' => 'text/javascript' ], "$('#login_form').validate();" );
		
		return $out;
	
	} // GetHTML
	
	
	public function GetDependencies()
	{
		return
		[
			'js' => [ '/View/Register/Register.js' ],
			'css' => [ '/View/Register/Register.css' ]
		];
		
	} // GetDependencies
	
	
} // Index
