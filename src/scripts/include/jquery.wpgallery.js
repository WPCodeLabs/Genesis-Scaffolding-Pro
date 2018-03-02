(function ($) {
    'use strict';
    $.fn.WPGallery = function (options) {

        var defaults = {
            breakpoints: [400, 600, 800, 1000],
            classes: ['sm', 'md', 'lg', 'xl'],
        };

        var $window = $(window);

        options = $.extend({}, defaults, options);

        return $.map(this, function (el) {
            return new Gallery($(el));
        });

        function Gallery($el) {
            var columns, $item;

            var _init = function() {
                columns = _getCols();
                $item = $el.find( '.gallery-item' ).first();
                if( columns !== false && $item.length !== 0 ) {
                    $el.css( { 'max-width' : ( $item.outerWidth() * columns ) + 'px' } );
                }
            };

            var _getCols = function() {
                var columns = false;
                for( var i = 1; i <= 10; i++ ) {
                    if( $el.hasClass( 'gallery-columns-' + i ) ) {
                        columns = i;
                        break;
                    }

                }
                return columns;
            };
            (function () {
                // Initial action on load
                _init();
            })();
            return $el;
        }
    };

})(jQuery);