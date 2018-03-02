<?php

/**
 * Include the kirki framework to create fields
 */
if( !class_exists( 'Kirki' ) ) {
    require CHILD_THEME_ROOT_DIR . 'lib/vendors/kirki/kirki.php';
}


function gsp_init_customizer() {
	/**
	 * Create Kirki config
	 * @see http://aristath.github.io/kirki/docs/config
	 */
	Kirki::add_config( CHILD_THEME_SLUG, array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	) );
}
add_action( 'customize_register', 'gsp_init_customizer' );

/**
 * Add additional header image controls
 */
function gsp_header_controls() {
    Kirki::add_field( CHILD_THEME_SLUG, array(
        'type'        => 'radio-buttonset',
        'settings'    => sprintf( '%s[header_bg][background-repeat]', CHILD_THEME_SLUG ),
        'label'       => __( 'Header Background Repeat', CHILD_THEME_SLUG ),
        'section'     => 'header_image',
        'default'     => '',
        'priority'    => 10,
        'choices'     => array(
            'no-repeat'   => esc_attr__( 'None', CHILD_THEME_SLUG ),
            'repeat' => esc_attr__( 'Tile', CHILD_THEME_SLUG ),
            'repeat-x'  => esc_attr__( 'X', CHILD_THEME_SLUG ),
            'repeat-y'  => esc_attr__( 'Y', CHILD_THEME_SLUG ),
        ),
    ) );
    Kirki::add_field( CHILD_THEME_SLUG, array(
        'type'        => 'radio-buttonset',
        'settings'    => sprintf( '%s[header_bg][background-size]', CHILD_THEME_SLUG ),
        'label'       => __( 'Header Background Size', CHILD_THEME_SLUG ),
        'section'     => 'header_image',
        'default'     => '',
        'priority'    => 10,
        'choices'     => array(
            'auto'     => esc_attr__( 'Auto', CHILD_THEME_SLUG ),
            'cover'    => esc_attr__( 'Cover', CHILD_THEME_SLUG ),
            'contain'  => esc_attr__( 'contain', CHILD_THEME_SLUG ),
        ),
    ) );
    Kirki::add_field( CHILD_THEME_SLUG, array(
        'type'        => 'radio-buttonset',
        'settings'    => sprintf( '%s[header_bg][background-attachment]', CHILD_THEME_SLUG ),
        'label'       => __( 'Header Background Attachment', CHILD_THEME_SLUG ),
        'section'     => 'header_image',
        'default'     => '',
        'priority'    => 10,
        'choices'     => array(
            ''       => esc_attr__( 'None', CHILD_THEME_SLUG ),
            'scroll' => esc_attr__( 'Scroll', CHILD_THEME_SLUG ),
            'fixed'  => esc_attr__( 'Fixed', CHILD_THEME_SLUG ),
        ),
    ) );
    Kirki::add_field( CHILD_THEME_SLUG, array(
        'type'        => 'dimensions',
        'settings'    => sprintf( '%s[header_bg][background-position]', CHILD_THEME_SLUG ),
        'label'       => __( 'Header Background position', CHILD_THEME_SLUG ),
        'section'     => 'header_image',
        'default'     => array(
			'x-position' => '50%',
			'y-position' => '50%',
		),
    ) );
    Kirki::add_field( CHILD_THEME_SLUG, array(
    	'type'        => 'checkbox',
    	'settings'    => sprintf( '%s[header_bg][use_featured]', CHILD_THEME_SLUG ),
    	'label'       => esc_attr__( 'Use Featured Image', CHILD_THEME_SLUG ),
    	'description' => esc_attr__( 'Replace header image with featured image on single posts and pages', CHILD_THEME_SLUG ),
    	'section'     => 'header_image',
    	'default'     => false,
    ) );
    Kirki::add_field( CHILD_THEME_SLUG, array(
        'type'        => 'radio-buttonset',
        'settings'    => sprintf( '%s[logo_alignment]', CHILD_THEME_SLUG ),
        'label'       => __( 'Logo Alignment', CHILD_THEME_SLUG ),
        'section'     => 'title_tagline',
        'default'     => '',
        'priority'    => 10,
        'choices'     => array(
            'left'     => esc_attr__( 'Left', CHILD_THEME_SLUG ),
            'center'    => esc_attr__( 'Center', CHILD_THEME_SLUG ),
            'right'  => esc_attr__( 'right', CHILD_THEME_SLUG ),
        ),
    ) );
    Kirki::add_field( CHILD_THEME_SLUG, array(
    	'type'        => 'image',
    	'settings'    => sprintf( '%s[featured_image]', CHILD_THEME_SLUG ),
    	'label'       => esc_attr__( 'Featured Image', CHILD_THEME_SLUG ),
    	'description' => esc_attr__( 'Default featured image to use if none is set', CHILD_THEME_SLUG ),
    	'section'     => 'title_tagline',
    	'default'     => '',
    	'choices'     => array(
    		'save_as' => 'id',
    	),
    ) );
}
add_action( 'init', 'gsp_header_controls' );

