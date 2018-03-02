(function ($) {
    'use strict';
    $.fn.ElementQuery = function (options) {

        var defaults = {
            breakpoints: [400, 600, 800, 1000],
            classes: ['sm', 'md', 'lg', 'xl'],
        };

        var $window = $(window);

        options = $.extend({}, defaults, options);

        return $.map(this, function (el) {
            return new Element($(el));
        });

        function Element($el) {

            var addQueryClasses = function () {
                // console.log( $el.width() );
                for (var i = 0; i < options.breakpoints.length; i++) {
                    // Get numberic breakpoint
                    var breakpoint = parseInt(options.breakpoints[i]);
                    // Get current width
                    var currentWidth = $el.width();
                    // If it's bigger, do nothing
                    if (currentWidth < breakpoint) {
                        $el.removeClass(options.classes[i]);
                        continue;
                    } else {
                        $el.addClass(options.classes[i]);
                    }
                }
            };

            (function () {
                // Initial action on load
                addQueryClasses();
                $window.on('resize', addQueryClasses);
            })();
            return $el;
        }
    };

})(jQuery);