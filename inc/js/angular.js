var fishStore = fishStore || {};

fishStore.Angular = {
	
	StartAngular: function( containerID, appName, controllerName, controllerFunc, modelFactory )
	{
		var app = fishStore.Angular.GetAngular( appName );
		app.controller( controllerName , [ '$scope', '$http', 'factory', 'util', controllerFunc ] );
		app.factory( 'factory', modelFactory );
		app.service( 'util', fishStore.Get );
		
		app.config(function($logProvider){
			$logProvider.debugEnabled(true);
		});
		
		angular.bootstrap( $( '#' + containerID ) , [ appName ] );
		
		return app;
	},
	
	GetAngular: function( appName )
	{
		// Boilerplate to make angular's POST work with PHP $_POST instead of looking for application/json
		//From http://victorblog.com/2012/12/20/make-angularjs-http-service-behave-like-jquery-ajax/
		
		var app = angular.module( appName , [], function($httpProvider) {
		  // Use x-www-form-urlencoded Content-Type
		  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
		
		  /**
		   * The workhorse; converts an object to x-www-form-urlencoded serialization.
		   * @param {Object} obj
		   * @return {String}
		   */ 
		  var param = function(obj) {
			var query = '', name, value, fullSubName, subName, subValue, innerObj, i;
		
			for(name in obj) {
			  value = obj[name];
		
			  if(value instanceof Array) {
				for(i=0; i<value.length; ++i) {
				  subValue = value[i];
				  fullSubName = name + '[' + i + ']';
				  innerObj = {};
				  innerObj[fullSubName] = subValue;
				  query += param(innerObj) + '&';
				}
			  }
			  else if(value instanceof Object) {
				for(subName in value) {
				  subValue = value[subName];
				  fullSubName = name + '[' + subName + ']';
				  innerObj = {};
				  innerObj[fullSubName] = subValue;
				  query += param(innerObj) + '&';
				}
			  }
			  else if(value !== undefined && value !== null)
				query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
			}
		
			return query.length ? query.substr(0, query.length - 1) : query;
		  };
		
		  // Override $http service's default transformRequest
		  $httpProvider.defaults.transformRequest = [function(data) {
			return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
		  }];
		});
		
		return app;
	}
	
} // fishStore.Angular