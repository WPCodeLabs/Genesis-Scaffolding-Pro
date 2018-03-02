<?php

/**
 * Alter footer credits
 * @param  string $credits : The default footer credis
 * @return string $custom_credits : The alternate credits
 */
function gsp_footer_credits( $credits ) {
    $custom_creds = gsp_theme_mod( 'footer_creds', '' );
    if( empty( $custom_creds ) ) {
        return $credits;
    }
    return $custom_creds;
}
add_filter( 'genesis_footer_creds_text', 'gsp_footer_credits' );

/**
 * Wrap footer widgets in row class
 *
 * @param  string $output : $output of structural wrap
 * @param  string $context : open / closing wrap
 * @return string : Output with wrap appended;
 */
function gsp_wrap_footer_widgets( $output, $context ) {
	if( $context === 'open' ) {
		return $output . genesis_markup( array( 'open' => '<div %s>', 'context' => 'footer-widget-wrap', 'echo' => false ) );
	} else {
		// Else if close
		return genesis_markup( array( 'close' => '</div>', 'context' => 'footer-widget-wrap', 'echo' => false ) ) . $output;
	}
}
add_filter( 'genesis_structural_wrap-footer-widgets', 'gsp_wrap_footer_widgets', 10, 2 );

function gsp_attr_footer_widget_wrap( $attr ) {
	$attr['class'] .= ' row flexrow';
	return $attr;
}
add_filter( 'genesis_attr_footer-widget-wrap', 'gsp_attr_footer_widget_wrap' );