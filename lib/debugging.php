<?php

$genesis_hooks = array(
    'genesis_before_comments',
    'genesis_comments',
    'genesis_after_comments',
    'genesis_before_pings',
    'genesis_pings',
    'genesis_after_pings',
    'genesis_before_comment_form',
    'genesis_comment_form',
    'genesis_after_comment_form',
    'genesis_before_footer',
    'genesis_footer',
    'genesis_after_footer',
    'genesis_after',
    'genesis_doctype',
    'genesis_title',
    'genesis_meta',
    'genesis_before',
    'genesis_before_header',
    'genesis_header',
    'genesis_after_header',
    'genesis_before_sidebar_alt_widget_area',
    'genesis_sidebar_alt',
    'genesis_after_sidebar_alt_widget_area',
    'genesis_before_sidebar_widget_area',
    'genesis_sidebar',
    'genesis_after_sidebar_widget_area',
    'genesis_before_content_sidebar_wrap',
    'genesis_before_content',
    'genesis_before_loop',
    'genesis_loop',
    'genesis_after_loop',
    'genesis_after_content',
    'genesis_after_content_sidebar_wrap',
    'genesis_pre',
    'genesis_pre_framework',
    'genesis_init',
    'genesis_setup',
    'genesis_archive_title_descriptions',
    'genesis_list_comments',
    'genesis_list_pings',
    'genesis_before_comment',
    'genesis_after_comment',
    'genesis_before_comment',
    'genesis_after_comment',
    'genesis_site_title',
    'genesis_site_description',
    'genesis_header_right',
    'genesis_before_while',
    'genesis_before_entry',
    'genesis_entry_header',
    'genesis_before_entry_content',
    'genesis_entry_content',
    'genesis_after_entry_content',
    'genesis_entry_footer',
    'genesis_after_entry',
    'genesis_after_endwhile',
    'genesis_loop_else',
    'genesis_before_post',
    'genesis_before_post_title',
    'genesis_post_title',
    'genesis_after_post_title',
    'genesis_before_post_content',
    'genesis_post_content',
    'genesis_after_post_content',
    'genesis_after_post',
    'genesis_after_endwhile',
    'genesis_loop_else',
    'genesis_reset_loops',
);
foreach( $genesis_hooks as $hook ) {
    add_action( $hook, 'gsp_open_display_hooks', 1 );
    add_action( $hook, 'gsp_close_display_hooks', 999999999 );
}

function gsp_open_display_hooks() {
    genesis_markup( array( 'open' => '<div %s>', 'context' => 'action-hook-display', 'content' => '<span class="action-hook-display-label">' . current_filter() . '</span>' ) );
}

function gsp_close_display_hooks() {
    genesis_markup( array( 'close' => '</div>', 'context' => 'action-hook-display' ) );
}

function gsp_display_hooks_styles() {
    $output = '<style id="gsp_layout_debugging">';
    $output .= '.action-hook-display { border: 1px dotted #d9d9d9; padding: 10px; margin-bottom: 10px; position: relative; }';
    $output .= 'span.action-hook-display-label {opacity: .65; background: rgba(128, 128, 128, 0.33); display: block; color: #727272; padding: 10px; margin: -10px -10px 10px;}';
    echo $output;
    
}
add_action( 'wp_head', 'gsp_display_hooks_styles' );