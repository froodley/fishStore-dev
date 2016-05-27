var fishStore = fishStore || {};

fishStore.admin = {

	InjectMenu: function( menu )
	{
		// Check for previous injection
		var exists = $( '#admin_menu_wrapper' );
		if( exists.length != 0) return;
		
		// Replace the %% with the ' - works around the need to use ' and " in the pass mechanism
		menu = menu.replace( /\%\%/g, '\'' );
		
		$( '#main_wrapper' ).prepend( menu );
		
		//hover
		$('.admin_menu_item').on( 'mouseenter',
									function ()
									{
										$(this).toggleClass( 'link_hover' );
									}
							 ).on( 'mouseleave',
									function ()
									{
										$(this).toggleClass( 'link_hover' );
									}
								);
	}
	
}