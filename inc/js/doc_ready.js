$(document).ready( function()
{
	// .hover
	$('.menu_item').on( 'mouseenter',
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
	
	if ( typeof fishStore !== 'undefined' && typeof fishStore.Home !== 'undefined' )
	{
		fishStore.Home.CheckLogin(0);
	}
	
} );

