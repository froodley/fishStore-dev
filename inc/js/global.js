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
	},
	
	ProcessKey: function( evt, key, func )
	{
		// TODO add other keys
		if ( key == 'Enter' && ( evt.which == 13 || evt.keyCode == 13 ) )
			fishStore.CallFunction( func );
		if ( key == 'Tab' && ( evt.which == 9 || evt.keyCode == 9 ) )
			fishStore.CallFunction( func );
	},
	
	CallFunction: function( fqfn )
	{
		var ns = window;
		
		// Get the rest of the arguments
		var args = [].slice.call(arguments).splice(1);
		
		// Split up the name
		var arr_ns = fqfn.split(".");
		var func = arr_ns.pop();
		
		// Fall through to the parent of the function
		for(var i = 0; i < arr_ns.length; i++)
			ns = ns[arr_ns[i]];
		
		// Call
		return ns[func].apply(ns, args);
	}
};
