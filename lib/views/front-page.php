<?php

/**
 * Do the layout options
 *
 * Configured in theme.json
 */
gsp_do_layout( 'frontpage' );

// Remove default actions
// remove_action( 'genesis_before_loop', 'gsp_do_widget_areas' );
// remove_action( 'genesis_after_loop', 'gsp_do_widget_areas' );
// remove_action( 'genesis_after_content_sidebar_wrap', 'gsp_do_widget_areas' );

// function gsp_configure_homepage_jumbotron() {
// 	remove_action( 'gsp_jumbotron_content', 'genesis_do_post_title' );

// }
// add_action( 'gsp_jumbotron_before', 'gsp_configure_homepage_jumbotron' );

function gsp_do_homepage_slider() {
	echo do_shortcode( '[smartslider3 slider=2]' );
}
// add_action( 'gsp_jumbotron_content', 'gsp_do_homepage_slider' );