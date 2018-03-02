<?php

// Remove default breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

// Place breadcrumbs with wrapper and woocommerce support
function gsp_do_breadcrumbs() {
    // Set our default function
    $breadcrumbs = 'genesis_do_breadcrumbs';
    // Check for woocommerce
	if ( function_exists( 'is_woocommerce' ) ) {
		// Make sure we're on a woocommerce page
		if ( is_woocommerce() || is_cart() || is_checkout() ) {
			$breadcrumbs = 'woocommerce_breadcrumb';
		}
    }
    // Open output buffer
    ob_start();
    // Do breadcrumbs
    $breadcrumbs();
    // Get content
    $output = ob_get_clean();
    // If we don't have an output, we can bail
    if( empty( $output ) ) {
        return;
    }
    // Open our wrapper
    genesis_markup( array( 'open' => '<div %s>', 'context' => 'breadcrumbs-wrapper' ) );
    // Open structural wrap
    genesis_structural_wrap( 'breadcrumbs' );
    // Do output
    echo $output;
    // Close structural wrap
    genesis_structural_wrap( 'breadcrumbs', 'close' );
    // Close the wrapper
    genesis_markup( array( 'close' => '</div>', 'context' => 'breadcrumbs-wrapper' ) );
}