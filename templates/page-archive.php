<?php
/**
 *
 * Template Name: Custom Archive
 *
 */

function gsp_custom_archive_do_output() {
	// Make sure we have the function enabled
	if( !function_exists( 'get_field' ) ) {
		return false;
	}
	// Do the action once
	if( is_main_query() ) {
		do_action( 'wpcl_query', get_field( 'wp_query' ) );
	}

}
add_action( 'genesis_after_entry', 'gsp_custom_archive_do_output', null );

function gsp_custom_archive_include_settings() {
	include_once( CHILD_THEME_ROOT_DIR . 'lib/views/archives.php' );
}
add_action( 'wpcl_query_engine_before_output', 'gsp_custom_archive_include_settings' );

function gsp_custom_archive_cleanup() {
	remove_action( 'genesis_before_while', 'gsp_wrap_while', 999 );
	remove_action( 'genesis_after_endwhile', 'gsp_wrap_while', 1 );
	remove_action( 'genesis_before_entry', 'gsp_wrap_entry', 999 );
	remove_action( 'genesis_after_entry', 'gsp_wrap_entry', 1 );
}
add_action( 'wpcl_query_engine_after_output', 'gsp_custom_archive_cleanup' );

/**
 * Run genesis
 */
genesis();