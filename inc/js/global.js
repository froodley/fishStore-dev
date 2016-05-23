var fishStore =
{
	Link: function( url )
	{
		$.get(	url, null,
				function( data )
				{
					$('#main').html( data );
				}
		);
	}
};
