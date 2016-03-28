(function (angular, app, $) {
    'use strict';

    app.factory("CustomServerErrorHttpInterceptor", function ($q) {
        return {
            response: function (response) {
                if (response.data.status && (response.data.status === 400)) {
                    return $q.reject(response);
                }
                return response || $q.when(response);
            }
        };
    });

    app.config(function ($httpProvider) {
        $httpProvider.interceptors.push("CustomServerErrorHttpInterceptor");
    });
})(angular, window.app, window.jQuery);
