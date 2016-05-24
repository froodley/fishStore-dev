
// Add regex validators

// First/Last Name
$.validator.addMethod(
		"vname",
		function(value, element ) {
			var re = new RegExp( /^[A-Za-z\'\-]{2,}$/ );
			return this.optional(element) || re.test(value);
		},
		'First and last names can contain only letters, apostrophes and hyphens.'
);

// Valid 
$.validator.addMethod(
		"mi",
		function(value, element ) {
			var re = new RegExp( /^[A-Z]$/ );
			return this.optional(element) || re.test(value);
		},
		'Middle initial can only be one capital letter.'
);

// US Phone
$.validator.addMethod(
		"usphone",
		function(value, element ) {
			var re = new RegExp( /((\([2-9]{3}\)( |-)?)|([2-9]{3}-))\d{3}-\d{4}/ );
			return this.optional(element) || re.test(value);
		},
		'Phone number must be in (557)-555-1212 or 557-555-1212 format.'
);

// Password
$.validator.addMethod(
		"password",
		function(value, element ) {
			var re = new RegExp( /(?!^[0-9]*$)(?!^[a-z]*$)(?!^[A-Z]*$)^[a-zA-Z][a-zA-Z0-9\#\!\$\%]{7,14}$/ );
			return this.optional(element) || re.test(value);
		},
		'Passwords must:<br/><ul>' +
		'<li>Be 8-15 characters long</li>' +
		'<li>Begin with a letter</hr/li>' +
		'<li>Contain at least one digit or special character</li>' +
		'<li>Contain at least one upper-case and one lower-case letter</li>' +
		'<li>Consist of only letters, numbers, and the characters #, !, $, and %</li></ul>'
);




