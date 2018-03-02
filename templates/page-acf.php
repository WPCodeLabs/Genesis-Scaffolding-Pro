<?php

/**
 *
 * Template Name: Advanced Custom Fields
 *
 */

if( class_exists( 'acf' ) && is_main_query() ) {
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
	add_action( 'genesis_entry_content', 'acf_do_post_content' );
}

function acf_do_post_content() {

	// Re-add our default action back in, so other things can use it
	add_action( 'genesis_entry_content', 'genesis_do_post_content' );
	// Remove this action for nested loops
	remove_action( 'genesis_entry_content', 'acf_do_post_content' );
	// Continue with our content...
	if( have_rows('page_section') ) : while ( have_rows('page_section') ) : the_row();
		// Open the section markup
		genesis_markup( array( 'open' => '<section %s>', 'context' => 'acf_page_section' ) );
			// Do the section header
			if( get_sub_field('display_title') ) :
				genesis_markup( array(
					'open' => '<header %s>',
					'close' => '</header>',
					'context' => 'acf_page_section_header',
					'content' => sprintf( '<h2 class="section-title">%s</h2>', get_sub_field('section_title') ),
				) );
			endif;
			// Open the inner markup
			genesis_markup( array( 'open' => '<div %s>', 'context' => 'acf_page_section_inner', 'content' => '<div class="row">' ) );
			// Loop through individual elements
			if( have_rows('section_elements') ) : while ( have_rows('section_elements') ) : the_row();
				// Open the column
				genesis_markup( array( 'open' => '<div %s>', 'context' => 'acf_column' ) );
					// Output each individual type of layout
					do_action( sprintf( 'acf_do_layout_%s', get_row_layout() ) );
				// Close the column
				genesis_markup( array( 'close' => '</div>', 'context' => 'acf_column' ) );
			endwhile; endif;
			// Close the inner markup
			genesis_markup( array( 'close' => '</div>', 'context' => 'acf_page_section_inner', 'content' => '</div>' ) );
		// Close the section markup
		genesis_markup( array( 'close' => '</section>', 'context' => 'acf_page_section' ) );

	endwhile; endif;
}

/**
 * Output function for standard content fields
 */
function acf_do_layout_standard_content() {
	echo apply_filters( 'the_content', get_sub_field( 'content' ) );
}
add_action( 'acf_do_layout_standard_content', 'acf_do_layout_standard_content' );

/**
 * Output function for single line fields
 */
function acf_do_layout_single_line_text() {
	echo apply_filters( 'the_content', get_sub_field( 'content' ) );
}
add_action( 'acf_do_layout_single_line_text', 'acf_do_layout_single_line_text' );

/**
 * Output function for standard content fields
 */
function acf_do_layout_gallery() {
	// Get image ID's
	$image_ids = get_sub_field( 'content', false, false );
	// Construct shortcode
	$shortcode = '[gallery ids="' . implode(',', $image_ids) . '"]';
	// Do Shortcode
	echo do_shortcode( $shortcode );
}
add_action( 'acf_do_layout_gallery', 'acf_do_layout_gallery' );

/**
 * Output function for content card fields
 */
function acf_do_layout_content_card() {
	genesis_markup( array( 'open' => '<div %s>', 'close' => '</div>', 'context' => 'content_card', 'content' => get_sub_field( 'content' ) ) );
}
add_action( 'acf_do_layout_content_card', 'acf_do_layout_content_card' );

/**
 * Add column classes to columns
 */
function acf_column_attr( $attr ) {
	$attr['class'] .= ' ' . get_sub_field( 'layout' );
	$attr['class'] .= ' ' . get_sub_field( 'class' );
	$attr['class']  = trim( $attr['class'] );
	// Conditional styling
	return $attr;
}
add_filter( 'genesis_attr_acf_column', 'acf_column_attr' );

/**
 * Add attributes to page sections and content cards
 */
function acf_page_section_attr( $attr ) {
	// Set the ID
	$attr['id'] = get_sub_field('id');
	// Set the classes
	$attr['class'] .= ' ' . get_sub_field( 'class' );
	$attr['class']  = trim( $attr['class'] );
	// Return
	return $attr;
}
add_filter( 'genesis_attr_acf_page_section', 'acf_page_section_attr' );

function acf_add_background_attr( $attr ) {
	// Set the style
	$attr['style']  = isset( $attr['style'] ) ? $attr['style'] : '';
	// Append background color
	$attr['style'] .= !empty( get_sub_field('background_color') ) ? ' background-color: ' . get_sub_field('background_color') . ';' : '';
	// Special handling for image object
	$image = get_sub_field('background_image');
	$attr['style'] .= !empty( $image ) ? ' background-image: url(' . esc_url_raw( $image['url'] ) . ');' : '';
	// Return
	return $attr;
}
add_filter( 'genesis_attr_acf_page_section', 'acf_add_background_attr' );
add_filter( 'genesis_attr_content_card', 'acf_add_background_attr' );
add_filter( 'genesis_attr_image_link_background', 'acf_add_background_attr' );


function acf_page_section_inner_attr( $attr ) {
	$attr['class'] .= ' wrap';
	return $attr;
}
add_filter( 'genesis_attr_acf_page_section_inner', 'acf_page_section_inner_attr' );
add_filter( 'genesis_attr_acf_page_section_header', 'acf_page_section_inner_attr' );

/**
 * Run genesis
 */
genesis();