<?php

function gsp_wrap_while() {
	// Get settings
    $settings = gsp_get_config( 'settings' );
    // Bail if it's not grid or mosaic type
    if( $settings['archives'] !== 'grid' && $settings['archives'] !== 'masonry' ) {
        return;
    }
    // Get the current filter
    $current_filter = current_filter();
    // Opening markup
    if( $current_filter === 'genesis_before_while' ) {
        genesis_markup( array( 'open' => '<div %s>', 'context' => 'archive-while-wrap' ) );
    }
    // Closing markup
    else {
        genesis_markup( array( 'close' => '</div>', 'context' => 'archive-while-wrap' ) );
    }

}
add_action( 'genesis_before_while', 'gsp_wrap_while', 999 );
add_action( 'genesis_after_endwhile', 'gsp_wrap_while', null );

function gsp_wrap_entry() {
	// Get settings
	$settings = gsp_get_config( 'settings' );

    // Bail if it's not grid or masonry type
    if( $settings['archives'] !== 'grid' && $settings['archives'] !== 'masonry' ) {
        return;
    }
    // Get the current filter
    $current_filter = current_filter();
        // Opening markup
    if( $current_filter === 'genesis_before_entry' ) {
        genesis_markup( array( 'open' => '<div %s>', 'context' => $settings['archives'] . '-entry-column' ) );
    }
    // Closing markup
    else {
        genesis_markup( array( 'close' => '</div>', 'context' => $settings['archives'] . '-entry-column' ) );
    }
}
add_action( 'genesis_before_entry', 'gsp_wrap_entry', 999 );
add_action( 'genesis_after_entry', 'gsp_wrap_entry', null );

function gsp_sticky_grid_class( $attr ) {
    $attr['class'] .= is_sticky() ? ' sticky' : '';
    return $attr;
}
add_filter( 'genesis_attr_grid-entry-column', 'gsp_sticky_grid_class' );
add_filter( 'genesis_attr_masonry-entry-column', 'gsp_sticky_grid_class' );


function gsp_set_archive_type( $attr ) {
    // Get settings
    $settings = gsp_get_config( 'settings' );
    // If one of these types, add the class
    if( $settings['archives'] === 'grid' || $settings['archives'] === 'masonry' ) {
        $attr['class'] .= ' ' . $settings['archives'] . '-archive';
    }
    return $attr;
}
add_action( 'genesis_attr_archive-while-wrap', 'gsp_set_archive_type' );

function gsp_set_archive_layout( $default_layout ) {
    $layout = gsp_theme_mod( 'archive_layout' );
    return !empty( $layout ) ? $layout : $default_layout;
}
add_action( 'genesis_pre_get_option_site_layout', 'gsp_set_archive_layout' );

function new_excerpt_more($more) {
    return sprintf( '... <a class="read-more-link" href="%s">%s</a>', get_the_permalink(), __( 'Read More', CHILD_THEME_SLUG ) );
}
add_filter('excerpt_more', 'new_excerpt_more');

function gsp_trim_excerpt( $excerpt, $original ) {
	// Get genesis settings
	$genesis = get_option( 'genesis-settings', array() );
	// Set limit
	$limit = isset( $genesis['content_archive_limit'] ) ? intval( $genesis['content_archive_limit'] ) : 0;
	// Bail if our limit is 0
	if( $limit === 0 ) {
		return $excerpt;
	}
	// Bail if we're using a custom excerpt
	if( $excerpt === $original ) {
		return $excerpt;
	}
	// Get the substring, trimmed to the nearest word
	$excerpt = substr( $excerpt, 0, $limit );
	$excerpt = substr( $excerpt, 0, strripos( $excerpt, " " ) );
	$excerpt = trim( preg_replace( '/\s+/', ' ', $excerpt ) );
	// Apply default excerpt more.
	$excerpt .= apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
	// Return our modified excerpt
	return $excerpt;
}
add_filter( 'wp_trim_excerpt', 'gsp_trim_excerpt', 10, 2 );

/**
 * Do the layout options
 */
gsp_do_layout( 'archive' );

/**
 * Include archive templates
 */
$templates = array();
// Inlude taxonomy template, such as templates/archive-category.php
if( is_tax() ) {
	$templates[] = sprintf( 'templates/archive-%s.php', get_queried_object()->taxonomy );
}
// Inlude date template, such as templates/archive-date.php
// Only has one option, archive-date.php
if( is_date() ) {
	$template[] = 'templates/archive-date.php';
}
// Include post type template, such as archive-testimonial.php
$templates[] = sprintf( 'templates/archive-%s.php', get_post_type() );
// Load each found template
locate_template( $templates, true );





