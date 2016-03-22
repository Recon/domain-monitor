(function (angular, $) {
    'use strict';
    window.app.controller('passwordReset', ['$scope', '$rootScope', '$http', '$timeout', '$location', '$routeParams', 'userService',
        function ($scope, $rootScope, $http, $timeout, $location, $routeParams, userService) {
            $scope.user = userService;

            $scope.password = '';
            $scope.password2 = '';

            $scope.done = false;
            $scope.errorMessage = false;

            $scope.send = function () {
                $http.post('perform_reset', {
                    'password': $scope.password,
                    'password2': $scope.password2,
                    'token': $routeParams.token
                }).then(function (response) {
                    $scope.done = true;
                    $scope.errorMessage = false;

                    $timeout(function () {
                        $location.path('login')
                    }, 1000);
                }, function (response) {
                    $scope.done = false;
                    $scope.errorMessage = response.data.message;
                });
            };

            $scope.showReset = function () {
                $scope.loginShown = false;
                $scope.resetPasswordShown = true;
            };
        }]);
})(window.angular, window.jQuery);
