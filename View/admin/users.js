var fishStore = fishStore || {};


// Angular controller
function ctrAdminUsers( $scope, $http, factory, util )
{
	$scope.Util = util;
	$scope.Util.SetDisabled( '#usr_edit_form input', true );
	
	$scope.User = factory.Create(); // Demo of IOC
	
	$scope.Users = [];
	//$scope.page = 1;
	
	$scope.Load = function ()
	{
		$http( { method: "GET", url: "/admin?view=users&load=coll" } ).
				 success( function ( data, status, headers, config )
							{
								//TODO Still working on the pagination
								//$scope.page = data.pg;
								//alert(data.pg);
								
								$scope.Users = data;
							} );
	};
	
	$scope.LoadUserByID = function($event )
	{
		var id = $event.target.innerHTML;
		var user = null;
		
		for( var i = 0; i < $scope.Users.length; i++ )
		{
			var row = $scope.Users[i];
			
			if ( row.usr_id == id )
			{
				user = row;
				user.usr_birthday = new Date( user.usr_birthday );
			}
		}
		
		$scope.User = user;
		$scope.Util.SetDisabled( '#usr_edit_form input', false );
	}
	
	$scope.SaveUser = function()
	{
		$scope.User.usr_birthday = $scope.User.usr_birthday.toMDY();
		
		$http(	{ 	method: "POST",
					url: "/admin?view=users",
					data: $scope.User
				} ).
				success( function ( data, status, headers, config )
							{
								$scope.Users = data;
								$scope.User = factory.Create();
								$scope.Util.SetDisabled( '#usr_edit_form input', true );
							} );
	}
	
	$scope.ResetUser = function()
	{
		$scope.User = factory.Create();
		$scope.Util.ResetForm( "usr_edit_form" );
		$scope.Util.SetDisabled( '#usr_edit_form input', true );
	}
	
	$scope.Load();
	
} // ctrAdminUsers

	

//TODO: Some way to abstract out what's common between this and registration and profile... But they have a few different rules...
// Probably have to write an array deep copy so i can alter the copies at runtime...

// Validate for the form
$(document).ready( function()
{
	// Validate for the form
	$('#usr_edit_form').validate
	( { 
		rules:
		{
			usr_edit_fn:	{
						required: true,
						minlength: 2,
						vname:  true
					},
					
			usr_edit_mi:	{
						maxlength: 1,
						mi: true
					},
					
			usr_edit_ln:	{
						required: true,
						minlength: 2,
						vname: true
					},
					
			usr_edit_email:	{
						required: true,
						email: true
					},
					
			usr_edit_phone:	{
						usphone: true
						},
						
			usr_edit_bday:	{
						date: true
						},
						
			usr_edit_profile_upload: {
						accept: "image/*"
						},
						
			usr_edit_pass:	{
						minlength: 8,
						password: true 
						}
		},
		
		message:
		{
			usr_edit_fn: {
						required: 'Please enter your first name.',
						minlength: 'First name must be at least two letters.'
					},
					
			usr_edit_mi: {
						maxlength: 'Middle initial can only be one capital letter.'
					},
					
			usr_edit_ln: {
						required: 'Please enter your last name.',
						minlength: 'Last name must be at least two letters.'
					},
					
			usr_edit_email: {
						required: 'Please enter your e-mail.',
						email: 'E-mail must be in name@domain.ext format.'
					},
					
			usr_edit_bday: {
						date: 'Birth date must be a valid date in mm/dd/YYYY format.'
					},
					
			usr_edit_profile_upload: {
						accept: 'Only image files (.jpg, .gif, .png) may be uploaded.'
					},
					
			usr_edit_pass: {
						required: 'Please enter a password',
						minlength: 'Passwords must be 8-15 characters long.'
					},
					
			usr_edit_pass_conf: {
						required: 'Please confirm your password.',
						equalTo: 'Error: Passwords don\'t match.'
					}
		}
	} );
} );