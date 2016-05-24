var fishStore = fishStore || {};

fishStore.Login = {
	Submit: function()
	{
		if( $('#login_form').valid() )
		{
			$.ajax( {
				type: "POST",
				url: '/Login',
				data: $("#login_form").serialize(),
				success: function( data )
				{
					 $('#main').html( data );
					 fishStore.Home.CheckLogin();
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