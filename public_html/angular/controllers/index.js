(function (angular, $) {
    'use strict';
    window.app.controller('index', ['$scope', '$http', '$location', 'userService', function ($scope, $http, $location, userService) {
        //$scope.user = userService;
        $location.path("overview");
    }]);
})(window.angular, window.jQuery);
