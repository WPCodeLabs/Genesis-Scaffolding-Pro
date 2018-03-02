<?php

// Remove default sidebar action
remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
// Reposition sidebar with lower priority (happens sooner)
add_action( 'genesis_after_content', 'genesis_get_sidebar', 0 );
// Remove default alt sidebar action
remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt' );
// Reposition alt sidebar with lower priority (happens sooner)
add_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt', 0 );
// Remove default footer widgets
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
// Reposition footer widgets in actual footer
add_action( 'genesis_footer', 'genesis_footer_widget_areas', 2 );

/**
 * Add classes if wrap doesn't exist
 * @param  array $atts : array of attributes
 * @param  string $context : open/close
 * @return array $atts : modified attributes
 */
function gsp_wrap_exists( $atts, $context ) {
    // Remap some keywords
    $keywords = array(
        'site-header'    => 'header',
        'nav-primary'    => 'menu-primary',
        'nav-secondary'  => 'menu-secondary',
        'site-inner'     => 'site-inner',
        'footer-widgets' => 'footer-widgets',
        'site-footer'    => 'footer',
    );
    foreach( $keywords as $keyword => $wrap ) {
        if( $context === $keyword ) {
            // Reset the context
            $context = $wrap;
            // Stop looping
            break;
        }
    }
    // Get the site support
    $structural_wraps = get_theme_support( 'genesis-structural-wraps' );
    // If false, we know we don't have have wrappers
    if( $structural_wraps === false || empty( $structural_wraps ) ) {
        $atts['class'] .= ' gsp-container';
    }
    // If $context is not in our array, we don't have a wrapper
    else if( !in_array( $context, current( $structural_wraps ) ) ) {
        $atts['class'] .= ' gsp-container';
    }
    return $atts;
}
add_filter( 'genesis_attr_site-header', 'gsp_wrap_exists', 10, 2 );
add_filter( 'genesis_attr_site-footer', 'gsp_wrap_exists', 10, 2 );
add_filter( 'genesis_attr_site-inner', 'gsp_wrap_exists', 10, 2 );
add_filter( 'genesis_attr_nav-primary', 'gsp_wrap_exists', 10, 2 );
add_filter( 'genesis_attr_nav-secondary', 'gsp_wrap_exists', 10, 2 );
add_filter( 'genesis_attr_footer-widgets', 'gsp_wrap_exists', 10, 2 );

function gsp_do_layout( $context = 'global', $layout = array() ) {
	$context = !is_string( $context ) ? 'global' : $context;
	// Get layouts
	$layouts = gsp_get_config( 'layout' );
	// Merge with defaults so we have the proper indexes
	$layouts = array_merge( array( 'global' => array(), 'archive' => array(), 'frontpage' => array() ), $layouts );
	// Get the default genesis hooks for reference
	$default = gsp_get_default_layout();
	// Set the individual layout
	$layout = !empty( $layout ) ? $layouts[$context] : array();

	if( empty( $layout ) ) {
		$layout = isset( $layouts[$context] ) ? $layouts[$context] : false;
	}

	if( $context === 'global' ) {
		// Loop over each
		foreach( $layouts['global'] as $hook => $actions ) {
			// If hooks are same as default, we can bail
			if( isset( $default[$hook] ) && array_keys( $default[$hook] ) === $actions ) {
				continue;
			}
			// Remove default actions
			if( isset( $default[$hook] ) ) {
				foreach( $default[$hook] as $genesis_action => $priority ) {
					remove_action( $hook, $genesis_action, $priority );
				}
			}
			// Add newly configured actions
			foreach( $actions as $action ) {
				add_action( $hook, $action );
			}
		}
	} else {
		if( $layout === false ) {
			return;
		}
		foreach( $layout as $hook => $actions ) {
			// If hooks are same as default && same as global, we can bail
			if( ( isset( $default[$hook] ) && array_keys( $default[$hook] ) === $actions ) && ( isset( $layouts['global'][$hook] ) && array_keys( $layouts['global'][$hook] ) === $actions )) {
				continue;
			}
			// Remove default actions
			if( isset( $default[$hook] ) ) {
				foreach( $default[$hook] as $genesis_action => $priority ) {
					remove_action( $hook, $genesis_action, $priority );
				}
			}
			// Remove global actions
			if( isset( $layouts['global'][$hook] ) ) {
				foreach( $layouts['global'][$hook] as $genesis_action ) {
					remove_action( $hook, $genesis_action );
				}
			}
			// Add newly configured actions
			foreach( $actions as $action ) {
				add_action( $hook, $action );
			}
		}
	}
	// Set up the jumbotron
	gsp_setup_jumbotron( $context );
}
add_action( 'wp', 'gsp_do_layout', null );

