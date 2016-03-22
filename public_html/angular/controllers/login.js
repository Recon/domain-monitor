(function (angular, $) {
    'use strict';
    window.app.controller('login', ['$scope', '$rootScope', '$http', '$timeout', '$location', 'userService', function ($scope, $rootScope, $http, $timeout, $location, userService) {
            $scope.user = userService;

            $scope.data = {};
            $scope.data.username = '';
            $scope.data.password = '';

            $scope.authenticated = false;
            $scope.resetLinkSent = false;
            $scope.errorMessage = false;

            $scope.loginShown = true;
            $scope.resetPasswordShown = false;

            $scope.send = function () {
                $http.post('login', {
                    'username': $scope.data.username,
                    'password': $scope.data.password
                }).then(function (response) {
                    $scope.authenticated = true;
                    $scope.errorMessage = false;

                    $rootScope.$broadcast('user.login');

                    $timeout(function () {
                        $location.path('overview')
                    }, 1000);
                }, function (response) {
                    $scope.authenticated = false;
                    $scope.errorMessage = response.data.message;
                });
            };

            $scope.reset = function () {
                $http.post('request_reset', {
                    'username': $scope.data.username,
                }).then(function (response) {
                    $scope.resetLinkSent = true;
                    $scope.errorMessage = false;
                }, function (response) {
                    $scope.errorMessage = response.data.message;
                });
            };

            $scope.showReset = function () {
                $scope.loginShown = false;
                $scope.resetPasswordShown = true;
            };
        }]);
})(window.angular, window.jQuery);
