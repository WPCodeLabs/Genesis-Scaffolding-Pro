<?php

/************************************************************
 Site Header
 *************************************************************/

// Add the custom logo to the theme
add_action( 'genesis_site_title', 'the_custom_logo', 0 );

function gsp_custom_header_style() {
	// Do nothing if custom header not supported.
	if( !current_theme_supports( 'custom-header' ) ) {
		return;
	}

	$header_image = get_header_image();

	$title_selector  = genesis_html5() ? '.custom-header .site-title'       : '.custom-header #title';

	$desc_selector = genesis_html5() ? '.custom-header .site-description' : '.custom-header #description';

	$settings = gsp_theme_mod( 'header_bg' ); // get all settings at once

	// If no options set, don't waste the output. Do nothing.
	if( empty( $header_image ) ) {
		return;
	}

	// Get header selector
	if( gsp_theme_mod( 'jumbotron_bg_use_header', false ) === true ) {
		$header_selector = '.jumbotron-area';
	} else {
		$header_selector = '.site-header';
	}

	// Header selector fallback.
	if( !$header_selector ) {
		$header_selector = genesis_html5() ? '.custom-header .site-header' : '.custom-header #header';
	}

	// Header image CSS, if exists.
	if( $header_image ) {
		// Open output
		$output  = sprintf( '%s { background-image: url(%s);', $header_selector, esc_url( $header_image ) );
		// Background Repeat
		$output .= !empty( $settings['background-repeat'] ) ? sprintf( 'background-repeat: %s;', $settings['background-repeat'] ) : '';
		// Background Attachment
		$output .= !empty( $settings['background-attachment'] ) ? sprintf( 'background-attachment: %s;', $settings['background-attachment'] ) : '';
		// Background Size
		$output .= !empty( $settings['background-size'] ) ? sprintf( 'background-size: %s;', $settings['background-size'] ) : '';
		// Background Position
		$output .= !empty( $settings['background-position'] ) ? sprintf( 'background-position: %s %s;', $settings['background-position']['x-position'], $settings['background-position']['y-position']  ) : '';
		// Close output
		$output .= '}';
		// Output
		printf( '<style type="text/css">%s</style>', $output );
	}
}

function gsp_attr_site_header( $attr ) {
	$alignment = gsp_theme_mod( 'logo_alignment', 'left' );
	$attr['class'] .= " logo-aligned-{$alignment}";
	return $attr;
}
add_action( 'genesis_attr_site-header', 'gsp_attr_site_header' );

function gsp_filter_header_image_url( $image_url ) {
	// Only applicable in the singular context
	if( !is_singular() ) {
		return $image_url;
	}
	$settings = gsp_theme_mod( 'header_bg' );
	// Bail if not replacing
	if( !isset( $settings['use_featured'] ) || $settings['use_featured'] !== true ) {
		return $image_url;
	}
	// Get featured image URL
	$featured_image = get_the_post_thumbnail_url();
	// Return URL IF has featured image, else default header image
	return $featured_image ? $featured_image : $image_url;
}
add_filter( 'theme_mod_header_image', 'gsp_filter_header_image_url', 25 );




