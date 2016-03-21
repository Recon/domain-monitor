(function (angular, $) {
    'use strict';
    window.app.controller('profileUserInfo', ['$scope', '$http', '$routeParams', '$location', '$timeout', 'userService',
        function ($scope, $http, $routeParams, $location, $timeout, userService) {

            var self = this;

            $scope.user = userService.info;

            $scope.messages = {
                edited: false,
                errors: []
            };

            $scope.save = function () {
                $http.post('user/update', $scope.user).then(function (response) {
                    if (response.data.success === true) {
                        $scope.messages['edited'] = true;

                        $timeout(function () {
                            $location.path('overview')
                        }, 1000);
                    }
                }, function (response) {
                    if (response.data.success === false) {
                        $scope.messages.errors = response.data.messages
                    }
                });
            };

            this.init = function () {
                if ($routeParams.id) {
                    $http.get('user/info.json', {
                        params: {id: $routeParams.id}
                    }).then(function (response) {
                        $scope.user = response.data;
                    });
                }
            };


            this.init();
        }]);
})(window.angular, window.jQuery);
