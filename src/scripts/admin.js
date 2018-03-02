//genesis_inpost_layout_box
jQuery(function ($) {
    'use strict';

    var $layoutMetabox, $layoutSelect, $fullWidth, $row, $checked, $disableContainer;

    // Get the metabox
    $layoutMetabox = $('#genesis_inpost_layout_box');

    // If it exists
    if ($layoutMetabox.length) {
        // Get all of our layout select radio buttons
        $layoutSelect = $layoutMetabox.find( 'input[type=radio]' );
        // Find the closest fieldset
        $row = $layoutSelect.closest( 'fieldset' );
        // Construct our custom input
        $fullWidth = $( gsp.genesis_layout_field );
        // Get our container select
        $disableContainer = $( gsp.genesis_disable_container );
        // Hide and append our custom inputto the fieldset
        $fullWidth.hide().appendTo( $row );
        // Append the disable container
        $disableContainer.appendTo( $row );
        // If our current layout is full width, go ahead and show it.
        if( gsp.genesis_layout === 'full-width-content' ) {
            $fullWidth.show();
        }
        // Finally, bind the hide/show event
        $layoutSelect.on( 'change', function() {
            if ($(this).val() === 'full-width-content' ) {
                $fullWidth.show();
            } else {
                $fullWidth.hide();
            }
        });
    }
});