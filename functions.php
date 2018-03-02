<?php
/**
 * Genesis Scaffolding Pro
 *
 * This file adds functions to the Genesis Scaffolding Pro Theme.
 *
 * @package Genesis Scaffolding Pro
 * @author  WP Code Labs
 * @license GPL-2.0+
 * @link    https://www.wpcodelabs.com/
 * @see     https://codex.wordpress.org/Functions_File_Explained
 */

/*******************************************************************************
 *                 ______                 __  _
 *                / ____/_  ______  _____/ /_(_)___  ____  _____
 *               / /_  / / / / __ \/ ___/ __/ / __ \/ __ \/ ___/
 *              / __/ / /_/ / / / / /__/ /_/ / /_/ / / / (__  )
 *             /_/    \__,_/_/ /_/\___/\__/_/\____/_/ /_/____/
 *
 ******************************************************************************/

/**
 * Setup some theme constants
 *
 * Constants used throughout the theme
 * @since version 1.0.0
 */
define( 'CHILD_THEME_ROOT_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'CHILD_THEME_ROOT_URL', trailingslashit( get_stylesheet_directory_uri() ) );
define( 'CHILD_THEME_NAME', 'Genesis Scaffolding Pro' );
define( 'CHILD_THEME_SLUG', 'genesis_scaffolding_pro' );
define( 'CHILD_THEME_URL', 'https://www.wpcodelabs.com/' );
define( 'CHILD_THEME_VERSION', '1.0.0' );

// Start the engine.
require_once( get_template_directory() . '/lib/init.php' );
// Theme Mods
include CHILD_THEME_ROOT_DIR . 'lib/theme-mods.php';
// Theme Supports
include CHILD_THEME_ROOT_DIR . 'lib/theme-setup.php';
// Structure
include CHILD_THEME_ROOT_DIR . 'lib/structure.php';
// Header Functions
include CHILD_THEME_ROOT_DIR . 'lib/header.php';
// Jumbotron Functions
include CHILD_THEME_ROOT_DIR . 'lib/jumbotron.php';
// Breadcrumb Functions
include CHILD_THEME_ROOT_DIR . 'lib/breadcrumbs.php';
// Navigation
include CHILD_THEME_ROOT_DIR . 'lib/navigation.php';
// Footer functions
include CHILD_THEME_ROOT_DIR . 'lib/footer.php';
// Footer functions
include CHILD_THEME_ROOT_DIR . 'lib/widgets.php';
// Admin functions
include CHILD_THEME_ROOT_DIR . 'lib/admin.php';
// Conditionally include woocommerce support
if( class_exists( 'WooCommerce' ) ) {
	include CHILD_THEME_ROOT_DIR . 'lib/woocommerce.php';
}
// Customizer
include CHILD_THEME_ROOT_DIR . 'lib/customizer.php';
// include layout debugging
if( isset( $_GET['gsp_debugging'] ) ) {
	include CHILD_THEME_ROOT_DIR . 'lib/debugging.php';
}

function gsp_subtheme_include() {
	// Include subtheme specific custom functions
	$subtheme = gsp_theme_mod( 'subthemes' );
	if( !empty( $subtheme ) && $subtheme !== 'default' ) {
		if( file_exists( CHILD_THEME_ROOT_DIR . 'lib/subthemes/' . $subtheme . '/functions.php' ) ) {
			include CHILD_THEME_ROOT_DIR . 'lib/subthemes/' . $subtheme . '/functions.php';
		}
	}
}
add_action( 'wp', 'gsp_subtheme_include' );
/**
 * These should remain at bottom of functions.php (last) to override defaults farther up
 * conditionally include functions, depending on where we are at
 */
function gsp_conditional_includes() {
	if( is_singular() ) {
		include_once( CHILD_THEME_ROOT_DIR . 'lib/views/single.php' );
	}
	if( is_front_page() && !is_home() ) {
		include_once( CHILD_THEME_ROOT_DIR . 'lib/views/front-page.php' );
	}
	if( is_home() ) {
		include_once( CHILD_THEME_ROOT_DIR . 'lib/views/blog.php' );
	}
	if( is_archive() ) {
		include_once( CHILD_THEME_ROOT_DIR . 'lib/views/archives.php' );
	}
	if( is_search() ) {
		include_once( CHILD_THEME_ROOT_DIR . 'lib/views/search.php' );
	}
	if( is_404() ) {
		include_once( CHILD_THEME_ROOT_DIR . 'lib/views/404.php' );
	}
}
add_action( 'wp', 'gsp_conditional_includes' );

/**
 * Edit what shows on the post info
 *
 * Default : [post_date] by [post_author_posts_link] [post_comments] [post_edit]
 * Can reorder, replace, append, etc
 *
 * @param  strint $post_info : string of text/shortcodes to display post info
 * @return  string : edited post info
 */
// add_filter( 'genesis_post_info', 'themeprefix_post_info_filter' );
// function themeprefix_post_info_filter( $post_info ) {
// 	// $post_info = '[post_date] [post_author_posts_link] [post_comments] [post_edit]';
// 	return $post_info;
// }

// add_action( 'genesis_after_entry', 'sk_after_entry_widget_area' );
/**
 * Display after-entry widget area on the genesis_after_entry action hook.
 *
 * Scope: Static Pages
 *
 * @uses genesis_widget_area() Output widget area.
 */
function sk_after_entry_widget_area() {
	if ( !is_singular( 'page' ) || !current_theme_supports( 'genesis-after-entry-widget-area' ) ) {
		return;
	}
	if( is_front_page() ) {
		return;
	}
	genesis_widget_area( 'after-entry', array(
		'before' => '<div class="after-entry widget-area">',
		'after'  => '</div>',
	) );
}
