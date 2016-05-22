var fishStore = fishStore || {};

fishStore.Menu = 
{
	Select: function( url )
	{
		$.get(	url, null,
				function( data )
				{
					$('#main').html( data );
				}
		);
	}
	
	
	
};