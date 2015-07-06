( function($) {
	$( '#widgets-right' )
		.on( 'click', '.view-icons', function(e) {
			var $el = $(this),
				$parent = $el.parents( '.widget-content' );

            e.preventDefault();
            $el.text( ( 'Hide Icons' == $el.text() ) ? 'View Icons' : 'Hide Icons' );
			$parent.find( '.font-awesome-picker' ).toggle();
		} )
		.on( 'click', '.font-awesome-picker .c4', function(e) {
			var $el = $(this),
				$parent = $el.parents( '.widget-content' ),
				selected_icon = $el.data( 'value' );

            e.preventDefault();
			$parent.find( '.image-widget-custom-icon' ).val( selected_icon ).trigger( 'change' );
			$parent.find( '.custom-icon-container' ).html( '<i class="fa ' + selected_icon + '" />' );
		} )
		.on( 'click', '.delete-icon', function(e) {
			var $parent = $(this).parents( '.widget-content' );

            e.preventDefault();
			$parent.find( '.image-widget-custom-icon' ).val( '' ).trigger( 'change' );
			$parent.find( '.custom-icon-container' ).html( '' );
		} );
} )(jQuery);