<?php

function gsp_enqueue_admin_assets() {
	wp_enqueue_script( 'gsp-admin', CHILD_THEME_ROOT_URL . 'assets/js/admin.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	// Tell our javascript what the layout is

	$layout = get_post_meta( get_the_id(), '_genesis_layout', true );

	wp_localize_script( 'gsp-admin', 'gsp', array( 'genesis_layout' => $layout, 'genesis_layout_field' => gsp_get_additional_layout_field(), 'genesis_disable_container' => gsp_get_additional_layout_container_field() ) );
}
add_action( 'admin_enqueue_scripts', 'gsp_enqueue_admin_assets' );