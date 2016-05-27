<?php

namespace fishStore\View\admin;


/**
 * users
 *
 * The admin view for the Users collection
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class users extends \fishStore\Base\View
{
	
	/**
	 * GetHTML
	 *
	 * Return the HTML for the Users admin view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The HTML
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $html, $_ENVELOPE;
		$out = '';
		//$pg = ( isset( $model ) && isset( $model->page ) ) ? $model->page : 1;
		
		if( !is_null( $model) )
		{
			if( !property_exists( get_class( $model ), 'usr_coll' ) ||
			    is_null( $model->usr_coll ) )
			{
				LogMessage( 'Error: View\admin\users.php - Didn\'t receive the user collection.' );
				exit();
			}
			
			$users = $model->usr_coll;
			
			return [ 'json' => \fishStore\Base\Entity::CollectionToJSON( $users ) ];
		}
		
		$out .= self::InjectDependencies( $this->GetDependencies() );
		
		// Angular spin-up
		$out .= $html->script( [],
								"var app = fishStore.Angular.StartAngular(	'app_adm_users_cont', 'appAdminUsers', " .
																			"'ctrAdminUsers', ctrAdminUsers, " .
																			"model_UserFactory );" .
								"app.directive( 'ngFinishTable', fishStore.Angular.Directives.UserAdminTableLoad );" );
		
		$cols =	[
					'usr_id'			=> 'ID',
					'usr_email'			=> 'E-Mail',
					'usr_first_name'	=> 'First',
					'usr_middle_init'	=> 'MI',
					'usr_last_name'		=> 'Last',
					//'usr_phone'			=> 'Phone',
					//'usr_birthday'		=> 'DOB',
					//'usr_profile_img'	=> 'Image',
					'usr_is_admin'		=> 'Admin?',
					'usr_is_suspended'	=> 'Susp.?'
					//'usr_created'		=> 'Created',
					//'usr_modified'		=> 'Modified'
				];
		
		$links = [ 'usr_id' => 'LoadUserByID( $event )' ];
		//$links = [];
		
		// ng-app container
		$out .= $html->div_beg( [ 'id' => 'app_adm_users_cont' ] );
		
		// ng-controller container
		$out .= $html->div_beg(	[ 'ng-controller' => 'ctrAdminUsers', 'id' => 'mdl_adm_users_cont' ] );
		
		// Users table
		$out .= $html->BuildTable( 'Users', $cols, 'tbl_users', $links, [ 'ng-finish-table' ] );
		
		// User edit
		$out .= $html->div_beg(	[ 'id' => 'user_edit_wrapper', 'class' => 'clearfix disabled' ] );
		$out .= $html->form( [ 'id' => 'usr_edit_form' ],
												$html->div_beg( ['id' => 'usr_edit_col1', 'class' => 'usr_edit_col clearfix' ] ) .
												$html->div( ['id' => 'usr_edit_email_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'usr_edit_email',
																	'id' => 'usr_edit_email_lbl' ],
																	'E-mail:' ) .
													
													$html->span( [ 'class' => 'required' ] ) .
													$html->input( [	'type' => 'email',
																	'id' => 'usr_edit_email',
																	'name' => 'usr_edit_email',
																	'ng-model' => 'User.usr_email',
																	//'required' => '',
																	'placeholder' => 'email@domain.com' ]
																)
												) .
												$html->div( ['id' => 'usr_edit_pass_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'usr_edit_pass',
																	'id' => 'usr_edit_pass_lbl' ],
																	'Password:' ) .
													
													$html->span( [ 'class' => 'not-required' ] ) .
													$html->input( [	'type' => 'password',
																	'id' => 'usr_edit_pass',
																	'name' => 'usr_edit_pass',
																	'ng-model' => 'User.usr_password',
																	//'minlength' => 8
																	'placeholder' => str_repeat( '&bullet;', 20 ) ] )
												) .
												
												$html->div( ['id' => 'usr_edit_fn_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'usr_edit_fn',
																	'id' => 'usr_edit_fn_lbl' ],
																	'First Name:' ) .
													
													$html->span( [ 'class' => 'required' ] ) .
													$html->input( [	'type' => 'text',
																	'id' => 'usr_edit_fn',
																	'name' => 'usr_edit_fn',
																	'ng-model' => 'User.usr_first_name',
																	//'minlength' => 2,
																	//'required' => '',
																	'placeholder' => 'John' ]
																)
												) .
												$html->div( ['id' => 'usr_edit_mi_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'usr_edit_mi',
																	'id' => 'usr_edit_mi_lbl' ],
																	'MI:' ) .
													
													$html->span( [ 'class' => 'not-required' ] ) .
													$html->input( [	'type' => 'text',
																	'id' => 'usr_edit_mi',
																	'name' => 'usr_edit_mi',
																	'ng-model' => 'User.usr_middle_init',
																	'maxlength' => 1,
																	'placeholder' => 'H' ]
																)
												) .
												$html->div( ['id' => 'usr_edit_ln_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'usr_edit_ln',
																	'id' => 'usr_edit_ln_lbl' ],
																	'Last Name:' ) .
													
													$html->span( [ 'class' => 'required' ] ) .
													$html->input( [	'type' => 'text',
																	'id' => 'usr_edit_ln',
																	'name' => 'usr_edit_ln',
																	'ng-model' => 'User.usr_last_name',
																	//'minlength' => 2,
																	//'required' => '',
																	'placeholder' => 'Fishowner' ]
																)
												) .
												
												$html->div( ['id' => 'usr_edit_phone_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'usr_edit_phone',
																	'id' => 'usr_edit_phone_lbl' ],
																	'Phone:' ) .
													
													$html->span( [ 'class' => 'not-required' ] ) .
													$html->input( [	'type' => 'tel',
																	'id' => 'usr_edit_phone',
																	'name' => 'usr_edit_phone',
																	'ng-model' => 'User.usr_phone',
																	'placeholder' => '(557) 555-1212' ]
																)
												) .
												$html->div( ['id' => 'usr_edit_bday_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'usr_edit_bday',
																	'id' => 'usr_edit_bday_lbl' ],
																	'Birthday:' ) .
													
													$html->span( [ 'class' => 'not-required' ] ) .
													$html->input( [	'type' => 'date',
																	'id' => 'usr_edit_bday',
																	'name' => 'usr_edit_bday',
																	'ng-model' => 'User.usr_birthday',
																	'placeholder' => '05/12/1975' ]
																)
												) .
												$html->div( ['id' => 'usr_edit_chx_row', 'class' => 'clearfix' ],
													$html->label( [	'for' => 'usr_edit_susp',
																	'id' => 'usr_edit_susp_lbl' ],
																	'Suspended?' ) .
													$html->input( [	'type' => 'checkbox',
																	'id' => 'usr_edit_susp',
																	'name' => 'usr_edit_susp',
																	'ng-model' => 'User.usr_is_suspended',
																	'default' => false ]
																) .
													
													$html->label( [	'for' => 'usr_edit_admin',
																	'id' => 'usr_edit_admin_lbl' ],
																	'Admin?' ) .
													$html->input( [	'type' => 'checkbox',
																	'id' => 'usr_edit_admin',
																	'name' => 'usr_edit_admin',
																	'ng-model' => 'User.usr_is_admin',
																	'default' => false ]
																)
												) .
												
												$html->input( [ 'type' => 'hidden', 'id' => 'usr_edit_id',
															    'ng-model' => 'User.usr_id' ] ) .
												
												$html->div( ['id' => 'usr_edit_button_row', 'class' => 'clearfix' ],
													$html->input( [ 'type' => 'button',
																	'id' => 'usr_edit_submit',
																	'value' => 'Save',
																	'ng-click' => 'SaveUser()'] ) .
													$html->input( [ 'type' => 'button',
																	'id' => 'usr_edit_reset',
																	'value' => 'Reset',
																	'ng-click' => 'ResetUser()' ] )
													
												) .
												
												
												$html->div_end() // end col 1
												
												//TODO get the profile image going
												
												//$html->div_beg( ['id' => 'usr_edit_col2', 'class' => 'usr_edit_col clearfix' ] ) .
												//
												//$html->div( ['id' => 'usr_edit_profile_row', 'class' => 'clearfix' ],
												//	$html->label( [	'for' => 'usr_edit_profile',
												//					'id' => 'usr_edit_profile_lbl' ],
												//					'Profile Picture:' ) .
												//	
												//	$html->div( [ 'id' => 'usr_edit_profile_wrapper' ],
												//				$html->img( [	'id' => 'usr_edit_profile_preview',
												//								'src' => '/inc/img/default_profile_img.png' ]
												//							)
												//			  ) .
												//	
												//				$html->label( [	'for' => 'usr_edit_profile_upload',
												//								'class' => 'file-upload',
												//								'id' => 'usr_edit_profile_upload_lbl' ],
												//								'Upload Image' ) .
												//				$html->input( [	'type' => 'file',
												//								'class' => 'hidden',
												//								'id' => 'usr_edit_profile_upload',
												//								'name' => 'usr_edit_profile_upload',
												//								'ng-model' => 'User.usr_profile_img',
												//								'accept' => ".png,.jpg,.gif"
												//							] )
												//) .
												//
												//$html->div_end() . // Col2
												
												//$html->div( ['id' => 'usr_edit_button_row', 'class' => 'clearfix' ],
												//	$html->input( [ 'type' => 'button',
												//					'id' => 'usr_edit_submit',
												//					'value' => 'Submit',
												//					'onclick' => 'fishStore.Register.Submit();'] ) .
												//	$html->input( [ 'type' => 'button',
												//					'id' => 'usr_edit_reset',
												//					'value' => 'Reset',
												//					'onclick' => 'fishStore.Register.Reset();' ] )
												//	
												//)
											);
		
		$out .= $html->div_end();
		
		// End ng-app
		$out .= $html->div_end();
		
		return $out;
	
	} // GetHTML
	
	
	public function GetDependencies()
	{
		return
		[
			'js' => [ '/View/admin/users.js' ],
			'css' => [ '/View/admin/users.css', '/View/admin/user_edit.css' ]
		];
		
	} // GetDependencies
	
} // users
