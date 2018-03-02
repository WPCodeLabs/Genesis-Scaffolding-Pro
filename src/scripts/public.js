jQuery(function ($) {
    'use strict';

    /**
     * Set up wordpress galleries
     */
    $('[class*="gallery-columns"]' ).WPGallery();

    /**
     * Set up our responsive navigation
     */
    ( function() {
    	var $primary_nav, $secondary_nav, types = [ 'dropdown', 'offcanvas', 'overlay' ];
    	// Get our primary navigation
    	$primary_nav = $('.nav-primary');
    	// Get our secondary navigation
    	$secondary_nav = $('.nav-secondary');

    	/**
    	 * Helper function to set up each navigation type
    	 * @param  {object} $nav jQuery object of the nav
    	 * @return { object} $nav jQuery object of the nav
    	 */
    	var setupNav = function( $nav, $combine ) {

    		if( $nav.length === 0 ) {
    			return false;
    		}
    		// Set the type
    		$nav.type = $nav.data( 'menu-type' );
    		// Make sure it's a responsive type
    		if( types.indexOf( $nav.type ) !== -1 ) {
    			$nav.GSPMenu( {
    				'type': $nav.type,
    				'animate': $nav.type === 'dropdown' ? 'slideToggle' : 'transition',
    				'button': false, // Can be passed a button selector if you want to place the button manually
    				'combine': $combine.data( 'menu-type' ) === 'combined' ? $combine : false,
    				'buttonPosition': $nav.type !== 'dropdown' ? 'both' : 'outside',
    			} );
    		}
    		return $nav;
    	};
    	// Do the primary nav setup
    	setupNav( $primary_nav, $secondary_nav );
    	// Do the secondary nav setup
    	setupNav( $secondary_nav, $primary_nav );
    })();

    /**
     * Set up our archive grids
     */
    (function(){
    	var $wrapper, $masonry;
    	// Add element query classes to archive wrapper
    	$wrapper = $('.archive-while-wrap').ElementQuery({
    	    breakpoints: [400, 600, 800, 1000],
    	    classes: ['sm', 'md', 'lg', 'xl'],
    	});
    	// Create masonry grid
    	$masonry = $('.masonry-archive').masonry({
    	    columnWidth: '.masonry-entry-column',
    	    itemSelector: '.masonry-entry-column',
    	    percentPosition: true,
    	});
    })();

});