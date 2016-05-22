$(document).ready( function()
	{
		// .hover
		$('.menu_item').on( 'mouseenter',
			function ()
			{
				$(this).toggleClass( 'menu_item_hover' );
			}
		).on( 'mouseleave',
			function ()
			{
				$(this).toggleClass( 'menu_item_hover' );
			}
		);
		
	} );
