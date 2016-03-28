(function (angular, app, $) {
    'use strict';

    app.filter('capitalize', function () {
        return function (input, scope) {
            return input.substring(0, 1).toUpperCase() + input.substring(1);
        }
    });
})(angular, window.app, window.jQuery);
