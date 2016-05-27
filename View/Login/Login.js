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
					 $(document).ready( function() { fishStore.Home.CheckLogin() } );
				}
			} );
		}
	}
	
	//TODO Center login wrapper
	
};