function gsp_footer_controls() {
	// Add jumbotron section
	Kirki::add_section( sprintf( '%s_footer', CHILD_THEME_SLUG ), array(
	    'title'          => esc_attr__( 'Footer', CHILD_THEME_SLUG ),
	    'priority'       => 160,
	) );
	Kirki::add_field( CHILD_THEME_SLUG, array(
		'type'     => 'editor',
		'settings' => sprintf( '%s[footer_creds]', CHILD_THEME_SLUG ),
		'label'    => __( 'Footer Credits', CHILD_THEME_SLUG ),
		'section'  => sprintf( '%s_footer', CHILD_THEME_SLUG ),
		'default'  => '[footer_copyright before="Copyright "] &middot; [footer_childtheme_link before="" after=" on"] [footer_genesis_link url="http://www.studiopress.com/" before=""] &middot; [footer_wordpress_link] &middot; [footer_loginout]',
		'priority' => 10,
	) );
	Kirki::add_field( CHILD_THEME_SLUG, array(
		'type'        => 'slider',
		'settings'    => sprintf( '%s[footer_widgets]', CHILD_THEME_SLUG ),
		'label'       => esc_attr__( 'Number of Footer Widgets', CHILD_THEME_SLUG ),
		'section'     => sprintf( '%s_footer', CHILD_THEME_SLUG ),
		'default'     => 3,
		'choices'     => array(
			'min'  => '0',
			'max'  => '10',
			'step' => '1',
		),
	) );
}
add_action( 'init', 'gsp_footer_controls' );

function gsp_archive_controls() {
    $layout_choices = array(
        ''  => esc_attr__( 'Site Default', CHILD_THEME_SLUG ),
    );
    // Add additional archive layout options
    $layouts =  genesis_get_layouts_for_customizer();
    // Add to choices
    foreach( $layouts as $layout_slug => $layout ) {
        $layout_choices[$layout_slug] = $layout;
    }
    Kirki::add_field( CHILD_THEME_SLUG, array(
        'type'        => 'select',
        'settings'    => sprintf( '%s[archive_layout]', CHILD_THEME_SLUG ),
        'label'       => __( 'Content Archive Page Layout', CHILD_THEME_SLUG ),
        'section'     => 'genesis_archives',
        'default'     => '',
        'priority'    => 10,
        'multiple'    => false,
        'choices'     => $layout_choices
    ) );
}
add_action( 'init', 'gsp_archive_controls' );

function gsp_jumbotron_controls() {
    // Add jumbotron section
    Kirki::add_section( sprintf( '%s_jumbotron', CHILD_THEME_SLUG ), array(
        'title'          => esc_attr__( 'Jumbotron', CHILD_THEME_SLUG ),
        'priority'       => 160,
    ) );
    Kirki::add_field( CHILD_THEME_SLUG, array(
        'type'        => 'toggle',
        'settings'    => sprintf( '%s[jumbotron_bg_use_header]', CHILD_THEME_SLUG ),
        'label'       => esc_attr__( 'Use Header Image Settings', CHILD_THEME_SLUG ),
        'description' => esc_attr__( 'If this is enabled, it will use the settings defined in "Header Image"', CHILD_THEME_SLUG ),
        'section'     => sprintf( '%s_jumbotron', CHILD_THEME_SLUG ),
        'default'     => false,
        'priority'    => 10,
    ) );
    Kirki::add_field( CHILD_THEME_SLUG, array(
        'type'        => 'background',
        'settings'    => sprintf( '%s[jumbotron_bg]', CHILD_THEME_SLUG ),
        'label'       => esc_attr__( 'Jumbotron Section Background', CHILD_THEME_SLUG ),
        'section'     => sprintf( '%s_jumbotron', CHILD_THEME_SLUG ),
        'default'     => array(
            'background-color'      => 'rgba(20,20,20,.8)',
            'background-image'      => '',
            'background-repeat'     => 'repeat',
            'background-position'   => 'center center',
            'background-size'       => 'cover',
            'background-attachment' => 'scroll',
        ),
        'required' => array(
            array(
                'setting'  => sprintf( '%s[jumbotron_bg_use_header]', CHILD_THEME_SLUG ),
                'value'    => false,
                'operator' => '=='
            ),
        ),
    ) );
}
add_action( 'init', 'gsp_jumbotron_controls' );

function gsp_layout_controls() {
	Kirki::add_field( CHILD_THEME_SLUG, array(
		'type'        => 'select',
		'settings'    => sprintf( '%s[subthemes]', CHILD_THEME_SLUG ),
		'label'       => esc_attr__( 'Preset Theme', CHILD_THEME_SLUG ),
		'section'     => 'genesis_layout',
		'default'     => '',
		'choices'     => array(
			'' => esc_attr__( 'Default', CHILD_THEME_SLUG ),
			'modern' => esc_attr__( 'Modern', CHILD_THEME_SLUG ),
			'clean' => esc_attr__( 'Clean', CHILD_THEME_SLUG ),
		),
		'active_callback' => 'gsp_is_dev_mode',
	) );
}
add_action( 'init', 'gsp_layout_controls' );

function gsp_is_dev_mode() {
	$settings = gsp_get_config( 'settings' );
	return $settings['dev_mode'];
}