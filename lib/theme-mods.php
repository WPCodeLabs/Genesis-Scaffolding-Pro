<?php

function gsp_get_config( $context = '' ) {

	$subtheme = gsp_theme_mod( 'subthemes' );

	$subtheme = empty( $subtheme ) ? 'lib/subthemes/default/theme.json' : 'lib/subthemes/' . $subtheme . '/theme.json';
	// Bail if our file doesn't exist
	if( !file_exists( CHILD_THEME_ROOT_DIR . $subtheme ) ) {
		return array();
	}
	// Get config file
	$config = json_decode( file_get_contents( CHILD_THEME_ROOT_DIR . $subtheme ), true );

	$config = apply_filters( 'gsp_theme_config', $config );

	if( !empty( $context ) ) {
		return isset( $config[$context] ) ? $config[$context] : array();
	}

	return $config;
}

function gsp_config_defaults( $config ) {
	$default = array(
		'settings' => array(
			'dev_mode' => false,
			'archives' => null,
		),
		'styles' => array(),
		'layout' => array(
			'global' => array(),
		),
		'navigation' => array(
			'primary' => 'dropdown',
			'secondary' => 'disabled',
		),
		'jumbotron' => array(
			'global' => array(),
		),
		'wraps' => array(
			'header',
			'menu-primary',
			'menu-secondary',
			'site-inner',
			'before-header-widgets',
			'after-header-widgets',
			'after-content-widgets',
			'before-footer-widgets',
			'footer-widgets',
			'footer',
			'jumbotron',
			'breadcrumbs',
		),
	);
	// Setup an empty array to write to
	$merged = array();
	// Make sure $config is an array
	$config = is_array( $config ) ? $config : array();
	// Do the merge
	foreach( $default as $index => $value ) {
		// First, just set the index
		$merged[$index] = isset( $config[$index] ) ? $config[$index] : $default[$index];
		// Conditionally merge with defaults
		if( gsp_is_associative_array( $value ) ) {
			$merged[$index] = array_merge( $default[$index], $merged[$index] );
		}
	}
	return $merged;
}
add_filter( 'gsp_theme_config', 'gsp_config_defaults', null );

function gsp_is_associative_array( $array ) {
	$keys = array_keys($array);
	return array_keys($keys) !== $keys;
}


function gsp_get_theme_defaults( $name = null ) {
	$defaults = array(
		'header_bg' => array(
			'background-repeat' => 'no-repeat',
			'background-size'   => 'cover',
			'background-attachment' => null,
			'background-position' => array(
				'x-position' => '50%',
				'y-position' => '50%',
			),
			'use_featured' => false,
		),
		'primary_nav_responsive' => 'dropdown',
		'secondary_nav_responsive' => 'disabled',
		'archive_type' => null,
		'archive_layout' => null,
		'jumbotron_enable' => false,
		'jumbotron_use_title' => false,
		'jumbotron_use_widget_area' => false,
		'jumbotron_bg_use_header' => true,
		'jumbotron_priority' => 10,
		'jumbotron_bg' => array(
			'background-color'      => 'rgba( 20, 20, 20, .8)',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'cover',
			'background-attachment' => 'scroll',
		),
	);
	// Will allow the import of settings
	$defaults = apply_filters( 'gsp_theme_default_config', $defaults );
	// Return single if defined
	if( !is_null( $name ) && isset( $defaults[$name] ) ) {
		return $defaults[$name];
	}
	// Return all settings if no setting is defined
	return $defaults;
}

/**
 * Get Theme Mod
 *
 * @param string $name
 * @return mixed
 */
function gsp_theme_mod( $name = null, $default = false ) {

	// Get the settings from the database
	$settings = get_theme_mod( CHILD_THEME_SLUG, array() );
	// Merge with defaults
	$settings = array_replace( gsp_get_theme_defaults(), $settings );
	// Return single if defined
	if( !is_null( $name ) ) {
		$theme_mod = isset( $settings[$name] ) ? $settings[$name] : $default;
		return apply_filters( "gsp_theme_mod_{$name}", $theme_mod );
	}
	// Return all settings if no setting is defined
	else {
		return apply_filters( 'gsp_theme_mod', $settings );
		return $settings;
	}
}

function gsp_remove_page_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}
add_action( 'theme_page_templates', 'gsp_remove_page_templates' );

/*
 * Removing custom title/logo metabox from Genesis theme options page.
 */
function gsp_remove_metaboxes( $_genesis_admin_settings ) {
	remove_meta_box( 'genesis-theme-settings-header', $_genesis_admin_settings, 'main' );
}
add_action( 'genesis_theme_settings_metaboxes', 'gsp_remove_metaboxes' );

/*
 * Removing custom title/logo metabox from Genesis customizer
 */
function gsp_remove_customizer_control( $wp_customize ) {
	$wp_customize->remove_control( 'blog_title' );
	$wp_customize->remove_control( 'genesis_content_archive_thumbnail' );
}
add_action( 'customize_register', 'gsp_remove_customizer_control', 999 );
// Force the setting true, since we'll control by other means
add_filter( 'genesis_pre_get_option_content_archive_thumbnail', '__return_true' );

/**
 * @param  stop images being wrapped in P tags
 * @return [type]
 */
function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter( 'the_content', 'filter_ptags_on_images' );

/**
 * Filter a few parameters into YouTube oEmbed requests
 */
function gsp_responsive_video_embeds( $html, $url, $args ) {
	// If the function we rely on doesn't exist, we need to bail
	if( !function_exists( '_wp_oembed_get_object' ) ) {
		return $html;
	}
	// Get the oembed object
	$wp_oembed = _wp_oembed_get_object();
	// Get the oembed data
	$data = $wp_oembed->get_data( $url );
	// Bail if we don't have any data
	if( $data === false ) {
		return $html;
	}
	// if it's not a video, bail
	if( $data->type !== 'video' ) {
		return $html;
	}
	// Set base class
	$class  = 'flex-video';
	// Set provider class
	$class .= isset( $data->provider_name ) ? ' ' . strtolower( $data->provider_name ) : '';
	// Set aspect ratio
	if( isset( $data->width ) && isset( $data->height ) ) {
		$class .= ( $data->height / $data->width < .6 ) ? ' widescreen' : ' standard';
	}
	// Now we can construct a wrapper
	$output  = sprintf( '<div class="%s">', trim( $class ) );
	$output .= $html;
	$output .= '</div>';
	// Return the newly constructed output
	return $output;

}
add_filter( 'oembed_result', 'gsp_responsive_video_embeds', 10, 3 );

function gsp_default_featured_image( $defaults, $args ) {
	// Get default image
	$default = gsp_theme_mod( 'featured_image' );
	// If nothing set, bail
	if( !$default ) {
		return $defaults;
	}
	// Set as fallback
	$defaults['fallback'] = $default;
	// Return settings
	return $defaults;
}
add_filter( 'genesis_get_image_default_args', 'gsp_default_featured_image', 10, 2 );

