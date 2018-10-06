$(function() {
		$( document ).tooltip({
			position: {
				my: "center bottom-2",
				at: "center top",
				using: function( position, feedback ) {
					$( this ).css( position );
					$( "<div>" )
						/* .addClass( "arrow" ) */
						.addClass( feedback.vertical )
						.addClass( feedback.horizontal )
						.appendTo( this );
				}
			}
		});
		return false;
		
		
});
