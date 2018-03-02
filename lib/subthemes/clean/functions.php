<?php

if( !function_exists( 'gsp_clean_site_container_attr' ) ) {
	function gsp_clean_site_container_attr( $attr ) {
		$attr['class'] .= ' scrolltoggle';
		$attr['data-trigger-offset'] = '-30';
		return $attr;
	}
	add_filter( 'genesis_attr_site-container', 'gsp_clean_site_container_attr' );
}
