<?php

/**
 *
 * Template Name: Landing Page
 *
 */

// Do structure
// Defined in theme.json
gsp_do_layout( 'landing' );
// Force full with layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

genesis();