(function (angular, $) {
    'use strict';
    window.app.controller('login', ['$scope', '$rootScope', '$http', '$timeout', '$location', 'userService', function ($scope, $rootScope, $http, $timeout, $location, userService) {
            $scope.user = userService;

            $scope.username = '';
            $scope.password = '';

            $scope.authenticated = false;
            $scope.errorMessage = false;


            $scope.send = function () {
                $http.post('login', {
                    'username': $scope.username,
                    'password': $scope.password
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

        }]);
})(window.angular, window.jQuery);
