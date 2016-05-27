var fishStore = fishStore || {};

fishStore.Home =
{
	// If the user is logged in ()
	CheckLogin: function( delay = 1000 )
	{
		var logged_in = $('#logged_in');
		
		if( logged_in.length > 0 )
		{
			$('#si_login, #ti_login').addClass('hidden');
			
			var fade_in = '#account_contr_wrapper, #ti_profile, #si_profile, #si_cart, #si_logout';
			if( logged_in.attr('data-is-admin') == 1 )
				fade_in += ', #si_admin';
			
			$( fade_in ).removeClass( 'hidden' ).fadeOut(0).fadeIn( delay );
			
			var is_login = logged_in.attr('data-is-login') == 1;
			var is_reg = logged_in.attr('data-is-reg') == 1;
			
			if ( is_login || is_reg)
			{
				var welcome_message =	'Welcome' + ( is_reg ? '' : ' back') + ', ' + logged_in.attr('data-user-fml') + '!';
				$('#main').prepend( '<span id="welcome_message">' + welcome_message + '</span>' );
			}
		}	
	} // CheckLogin
	
}; // fishStore.Home
