(function (angular, $) {
    'use strict';
    window.app.controller('navigation', ['$scope', '$http', 'userService', function ($scope, $http, userService) {
            $scope.user = userService;
        }]);
})(window.angular, window.jQuery);
