(function (angular, $) {
    'use strict';

    var pathWhitelist = [
        // Whitelist paths which can be accessed without logging in
        '/login',
        '/reset-password/:token'
    ]

    var app = angular.module('app', ['ngRoute', 'angular-loading-bar', 'am.multiselect']);

    app.run(function ($rootScope, $location, $timeout, userService) {

        var authLoaded = false;

        // Redirect if not logged in
        $rootScope.$on('$routeChangeSuccess', function (event, next) {
            if (!userService.info.is_authenticated && authLoaded && pathWhitelist.indexOf(next.originalPath) == -1) {
                $location.path("/login");
            }
        });
        $rootScope.$on('user.info.loaded', function (event) {
            authLoaded = true;
            if (!userService.info.is_authenticated && authLoaded && pathWhitelist.indexOf($location.path()) == -1) {
                $location.path("/login");
            }
        });

        $rootScope.$on('$routeChangeSuccess', function (event, next, current) {
            // Tracking - not used at the moment
            /*
             if (window._paq) {
             window._paq.push(['setCustomUrl', $location.path()]);
             window._paq.push(['trackPageView']);
             }*/

            $rootScope.$emit('ui-component-update');
        });

        $rootScope.$on('ui-component-update', function () {
            $timeout(function () {
                $('[data-toggle="popover"]:not(".initialized")').addClass('initialized').popover();
                //$('[data-toggle=confirmation]:not(".initialized")').addClass('initialized').confirmation({popout: true});
            }, 100, false);
        });

    });

    window.app = app;

})(window.angular, window.jQuery);
