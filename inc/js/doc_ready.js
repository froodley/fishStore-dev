$(document).ready( function()
	{
		// .hover
		$('.menu_item, a').on( 'mouseenter',
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
		
		$('#main').css('background', '/inc/img/oliver.jpg').css('background-opactity', '20%'); /* TODO */
		alert($('#main').css('background'));
	} );
