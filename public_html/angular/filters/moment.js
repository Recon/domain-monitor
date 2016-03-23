(function (angular, $) {
    'use strict';
    window.app.filter('momentjs', function () {
        return function (input) {
            return moment(input).toDate();
        };
    });
    window.app.filter('moment_diff', function () {
        return function (input) {
            return moment(input).fromNow();
        };
    });
})(angular, window.jQuery);
