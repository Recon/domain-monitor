(function (angular, app, csrfToken) {
    'use strict';

    app.config(function ($httpProvider) {
        // Set default headers
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
        $httpProvider.defaults.headers.common['X-Angular'] = 'true';
        $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    });
})(angular, window.app, window.token);
