<?php

/**
 * Wordpress core supports
 */
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'post-formats', array( 'aside', 'audio', 'video', 'chat', 'gallery', 'image', 'quote', 'status', 'link' ) );
add_theme_support( 'customize-selective-refresh-widgets' );
add_theme_support( 'custom-background' );
add_theme_support( 'custom-logo', array( 'flex-width'  => true, 'flex-height' => true ) );
add_theme_support( 'custom-header', array(
	'flex-height'            => true,
	'flex-width'             => true,
	'header-text'            => false,
	'wp-head-callback'       => 'gsp_custom_header_style',
	'admin-head-callback'    => 'gsp_custom_header_style',
	'admin-preview-callback' => 'gsp_custom_header_style',
));

/**
 * Genesis specific theme supports
 */
add_theme_support( 'genesis-responsive-viewport' );
add_theme_support( 'genesis-after-entry-widget-area' );
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );
add_theme_support( 'genesis-structural-wraps', gsp_get_config( 'wraps' ) );
add_theme_support( 'genesis-menus', array(
	'primary'   => __( 'Primary Navigation Menu', 'genesis' ),
	'secondary' => __( 'Secondary Navigation Menu', 'genesis' ),
) );

function gsp_conditional_theme_support() {
	if( !is_front_page() ) {
		add_post_type_support( 'page', 'genesis-after-entry-widget-area' );
	}
}
add_action( 'wp', 'gsp_conditional_theme_support' );

/**
 * Woocommerce Support
 */
add_theme_support( 'woocommerce' );

/**
 * Add custom image sizes
 */
add_image_size( 'featured-image', 830, 467, true );

function gsp_show_image_sizes($sizes) {
    $sizes['featured-image'] = __( 'Featured Image', CHILD_THEME_SLUG );
    return $sizes;
}
add_filter( 'image_size_names_choose', 'gsp_show_image_sizes' );

/**
 * Enqueue style and script assets
 */
function gsaf_enqueue_assets() {
    // Enqueue wordpress dashicons
    wp_enqueue_style( 'dashicons' );
    // Enqueue Scripts
    wp_enqueue_script( 'gsp-public', CHILD_THEME_ROOT_URL . 'assets/js/public.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
    // Get subtheme
    $subtheme = gsp_theme_mod( 'subthemes' );
    // Create path to subtheme
    $subtheme_path = empty( $subtheme ) ? false : 'assets/css/' . $subtheme . '.css';
    // Bail if our file doesn't exist
    if( $subtheme_path !== false && file_exists( CHILD_THEME_ROOT_DIR . $subtheme_path ) ) {
    	// Dequeue our default stylesheet
    	$handle  = defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';
    	wp_dequeue_style( $handle );
    	// Enqueue or custom stylesheet
    	wp_enqueue_style( 'gsp-' . $subtheme, CHILD_THEME_ROOT_URL . $subtheme_path, array(), CHILD_THEME_VERSION, 'all' );
    }
    // Enqueue additional subtheme styles
    $styles = gsp_get_config( 'styles' );

    foreach( $styles as $name => $path ) {
    	wp_enqueue_style( $name, $path, array(), 'all' );
    }

}
add_action( 'wp_enqueue_scripts', 'gsaf_enqueue_assets' );

/**
 * Do extra body classes
 */
function gsp_body_classes( $classes ) {
	$id = get_the_id();
	// Get the layout
	$layout = get_post_meta( get_the_id(), '_genesis_layout', true );
	// Get the force full width setting
	$full_width = get_post_meta( $id, '_genesis_force_full_width', true );

	if( $layout === 'full-width-content' && $full_width === 'on' ) {
		$classes[] = 'force-full-width';
	}
	// Get the container rule
	$disableContainer = get_post_meta( get_the_id(), '_genesis_disable_layout_container', true );
	if( $disableContainer === 'on' ) {
		$classes[] = 'disable-content-container';
	}
	return $classes;

}
add_filter( 'body_class', 'gsp_body_classes' );

if( is_customize_preview() ) {
	function gsp_customizer_theme_mods() {
		add_theme_support( 'genesis-structural-wraps', gsp_get_config( 'wraps' ) );
	}
	add_action( 'wp', 'gsp_customizer_theme_mods' );
}