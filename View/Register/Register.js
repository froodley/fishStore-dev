var fishStore = fishStore || {};

fishStore.Register =
{
	Submit: function()
	{
		if( $('#reg_form').valid() )
		{
			var fdata = new FormData( $('#reg_form')[0] );
			$.ajax( {
				type: "POST",
				url: '/Register',
				data: fdata,
				// Allow file POST
				contentType: false, // suppress contentType HTTP Header
				processData: false, // Disable processing, send as BLOB
				// OnComplete
				success: function( data )
				{
					 $('#main').html( data );
					 fishStore.Home.CheckLogin();
				}
			} );
		}
	}
	
}; // fishStore.Register


// Handle the profile image upload
$( "#reg_profile_upload" ).on( 'change', function ()
{
	var file = this.files[0];
	
	if ( typeof( FileReader ) == "undefined" )
	{
		alert( "Your browser does not support profile preview, but your profile picture will be uploaded." );
		return;
	}
	
	var img = /image.*/;
	
	if ( !file.type.match( img ) )
	{
		alert( "File '" + file.name + "' isn't an image." );
		this.files = [];
		return;
	}

	
	var fr = new FileReader();
	fr.onload = function ( evt )
	{
		$('#reg_profile_preview').attr( 'src', evt.target.result )
	}

	fr.readAsDataURL( file );

} );

// Validate for the form
$(document).ready( function()
{
	$('#reg_form').validate
	( { 
		rules:
		{
			reg_fn:	{
						required: true,
						minlength: 2,
						vname:  true
					},
					
			reg_mi:	{
						maxlength: 1,
						mi: true
					},
					
			reg_ln:	{
						required: true,
						minlength: 2,
						vname: true
					},
					
			reg_email:	{
						required: true,
						email: true
					},
					
			reg_phone:	{
						usphone: true
						},
						
			reg_bday:	{
						date: true
						},
						
			reg_profile_upload: {
						accept: "image/*"
						},
						
			reg_pass:	{
						required: true,
						minlength: 8,
						password: true 
						},
						
			reg_pass_conf: {
						required: true,
						equalTo: '#reg_pass'
						}
		},
		
		message:
		{
			reg_fn: {
						required: 'Please enter your first name.',
						minlength: 'First name must be at least two letters.'
					},
					
			reg_mi: {
						maxlength: 'Middle initial can only be one capital letter.'
					},
					
			reg_ln: {
						required: 'Please enter your last name.',
						minlength: 'Last name must be at least two letters.'
					},
					
			reg_email: {
						required: 'Please enter your e-mail.',
						email: 'E-mail must be in name@domain.ext format.'
					},
					
			reg_bday: {
						date: 'Birth date must be a valid date in mm/dd/YYYY format.'
					},
					
			reg_profile_upload: {
						accept: 'Only image files (.jpg, .gif, .png) may be uploaded.'
					},
					
			reg_pass: {
						required: 'Please enter a password',
						minlength: 'Passwords must be 8-15 characters long.'
					},
					
			reg_pass_conf: {
						required: 'Please confirm your password.',
						equalTo: 'Error: Passwords don\'t match.'
					}
		}
	} );
} );
