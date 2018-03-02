<?php

function gsp_responsive_navigation( $atts ) {
	$settings = gsp_get_config( 'navigation' );
	// Merge with defaults
	$settings = wp_parse_args( $settings, array( 'primary' => 'dropdown', 'secondary' => 'disabled' ) );
    // Get the current filter
    $current_filter = current_filter() === 'genesis_attr_nav-primary' ? 'primary' : 'secondary' ;
    // Add additional class
    $atts['class'] .= ( empty( $settings[$current_filter] ) || $settings[$current_filter] === 'disabled' ) ? ' genesis-nav' : ' genesis-responsive-nav';
    // Add the data element
    $atts['data-menu-type'] = $settings[$current_filter];
    // Return the attributes
    return $atts;
}
add_filter( 'genesis_attr_nav-primary', 'gsp_responsive_navigation' );
add_filter( 'genesis_attr_nav-secondary', 'gsp_responsive_navigation' );

/**
 * Add extra action hooks to navigation
 *
 * @param string $nav_output
 * @param string $nav
 * @param array $args
 * @return string fully formed markup
 */
function gsp_extra_do_nav_hooks( $nav_output, $nav, $args ) {
    // Open output buffer
    ob_start();
    // construct output
    do_action( 'before_genesis_do_nav' ); // generic for all
    do_action( sprintf( 'before_genesis_do_nav_%s', $args['theme_location'] ) ); // hook for specific locations
        echo $nav_output;
    do_action( sprintf( 'after_genesis_do_nav_%s', $args['theme_location'] ) ); // hook for specific locations
    do_action( 'after_genesis_do_nav' ); // generic hook for all
    // Get content
    $output = ob_get_clean();
    // Return extra markup
    return genesis_markup( array( 'open' => '', 'close' => '', 'context' => 'nav-primary-wrap', 'content' => $output ) );
}
add_filter( 'genesis_do_nav', 'gsp_extra_do_nav_hooks', 10, 3 );
add_filter( 'genesis_do_subnav', 'gsp_extra_do_nav_hooks', 10, 3 );

/**
 * Add submenu toggle buttons
 *
 * @param string $output
 * @param string $item
 * @param string $depth
 * @return string $output
 */

function gsp_submenu_toggle( $output, $item, $depth, $args ) {
	// Get layouts
	$settings = gsp_get_config( 'navigation' );
	// Merge with defaults
	$settings = wp_parse_args( $settings, array( 'primary' => 'dropdown', 'secondary' => 'disabled' ) );
    // Bail early if we don't need to do anything
    if( empty( $settings[$args->theme_location] ) || $settings[$args->theme_location] === 'disabled' ) {
    	return $output;
    }
	// If this item has children, append the button
    if( in_array( 'menu-item-has-children', $item->classes ) ){
        $output .= '<button class="sub-menu-toggle dashicons-before dashicons-arrow-down-alt2" aria-expanded="false" aria-pressed="false" role="button"><span class="screen-reader-text">Submenu</span></button>';
    }
    // Return the output
    return $output;
}
add_filter( 'walker_nav_menu_start_el', 'gsp_submenu_toggle', 999, 4 );

function gsp_site_header_attr( $attr ) {
	if( has_action( 'genesis_header', 'genesis_do_nav' ) !== false || has_action( 'genesis_header', 'genesis_do_subnav' ) !== false ) {
		$attr['class'] .= ' has-navigation';
	}
	return $attr;
}
add_filter( 'genesis_attr_site-header', 'gsp_site_header_attr' );