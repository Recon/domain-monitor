(function (angular, $) {
    'use strict';
    window.app.controller('overview', ['$scope', '$http', 'userService', function ($scope, $http, userService) {
            $scope.user = userService;
            $scope.domains = [];

            $http.get('domains/list.json').then(function (response) {
                $scope.domains = response.data;
            });

            $scope.delete = function (index) {
                $http.get('domains/delete', {
                    params: {
                        id: $scope.domains[index].id
                    }
                }).then(function (response) {
                    if (response.data.success == true) {
                        $scope.domains.splice(index, 1);
                    }
                });
            };

            $scope.setEnabled = function (item, status) {
                $http.post('domains/set-enabled', {
                    id: item.id,
                    enabled: !!status
                }).then(function (response) {
                    if (response.data.success == true) {
                        item.is_enabled = !!status;
                    }
                });
            };
        }]);
})(window.angular, window.jQuery);
