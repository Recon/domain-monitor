(function (angular, $) {
    'use strict';
    window.app.controller('userManager', ['$scope', '$http', '$filter', 'userService', function ($scope, $http, $filter, userService) {

            var self = this;

            $scope.user = userService;
            $scope.users = [];

            $scope.messages = {
                removed: false,
                errors: []
            };

            this.init = function () {
                $http.get('account/users').then(function (response) {
                    $scope.users = response.data;
                });
            };

            $scope.delete = function (user) {
                $http.post('user/delete', user).then(function (response) {
                    if (response.data.success === true) {
                        $scope.messages['removed'] = true;
                        $scope.users = $filter('filter')($scope.users, {id: '!' + user.id})

                        $timeout(function () {
                            $scope.messages['removed'] = false;
                        }, 4000);
                    }
                }, function (response) {
                    if (response.data.success === false) {
                        $scope.messages.errors = response.data.messages
                    }
                });
            }

            this.init();
        }]);
})(window.angular, window.jQuery);
