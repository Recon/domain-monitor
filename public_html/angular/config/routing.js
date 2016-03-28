(function (angular, app, $) {
    'use strict';

    app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
        $locationProvider.html5Mode(false);

        $routeProvider
            .when('/overview', {
                templateUrl: '/templates/overview.html'
            })
            .when('/domain-settings/new', {
                templateUrl: '/templates/domain-settings.html'
            })
            .when('/domain-settings/:id', {
                templateUrl: '/templates/domain-settings.html'
            })
            .when('/user/edit/:id', {
                templateUrl: '/templates/users/user-edit.html'
            })
            .when('/user/new', {
                templateUrl: '/templates/users/user-edit.html'
            })
            .when('/users', {
                templateUrl: '/templates/users/user-list.html'
            })
            .when('/profile', {
                templateUrl: '/templates/profile/profile.html'
            })
            .when('/login', {
                templateUrl: '/templates/login.html'
            })
            .when('/reset-password/:token', {
                templateUrl: '/templates/reset-password.html'
            })
            .when('/', {
                templateUrl: '/templates/index.html',
                controller: 'index'
            })
            .otherwise({
                redirectTo: '/'
            });
    }]);
})(angular, window.app, window.jQuery);
