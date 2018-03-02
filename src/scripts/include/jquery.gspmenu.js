(function ($) {
    'use strict';
    $.fn.GSPMenu = function (options) {

        var defaults = {
            buttonText : 'Menu',
            buttonClass: 'dashicons-before dashicons-menu',
            buttonId: false,
            buttonPosition: 'outside',
            targets: false,
            animate: 'slideToggle',
            button: false,
            type : 'dropdown',
            combine : false,
        };

        options = $.extend({}, defaults, options);

        return $.map(this, function (el) {
            return new responsiveMenu( $(el) );
        });

        function responsiveMenu( $el ) {

            /**
             * Instantiate our variables
             */
            var $superFish, $menuButton, $submenuButtons, $addon, $addonSubmenuButtons, combined = false;

            /**
             * Cache the vars for use in our functions, so we don't have to search for them again
             */
            var _cacheDom = function() {
                // Get our main menu
                $el.menu = $el.hasClass('.menu') ? $el : $el.find('.menu').first();
                // Our superfish class menu
                $superFish = $el.hasClass('.js-superfish') ? $el : $el.find('.js-superfish' );
                // The button that toggles the menu on mobile
                $menuButton = options.button === false ? _insertButton() : $( options.button );
                // Submenu toggle buttons
                $submenuButtons = $el.find( '.sub-menu-toggle' );
                // Search for the combination menu
                $addon = typeof options.combine === 'object' ? options.combine : $( options.combine );
                // Get the appended list of addon
                $addon.menu = $addon.hasClass('.menu') ? $addon : $addon.find('.menu').first();
                // Addon submenus
                $addonSubmenuButtons = $addon.find('.sub-menu-toggle');
            };

            /**
             * Insert a button if one wasn't specified
             */
            var _insertButton = function () {
                var buttonArgs = {
                    'class': 'menu-toggle ' + options.buttonClass,
                    'aria-expanded': false,
                    'aria-pressed': false,
                    'role': 'button',
                    'text': 'Menu',
                };
                // Conditionally set ID
                if( options.buttonId ) {
                    buttonArgs.id = options.buttonId;
                }
                // Create button object
                $menuButton = $( '<button />', buttonArgs );
                // Insert button before element
                if (options.buttonPosition === 'outside' || options.buttonPosition === 'both' ) {
                    $menuButton.insertBefore($el);
                }
                // Insert button inside element
                if (options.buttonPosition === 'inside' ) {
                    $menuButton.prependTo($el);
                }
                // Insert copy of button
                if (options.buttonPosition === 'both') {
                    $menuButton.cloned = $menuButton.clone().prependTo($el);
                }
                // Return the button
                return $menuButton;
            };

            /**
             * Addon any additional menu's that need to be combined
             */
            var _combine = function ( combine ) {
                // Make sure we know whether to enable or disable
                if (combine === 'undefined') {
                    combine = $menuButton.is(':visible');
                }
                // Bail if we have no menu to combine
                if( $addon.length === 0) {
                    return false;
                }
                // Bail if we are supposed to combine, but already have...
                if( combine && combined === true ) {
                    return false;
                }
                // Combine menu's for mobile
                if( combine && combined === false ) {
                    $el.menu.append($addon.menu.children('li').clone().addClass('moved-item') );
                    combined = true;
                }
                // Else remove on desktop
                else if( combined === true ){
                    $el.find('.moved-item').remove();
                    combined = false;
                }
            };

            /**
             * Orchastrate actual execution
             */
            var _init = function() {
                // Add class to our menu, so we can target it with css
                $el.addClass(options.type);
                // Add class to button, so we can target it with css (if necessary)
                $menuButton.addClass(options.type);
                // instantiate Toggle Buttons
                $menuButton.toggleButton({ 'targets': $el, 'animate': options.animate });
                // instantiate a clone of the button
                if ($menuButton.cloned !== undefined ) {
                    // Add class to button, so we can target it with css (if necessary)
                    $menuButton.cloned.addClass(options.type);
                    // instantiate Toggle Buttons
                    $menuButton.cloned.toggleButton({ 'targets': $el, 'animate': options.animate });
                }
                // instantiate submenu toggles
                for (var i = 0; i < $submenuButtons.length; i++) {
                    $($submenuButtons[i]).toggleButton({ 'targets': $($submenuButtons[i]).siblings('ul') });
                }
                // instantiate submenu toggles for addon menu
                for (i = 0; i < $addonSubmenuButtons.length; i++) {
                    // Create object
                    $addonSubmenuButtons[i] = $($addonSubmenuButtons[i]);
                    // Do toggle function
                    $($addonSubmenuButtons[i]).toggleButton({ 'targets': $($addonSubmenuButtons[i]).siblings('ul') });

                }
            };

            /**
             * Enable / disable superfish menu
             */
            var _superFish = function( enable ) {
                // Make sure we know whether to enable or disable
                if(enable === 'undefined') {
                    enable = $menuButton.is(':visible') ? false : true;
                }
                // Set initial args
                var $args = {
                    'delay': 100,
                    'animation': { 'opacity': 'show', 'height': 'show' },
                    'dropShadows': false,
                    'speed': 'fast'
                };
                // Make sure we have superfish enabled
                if ( $superFish.length === 0 || typeof $superFish.superfish !== 'function') {
                    return;
                }
                // Check if our button is enabled, and disable superfix
                if( enable === false ) {
                    $args = 'destroy';
                }
                // Do superfish
                $superFish.superfish( $args );
            };

            /**
             * Functions to run on resize
             */
            var _resize = function () {
                /**
                 * If on mobile size (menu button is visible)
                 * disable superfish
                 */
                if ( $menuButton.is(':visible') ) {
                    _superFish(false);
                    _combine( true );
                }
                /**
                 * If on desktop size (menu button not visible)
                 * Remove extra styles added by script
                 * Enable superfish
                 */
                else {
                    $el.attr('style', '');
                    _superFish(true);
                    _combine( false );
                }
            };

            var bindEvents = function() {
                // Bind the resize function of the window
                $( window ).on( 'resize', _resize );
                // $( document ).on( 'ready', _resize );
                // Open the menu
                // $menuButton.on( 'click', _toggleMenu );
            };



            ( function() {
                _cacheDom();
                _combine();
                bindEvents();
                _init();
                _resize();
            })();
        }
    };
}) (jQuery);

