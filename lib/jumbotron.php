<?php

function gsp_setup_jumbotron( $context = 'global' ) {
	// Get config
	$settings = gsp_get_config( 'jumbotron' );
	// Get all default hooks
	$hooks = gsp_get_default_layout();
	// See if we have a hook or not
	if( gsp_has_jumbotron() === false ) {
		return;
	}
	// Make sure context is a string
	$context = is_string( $context ) ? $context : 'global';

	if( $context === 'global' ) {
		// Setup content
		foreach( $settings['global'] as $setting ) {
			if( $setting === 'gsp_jumbotron_headlines' ) {
				gsp_set_jumbotron_headlines();
			} else {
				add_action( 'gsp_jumbotron_content', $setting );
			}
		}
	} else {
		// Bail if our context isn't set
		if( !isset( $settings[$context] ) ) {
			return;
		}
		// Remove global actions
		foreach( $settings['global'] as $setting ) {
			// If it exists in both, we don't need to do anything and can bail
			// if( in_array( $setting, $settings[$context] ) ) {
			// 	continue;
			// }
			// Keep going if it needs removed
			if( $setting === 'gsp_jumbotron_headlines' ) {
				gsp_unset_jumbotron_headlines();
			} else {
				remove_action( 'gsp_jumbotron_content', $setting );
			}
		}
		// Add contextual settings
		foreach( $settings[$context] as $setting ) {
			// If it's already added, we can bail, we don't need to do anything
			// if( in_array( $setting, $settings['global'] ) ) {
			// 	continue;
			// }
			// Keep going if needs added
			if( $setting === 'gsp_jumbotron_headlines' ) {
				gsp_set_jumbotron_headlines();
			} else {
				add_action( 'gsp_jumbotron_content', $setting );
			}
		}
	}
}
// add_action( 'wp', 'gsp_setup_jumbotron' );

/**
 * Setup jumbotron headlines
 * adds/removes appropriate actions and filters to do headlines in jumbotron
 */
function gsp_set_jumbotron_headlines() {
	// On singular pages, we just need to add the post title
	if( is_singular() ) {
		add_action( 'gsp_jumbotron_content', 'genesis_do_post_title' );
	}
	// Handle search specific actions
	else if( is_search() ) {
		remove_action( 'genesis_before_loop', 'genesis_do_search_title' );
		add_action( 'gsp_jumbotron_content', 'genesis_do_search_title' );
	}
	// Add all for our archive heading / description
	add_action( 'gsp_jumbotron_content', 'genesis_do_cpt_archive_title_description' );
	add_action( 'gsp_jumbotron_content', 'genesis_do_posts_page_heading' );
	add_action( 'gsp_jumbotron_content', 'genesis_do_date_archive_title' );
	add_action( 'gsp_jumbotron_content', 'genesis_do_taxonomy_title_description' );
	add_action( 'gsp_jumbotron_content', 'genesis_do_author_title_description' );
	add_action( 'gsp_jumbotron_content', 'genesis_do_blog_template_heading' );

	// Remove the intro text for this spot only
	add_filter( 'genesis_term_intro_text_output', '__return_false' );
	add_filter( 'genesis_author_intro_text_output', '__return_false' );
	add_filter( 'genesis_cpt_archive_intro_text_output', '__return_false' );
	// Remove the wrappers
	remove_action( 'genesis_archive_title_descriptions', 'genesis_do_archive_headings_open', 5, 3 );
	remove_action( 'genesis_archive_title_descriptions', 'genesis_do_archive_headings_close', 15, 3 );
	// Make sure we have the title action we need
	if( !has_action( 'genesis_archive_title_descriptions', 'genesis_do_archive_headings_headline' ) ) {
		add_action( 'genesis_archive_title_descriptions', 'genesis_do_archive_headings_headline', 10, 3 );
	}
	// Setup our action to unset filters and stuff
	add_action( 'gsp_jumbotron_after', 'gsp_unset_jumbotron_headlines' );
}


/**
 * Unset headlines so the actions can be reused by genesis core
 */
