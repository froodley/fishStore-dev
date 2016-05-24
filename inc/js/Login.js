var fishStore = fishStore || {};

fishStore.Login = {
	Submit: function()
	{
		if( $('#login_form').valid() )
		{
			$.ajax( {
				type: "POST",
				url: '/Register',
				data: $("#login_form").serialize(),
				success: function( data )
				{
					 $('#main').html( data );
				}
			} );
		}
	},
	
	Reset: function()
	{
		 $('#login_form')[0].reset();
	}
	
	//TODO Center login from JS
	
};