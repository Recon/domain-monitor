(function (angular, $) {
    'use strict';
    window.app.controller('userSettings', ['$scope', '$http', '$routeParams', '$location', '$timeout', 'userService',
        function ($scope, $http, $routeParams, $location, $timeout, userService) {

            var self = this;

            $scope.user = {};
            $scope.domains = [];

            $scope.messages = {
                added: false,
                edited: false,
                removed: false,
                errors: []
            };

            $scope.save = function () {
                if ($scope.user.id) {
                    self.save('user/update', 'edited');
                } else {
                    self.save('user/add', 'added');
                }
            };

            this.init = function () {
                if ($routeParams.id) {
                    $http.get('user/info.json', {
                        params: {id: $routeParams.id}
                    }).then(function (response) {
                        $scope.user = response.data;
                    });
                }

                $http.get('domains/list.json').then(function (response) {
                    $scope.domains = response.data;
                });
            };

            this.save = function (route, messageKey) {
                $http.post(route, $scope.user).then(function (response) {
                    if (response.data.success === true) {
                        $scope.messages[messageKey] = true;

                        $timeout(function () {
                            $location.path('users')
                        }, 1000);
                    }
                }, function (response) {
                    if (response.data.success === false) {
                        $scope.messages.errors = response.data.messages
                    }
                });
            };

            this.init();
        }]);
})(window.angular, window.jQuery);
