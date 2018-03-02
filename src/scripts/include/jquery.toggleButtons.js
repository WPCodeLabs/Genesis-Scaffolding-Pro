(function ($) {
    'use strict';
    $.fn.toggleButton = function (options) {

        var defaults = {
            targets: false,
            animate: 'slideToggle',
            duration: 'fast',
            timeout: 2000,
        };

        options = $.extend({}, defaults, options);

        return $.map(this, function (el) {
            return new toggleButton($(el));
        });

        function toggleButton($button) {
            var $targets;
            /**
            * Cache all DOM elements to their respective variables
            * Sets $toggles to map of all elements with data-toggle="[name]" specified on toggle button
            * @return  blank return, to maintain code consistancy
            */
            var _cache = function () {
                // Get which element we are going to search for
                var targets = options.targets || $button.data('toggles') || false;
                // Get the element(s) as objects
                $targets = $.map($(targets), function (el) {
                    return new togglable($(el), $button);
                });
            };

            var _toggle = function (event) {
                // Make sure to remove any default behavior from button/link that is clicked
                event.preventDefault();
                // Blur the button
                $button.blur();
                // Toggle the class
                // Loop through each target and do toggle action
                for (var i = 0; i < $targets.length; i++) {
                    $targets[i]._doToggle();
                }
            };

            (function () {
                _cache();
                if ($targets.length) {
                    $button.on('click', _toggle);
                }
            })();
        }

        function togglable($el, $button) {
            var transitionEndTrigger, classes;
            /**
             * Animate toggle
             * Performs jquery animations of toggle, if any are specified
             * Attached to el to allow chained events / functions
             * @param  (string) action : String indicator of which type of toggle we're doing (show/hide)
             * @return (object) $el : jQuery object, returned so functions can be chained
             */
            $el.doToggle = function () {
                if (options.animate !== 'transition') {
                    $el[options.animate](options.duration, function () {
                        $el.trigger('transitionend');
                    });
                }
                return $el;
            };

            $el._doToggle = function () {
                var completed = false;
                // Index of the classes we want to add
                var state = $el.hasClass(classes[0]) || $el.hasClass(classes[1]) ? 2 : 0;
                // Trigger start event for other functions to bind to
                $el.trigger('toggle:start');
                // Add class to button
                $button.removeClass(classes.join(' ')).addClass(classes[state]);
                // Do toggle
                $el.finish().removeClass(classes.join(' ')).addClass(classes[state]).doToggle().one(transitionEndTrigger, function () {
                    _completeTransition(state);
                    // Set completion trigger
                    completed = true;
                });
                // Set timeout to remove hanging transitions
                setTimeout(function () {
                    if (completed !== true) {
                        _completeTransition(state);
                    }
                }, options.timeout);
            };

            var _completeTransition = function (state) {
                // Remove button class
                $button.removeClass(classes.join(' ')).addClass(classes[state + 1]);
                // Remove all the other classes
                $el.removeClass(classes.join(' ')).addClass(classes[state + 1]);
                // Trigger end event for other functions to bind to
                $el.trigger( 'toggle:end' );
            };

            (function () {
                transitionEndTrigger = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend transitionend';
                classes = ['activating', 'activated', 'deactivating', 'deactivated'];
                // Stop inaccuratly ending transition on children
                $el.children().on( transitionEndTrigger, function (event) {
                    event.stopPropagation();
                });
            })();

            return $el;
        }
    };

})(jQuery);