/**
 * Get Default Genesis layout options
 *
 * Hooks / actions / priorities of genesis default layout options
 */
function gsp_get_default_layout() {
	$defaults = array(
		'genesis_before_header' => array(),
		'genesis_header' => array(
			'genesis_do_header' => 10,
		),
		'genesis_after_header' => array(
			'genesis_do_nav' => 10,
			'genesis_do_subnav' => 10,
		),
		'genesis_before_loop' => array(
			'gsp_do_breadcrumbs' => 10,
			'genesis_do_cpt_archive_title_description' => 10,
			'genesis_do_posts_page_heading' => 10,
			'genesis_do_date_archive_title' => 10,
			'genesis_do_taxonomy_title_description' => 15,
			'genesis_do_author_title_description' => 15,
			'genesis_do_blog_template_heading' => 10,
		),
		'genesis_entry_header' => array(
			'genesis_do_post_format_image' => 4,
			'genesis_do_post_title' => 10,
			'genesis_post_info' => 12,
		),
		'genesis_entry_content' => array(
			'genesis_do_post_image' => 8,
			'genesis_do_post_content' => 10,
			'genesis_do_post_content_nav' => 12,
			'genesis_do_post_permalink' => 14,
		),
		'genesis_entry_footer' => array(
			'genesis_post_meta' => 10,
		),
		'genesis_after_content_sidebar_wrap' => array(),
		'genesis_before_footer' => array(),
		'genesis_footer' => array(
			'genesis_do_footer' => 10,
		),
	);
	return $defaults;
}

function gsp_save_additional_layout_options( $post_id ) {
	// Make sure this user has permission
	if ( !current_user_can( 'edit_post', $post_id )) {
		return;
	}
	// Verify our noonce
	if( !wp_verify_nonce( $_POST['genesis_inpost_layout_nonce'],'genesis_inpost_layout_save') ) {
    	return;
  	}
	// If it's empty, it's false
	if( empty( $_POST['_genesis_force_full_width'] ) ) {
		update_post_meta($post_id, '_genesis_force_full_width', false );
	} else {
		update_post_meta($post_id, '_genesis_force_full_width', sanitize_text_field( $_POST['_genesis_force_full_width']));
	}
	// If it's empty, it's false
	if( empty( $_POST['_genesis_disable_layout_container'] ) ) {
		update_post_meta($post_id, '_genesis_disable_layout_container', false );
	} else {
		update_post_meta($post_id, '_genesis_disable_layout_container', sanitize_text_field( $_POST['_genesis_disable_layout_container']));
	}

}
add_action('save_post', 'gsp_save_additional_layout_options');

function gsp_get_additional_layout_field() {
	// Value of meta field
	$value = get_post_meta( get_the_id(), '_genesis_force_full_width', true );
	//<p style="clear: both;"><label for="_genesis_force_full_width"><input type="checkbox" name="_genesis_force_full_width" class="force-full-width" id="force-full-width" value="on"> Force Full Width</label></p>
	$field = '<p style="clear: both;"><label for="_genesis_force_full_width">';
	$field .= sprintf( '<input type="checkbox" name="_genesis_force_full_width" class="force-full-width" id="force-full-width" value="on"%s>', checked( $value, 'on', false ) );
	$field .= ' Force Full Width</label></p>';

	return $field;
}

function gsp_get_additional_layout_container_field() {
	// Value of meta field
	$value = get_post_meta( get_the_id(), '_genesis_disable_layout_container', true );
	//<p style="clear: both;"><label for="_genesis_force_full_width"><input type="checkbox" name="_genesis_force_full_width" class="force-full-width" id="force-full-width" value="on"> Force Full Width</label></p>
	$field = '<p style="clear: both;"><label for="_genesis_disable_layout_container">';
	$field .= sprintf( '<input type="checkbox" name="_genesis_disable_layout_container" class="disable-layout-container" id="disable-layout-containe" value="on"%s>', checked( $value, 'on', false ) );
	$field .= ' Disable Layout Container</label></p>';

	return $field;
}

function gsp_debug_layout_hook() {

	if( !isset( $_GET['display_hook'] ) || empty( $_GET['display_hook'] ) ) {
		return;
	}

	global $wp_filter;

	if( !isset( $wp_filter[ $_GET['display_hook'] ] ) ) {
		return;
	}
	var_dump($wp_filter[ $_GET['display_hook'] ]);
}
add_action( 'genesis_after', 'gsp_debug_layout_hook' );


/**
 * Unregistering header right sidebar
 *
 * Recommended if using navigation in the header in place of a widget area
 */

unregister_sidebar( 'header-right' );




