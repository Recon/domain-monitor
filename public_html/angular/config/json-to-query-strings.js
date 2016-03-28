(function (angular, app, $) {
    'use strict';

    app.config(function ($httpProvider) {
        // Converts json to query string on $http requests
        $httpProvider.defaults.transformRequest = function (data) {
            if (data === undefined) {
                return data;
            }

            return $.param(data);
        };
    });
})(angular, window.app, window.jQuery);
