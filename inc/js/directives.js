var fishStore = fishStore || {};
fishStore.Angular = fishStore.Angular || {};

fishStore.Angular.Directives =
{
	
	UserAdminTableLoad: function( $timeout )
	{
		var func =	function( scope, element, attrs )
					{
						if ( scope.$last )
						{
							$timeout(function ()
									{
										fishStore.BoolToChx( '.tbl_users_col5, .tbl_users_col6' );
										fishStore.StyleCellAsLink( '.tbl_users_col0' );
									}
							);
						}
						
						
					};
		
		return {
			restrict: 'A',
			link: func
		}
	} // UserAdminTableLoad
	
	
};