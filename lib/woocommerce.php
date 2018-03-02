<?php

//remove generator meta tag
remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

// Add WooCommerce support for Genesis layouts, Genesis SEO, and Genesis Scripts
add_post_type_support( 'product', array( 'genesis-layouts', 'genesis-seo', 'genesis-scripts' ) );

// Unhook WooCommerce Sidebar - use Genesis Sidebars instead
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Unhook WooCommerce wrappers
// remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
// remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Add Woocommerce theme supports
 *
 * @see https://docs.woocommerce.com/document/declare-woocommerce-support-in-third-party-theme/
 * @see https://woocommerce.wordpress.com/2017/02/28/adding-support-for-woocommerce-2-7s-new-gallery-feature-to-your-theme/
 */
function gsp_declare_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'gsp_declare_woocommerce_support' );


/**
 * Set woocommerce template path
 *
 * Tell woocommerce where our main woocommerce file is located
 * default is theme/woocommerce.php. We've moved it to theme/templates/woocommerce.php
 * @see http://hookr.io/filters/woocommerce_template_loader_files/
 */

function set_woocommerce_template_path( $search_paths ) {
	$search_paths[] = 'templates/woocommerce.php';
	return $search_paths;
}
add_filter( 'woocommerce_template_loader_files', 'set_woocommerce_template_path' );

/**
 * Optimize WooCommerce Scripts
 * Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
 */
function gsp_manage_woocommerce_styles() {
	//first check that woo exists to prevent fatal errors
	if ( function_exists( 'is_woocommerce' ) ) {
		//dequeue scripts and styles
		if ( !is_woocommerce() && !is_cart() && !is_checkout() ) {
            // add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' ); // Easy, but only does styles...havne't found equiv for scripts yet

            ## Dequeue WooCommerce styles
			wp_dequeue_style('woocommerce-layout');
			wp_dequeue_style('woocommerce-general');
			wp_dequeue_style('woocommerce-smallscreen');

			## Dequeue WooCommerce scripts
			wp_dequeue_script('wc-cart-fragments');
			wp_dequeue_script('woocommerce');
			wp_dequeue_script('wc-add-to-cart');

		}
	}
}
add_action( 'wp_enqueue_scripts', 'gsp_manage_woocommerce_styles' );