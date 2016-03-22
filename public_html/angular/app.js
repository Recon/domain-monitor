(function (angular, $, csrfToken) {
    'use strict';

    var app = angular.module('app', ['ngRoute', 'angular-loading-bar']);

    app.config(function ($httpProvider) {
        // Converts json to query string on $http requests
        $httpProvider.defaults.transformRequest = function (data) {
            if (data === undefined) {
                return data;
            }

            return $.param(data);
        };

        // Set default headers
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
        $httpProvider.defaults.headers.common['X-Angular'] = 'true';
        $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    });

    app.config(['cfpLoadingBarProvider', function (cfpLoadingBarProvider) {
            cfpLoadingBarProvider.includeSpinner = false;
            cfpLoadingBarProvider.includeBar = true;
        }]);

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



    app.run(function ($rootScope, $location, $timeout, userService) {
        $rootScope.$on('$routeChangeSuccess', function (event, next, current) {

            // Redirect if not logged in
//            var redirectToLoginIfRestricted = function () {
//                if (!userService.isAuthenticated() && [
//                    // Whitelist paths which can be accessed without logging in
//                    '/',
//                    '/user/recover',
//                    '/user/reset/:token'
//                ].indexOf(next.originalPath) == -1) {
//                    $location.path("/");
//                }
//            };
//            if (userService.isLoaded()) {
//                redirectToLoginIfRestricted();
//            } else {
//                var unregister = $rootScope.$on('user.info.update', function () {
//                    redirectToLoginIfRestricted();
//                    unregister();
//                });
//            }

            // Tracking - not used at the moment
            /*
             if (window._paq) {
             window._paq.push(['setCustomUrl', $location.path()]);
             window._paq.push(['trackPageView']);
             }*/


            $rootScope.$emit('ui-component-update');

            $timeout(function () {
                $('.viewport-min-height').css('min-height', ($('.site-view').height() - 65) + 'px');
            }, 100);
        });

        $rootScope.$on('ui-component-update', function () {
            $timeout(function () {
                $('[data-toggle="popover"]:not(".initialized")').addClass('initialized').popover();
                //$('[data-toggle=confirmation]:not(".initialized")').addClass('initialized').confirmation({popout: true});
            }, 100, false);
        });

    });

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

    app.filter('capitalize', function () {
        return function (input, scope) {
            return input.substring(0, 1).toUpperCase() + input.substring(1);
        }
    });

    app.filter('unsafe', function ($sce) {
        return $sce.trustAsHtml;
    });

    window.app = app;

})(window.angular, window.jQuery, window.token);