function gsp_unset_jumbotron_headlines() {
	// Remove actions
	remove_action( 'gsp_jumbotron_content', 'genesis_do_search_title' );
	remove_action( 'gsp_jumbotron_content', 'genesis_do_post_title' );
	remove_action( 'gsp_jumbotron_content', 'genesis_do_cpt_archive_title_description' );
	remove_action( 'gsp_jumbotron_content', 'genesis_do_posts_page_heading' );
	remove_action( 'gsp_jumbotron_content', 'genesis_do_date_archive_title' );
	remove_action( 'gsp_jumbotron_content', 'genesis_do_taxonomy_title_description' );
	remove_action( 'gsp_jumbotron_content', 'genesis_do_author_title_description' );
	remove_action( 'gsp_jumbotron_content', 'genesis_do_blog_template_heading' );
	// remove filters
	remove_filter( 'genesis_term_intro_text_output', '__return_false' );
	remove_filter( 'genesis_author_intro_text_output', '__return_false' );
	remove_filter( 'genesis_cpt_archive_intro_text_output', '__return_false' );
	// Remove titles from normal intro area
	remove_action( 'genesis_archive_title_descriptions', 'genesis_do_archive_headings_headline', 10, 3 );
	// Re-add the wrappers
	add_action( 'genesis_archive_title_descriptions', 'genesis_do_archive_headings_open', 5, 3 );
	add_action( 'genesis_archive_title_descriptions', 'genesis_do_archive_headings_close', 15, 3 );
}



/**
 * Do the jumbotron
 */
function gsp_do_jumbotron() {
	// First action
	do_action( 'gsp_jumbotron_before' );
	// Open markup
	genesis_markup( array( 'open' => '<section %s>', 'context' => 'jumbotron-area' ) );
	// Do inner wrapper
	genesis_markup( array( 'open' => '<div %s>', 'context' => 'jumbotron-area-inner' ) );
	// Do structural wrap
	genesis_structural_wrap( 'jumbotron' );
	// Do before content
	do_action( 'gsp_jumbotron_before_content' );
	// Do content
	do_action( 'gsp_jumbotron_content' );
	// Do before content
	do_action( 'gsp_jumbotron_after_content' );
	// Close structural wrap
	genesis_structural_wrap( 'jumbotron', 'close' );
	// Close wrapper
	genesis_markup( array( 'close' => '</div>', 'context' => 'jumbotron-area-inner' ) );
	// Closed markup
	genesis_markup( array( 'close' => '</section>', 'context' => 'jumbotron-area' ) );
	// Do final action
	do_action( 'gsp_jumbotron_after' );
}


/**
 * Output the jumbotron style
 */
function gsp_jumbotron_style() {
	// If we're using the "Custom Header Image", we can bail...the styles are already set
	if( gsp_theme_mod( 'jumbotron_bg_use_header' ) === true ) {
		return false;
	}
	// Get settings
	$settings = gsp_theme_mod( 'jumbotron_bg' );
	// Construct output
	$output = '.jumbotron-area {';
		// Background Color
		$output .= !empty( $settings['background-color'] ) ? sprintf( 'background-color: %s;', $settings['background-color'] ) : '';
		// Background Image
		$output .= !empty( $settings['background-image'] ) ? sprintf( 'background-image: url(%s);', $settings['background-image'] ) : '';
		// Background Repeat
		$output .= !empty( $settings['background-repeat'] ) ? sprintf( 'background-repeat: %s;', $settings['background-repeat'] ) : '';
		// Background Attachment
		$output .= !empty( $settings['background-attachment'] ) ? sprintf( 'background-attachment: %s;', $settings['background-attachment'] ) : '';
		// Background Size
		$output .= !empty( $settings['background-size'] ) ? sprintf( 'background-size: %s;', $settings['background-size'] ) : '';
		// Background Position
		$output .= !empty( $settings['background-position'] ) ? sprintf( 'background-position: %s;', $settings['background-position'] ) : '';
	// Close output
	$output .= '}';
	// Output
	printf( '<style type="text/css">%s</style>', $output );

}

// Helper functions
function gsp_get_author_outside_loop( $name ) {
	if( empty( $name ) && !in_the_loop() ) {
		global $post;
		$name = get_the_author_meta( 'display_name', $post->post_author );
	}
	return $name;
}
add_filter( 'the_author', 'gsp_get_author_outside_loop' );

function gsp_get_author_id_outside_loop( $value ) {
	if( empty( $value ) && !in_the_loop() ) {
		global $post;
		$value = get_the_author_meta( 'id', $post->post_author );
	}
	return $value;
}
add_filter( 'get_the_author_ID', 'gsp_get_author_id_outside_loop' );

function gsp_has_jumbotron() {
	// Get all default hooks
	$hooks = gsp_get_default_layout();
	// See if we have a hook or not
	$has_action = false;
	// loop through each
	foreach( $hooks as $hook => $actions ) {
		if( has_action( $hook, 'gsp_do_jumbotron' ) !== false ) {
			$has_action = true;
			break;
		}
	}
	return $has_action;
}