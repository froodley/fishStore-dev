var fishStore =
{
	
	Get: function()
	{
		return fishStore;
	},
	
	Link: function( url )// method = 'GET', data = null, callback = fishStore.LoadMain  )
	{
		// Instead of using Angular UI Route, because additional dependency
		
		// Using the jQuery so the callback happens before $.ready(); need it to inject script
		$.get(	url, null,
					function( data )
					{
						fishStore.LoadMain( data );
					}
		);
		
		// For demo here's the raw JS
		//var xhr = null;
		//
		//if ( typeof XMLHttpRequest == 'undefined' )
		//{
		//	alert( 'Please update your browser' ); // Not doing any fallback for this project at this point
		//	MINOR: Insert the fallback
		//	return;
		//}
		//
		//xhr = new XMLHttpRequest();
		//
		//xhr.onreadystatechange = function() {
		//	if( (xhr.readyState < 4) || xhr.status !== 200 )
		//		return;
		//	
		//	callback( xhr.responseText );
		//};
		//
		//xhr.open( method, url, true);
		//if ( (method == 'POST' || method == 'PUT' ) && typeof data != 'undefined' )
		//{
		//	xhr.setRequestHeader( "Content-type", "application/x-www-form-urlencoded" );
		//	var json = JSON.stringify( data );
		//	xhr.send( json );
		//}
		//else
		//{
		//	xhr.send();
		//}
	},
	
	LoadMain: function( data )
	{
		// Again, jQuery to precede $.ready()
		$('#admin_menu_wrapper').remove();
		
		$('#main').html( data );
		
		
		// Again for demo, the raw JS
		//var main = document.getElementById( 'main' );
		//if ( typeof 'main' == 'undefined' )
		//{
		//	alert( 'An internal error occured; please contact an administrator.' ); // TODO: setup global for INI vals
		//	return;
		//}
		//main.innerHTML = data;
	},
	
	ResetForm: function( fm )
	{
		 $('#' + fm )[0].reset();
	},
	
	SetDisabled: function( selector, state )
	{
		$( selector ).prop( 'disabled', state );
		
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
	},
	
	ToJSON: function( obj )
	{
		var json = JSON.stringify( obj );
		return json;
	
	},
	
	BoolToChx: function( selector, toggle )
	{
		if ( typeof toggle == 'undefined' )
			toggle = false;
		
		$( selector ).each	(
								function ()
								{
									var txt = $(this).html();
									var yes = '<i class="fa fa-check chx-yes"></i>';
									var no = '<i class="fa fa-times chx-no"></i>';
									
									if ( txt == 'true' || ( toggle && txt == no ) )
										$(this).html( yes );
									else if ( txt == 'false' || ( toggle && txt == yes ) )
										$(this).html( no );
								}
							);
	},
	
	// Make a 'pseudo' link for ng-click or onclick, eg
	StyleCellAsLink: function( selector )
	{
		$( selector ).each(
			function ()
			{
				var txt = $(this).html();
				$(this).html( '<span class="link_styled">' + txt + '</span>' );
			}
		);
	}
};

// Prototype extensions
Date.prototype.toMDY = function()
{
	var yr		= this.getFullYear().toString();
	var mo		= ( this.getMonth() + 1 ).toString();
	var day 	= this.getDate().toString();
	return ( mo[1] ? mo: "0" + mo[0] ) + '/' + ( day[1] ? day : "0" + day[0]) + '/' + yr;
};