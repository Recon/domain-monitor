(function (angular, $) {
    'use strict';
    window.app.controller('index', ['$scope', '$http', 'userService', function ($scope, $http, userService) {
            $scope.user = userService;
        }]);
})(window.angular, window.jQuery);
