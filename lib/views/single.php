<?php

/**
 * Do the layout options
 */
gsp_do_layout( 'single' );

/**
 * Include single templates
 */
// Empty template array
$templates = array();
// Include post type template, such as single-testimonial.php
$templates[] = sprintf( 'templates/single-%s.php', get_post_type() );
// Include post format template
$templates[] = sprintf( 'templates/single-%s.php', get_post_format() );
// Load each found template
locate_template( $templates, true );