<?php

// Add the footer widgets
add_theme_support( 'genesis-footer-widgets', gsp_theme_mod( 'footer_widgets', 3 ) );
// Add the after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );
// Add custom widget areas
$widget_areas = gsp_theme_mod( 'widget_areas', array() );

function gsp_register_widget_areas() {
	// Get all the sidebar names
	$sidebars = gsp_get_extra_sidebars();
	// Get the layout options
	$layouts = gsp_get_config( 'layout' );
	// Loop through our layout to find which ones to register
	foreach( $layouts as $view => $hooks ) {
		// Loop through each action in the hook
		foreach( $hooks as $hook => $actions ) {
			// Register widget area if array contains this action
			if( in_array( 'gsp_do_widget_areas', $actions ) ) {
				// do some string replacement
				$sidebar_name = str_ireplace('genesis_', '', $hook );
				$sidebar_name = str_ireplace('_', '-', $sidebar_name );
				if( isset( $sidebars[$sidebar_name] ) ) {
					genesis_register_sidebar( array(
						'id' => $sidebar_name,
						'name' => $sidebars[$sidebar_name],
					) );
				}
			}
		}
	}
}
add_action( 'init', 'gsp_register_widget_areas' );

function gsp_get_extra_sidebars( $name = null ) {
	$sidebars = array(
		'before-header' => __( 'Before Header', CHILD_THEME_SLUG ),
		'after-header' => __( 'After Header', CHILD_THEME_SLUG ),
		'before-loop' => __( 'Before Loop', CHILD_THEME_SLUG ),
		'entry-content' => __( 'Entry Content', CHILD_THEME_SLUG ),
		'after-entry-content' => __( 'After Entry Content', CHILD_THEME_SLUG ),
		'after-loop' => __( 'After Loop', CHILD_THEME_SLUG ),
		'after-content' => __( 'After Content', CHILD_THEME_SLUG ),
		'before-content-sidebar-wrap' => __( 'Before Content Sidebar Wrap', CHILD_THEME_SLUG ),
		'after-content-sidebar-wrap' => __( 'After Content Sidebar Wrap', CHILD_THEME_SLUG ),
	);
	// Return specific one, or all of them
	if( !is_null( $name ) && isset( $sidebars[$name] ) ) {
		return $sidebars[$name];
	}
	return $sidebars;
}

function gsp_widget_area_classes( $attr ) {
	$attr['class'] .= ' widget-area';
	return $attr;
}
add_action( 'genesis_attr_before-header-widgets', 'gsp_widget_area_classes' );


function gsp_do_widget_areas() {
	// Get registered sidebars
	global $wp_registered_sidebars;
	// Set default widget area names
	$widget_names = array(
		'before-header' => __( 'Before Header', CHILD_THEME_SLUG ),
		'after-header' => __( 'After Header', CHILD_THEME_SLUG ),
		'before-loop' => __( 'Before Loop', CHILD_THEME_SLUG ),
		'entry-content' => __( 'Entry Content', CHILD_THEME_SLUG ),
		'after-entry-content' => __( 'After Entry Content', CHILD_THEME_SLUG ),
		'after-loop' => __( 'After Loop', CHILD_THEME_SLUG ),
		'after-content' => __( 'After Content', CHILD_THEME_SLUG ),
		'before-content-sidebar-wrap' => __( 'Before Content Sidebar Wrap', CHILD_THEME_SLUG ),
		'after-content-sidebar-wrap' => __( 'After Content Sidebar Wrap', CHILD_THEME_SLUG ),
	);
	// Use the current filter to define the widget area
	$widget_name = current_filter();
	// Replace genesis string
	$widget_name = str_ireplace('genesis_', '', $widget_name );
	// Replace gsp string
	$widget_name = str_ireplace('gsp_', '', $widget_name );
	// Replace underscores with dashes
	$widget_name = str_ireplace('_', '-', $widget_name );
	// Construct initial widget area args
	$widget_args = array(
		'before' => '',
		'after'  => '',
		'show_inactive' => true,
	);
	// Add a filter to add additional classes for every widget
	add_filter( "genesis_attr_{$widget_name}-widgets", 'gsp_additional_widgets_classes' );
	// Open markup
	genesis_markup( array( 'open' => '<aside %s>', 'context' => "{$widget_name}-widgets" ) );
	// Do wrapper
	genesis_markup( array( 'open' => '<div %s>', 'context' => "{$widget_name}-widgets-inner" ) );
	// Do structural wrap
	genesis_structural_wrap( "{$widget_name}-widgets" );
	// Do before content
	do_action( "gsp_before_{$widget_name}_widgets" );
	// Do content
	genesis_widget_area( $widget_name, $widget_args );
	// Do before content
	do_action( "gsp_after_{$widget_name}_widgets" );
	// Close Structural Wrap
	genesis_structural_wrap( "{$widget_name}-widgets", 'close' );
	// Close wrapper
	genesis_markup( array( 'close' => '</div>', 'context' => "{$widget_name}-widgets-inner" ) );
	// Closed markup
	genesis_markup( array( 'close' => '</aside>', 'context' => "{$widget_name}-widgets" ) );
}

function gsp_additional_widgets_classes( $attr ) {
	$attr['class'] .= ' widget-area';
	return $attr;
}

function gsp_debug_default_widgets( $defaults, $id ) {

	// Bail if sidebar has content
	if( is_active_sidebar( $id ) ) {
		return $defaults;
	}
	// Get settings
	$settings = gsp_get_config( 'settings' );
	// Bail if not in dev mode
	if( !isset( $settings['dev_mode'] ) || $settings['dev_mode'] !== true ) {
		return $defaults;
	}
	// Open the output buffer to add default content
	ob_start();
	// Genesis core function to output default content
	genesis_default_widget_area_content( $id . __( ' Widget Area', CHILD_THEME_SLUG ) );
	// Set default
	$defaults['default'] = ob_get_clean();

	return $defaults;
}
add_filter( 'genesis_widget_area_defaults', 'gsp_debug_default_widgets', 10, 3 );


