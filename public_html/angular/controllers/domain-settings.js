(function (angular, $, testTypes) {
    'use strict';
    window.app.controller('domainSettings', ['$scope', '$http', '$location', '$timeout', '$routeParams', 'userService',
        function ($scope, $http, $location, $timeout, $routeParams, userService) {
            var self = this;

            $scope.user = userService;

            $scope.messages = {
                added: false,
                edited: false,
                errors: []
            };

            $scope.domain = {
                tests: []
            };

            $scope.testOptions = [
                {id: testTypes.TYPE_HTTP, name: 'HTTP'},
                {id: testTypes.TYPE_HTTPS, name: 'HTTPS'}
            ];

            $scope.addTest = function () {
                $scope.domain.tests.push({
                    type: $scope.testOptions[0]
                });
            };

            $scope.getTestHelpText = function (type) {
                switch (type.id) {
                    case testTypes.TYPE_HTTP:
                        return 'Will verify that a non-error response code is returned when using the HTTP protocol';
                    case testTypes.TYPE_HTTPS:
                        return 'Will verify that a non-error response code is returned when using the HTTPS protocol';
                }
            };

            $scope.canAddTest = function () {

            };

            $scope.removeTest = function (test) {
                var index = $scope.domain.tests.indexOf(test);
                $scope.domain.tests.splice(index, 1);
            };

            $scope.save = function () {
                $scope.messages.errors = [];
                $scope.messages.added = false;

                if ($scope.domain.id) {
                    self.save('domains/update', 'edited');
                } else {
                    self.save('domains/add', 'added');
                }
            };

            this.init = function () {
                if ($routeParams.id) {
                    $http.get('domains/item.json', {
                        params: {id: $routeParams.id}
                    }).then(function (response) {
                        var domain = $.extend({}, response.data);
                        domain.tests = [];

                        for (var i in response.data.tests) {
                            var test = response.data.tests[i];
                            test.type = $.grep($scope.testOptions, function (element, index) {
                                return element.id == test.test_type;
                            })[0];
                            domain.tests.push(test);
                        }

                        $scope.domain = domain;
                    });
                }
            };

            this.save = function (route, messageKey) {
                $http.post(route, $scope.domain).then(function (response) {
                    if (response.data.success === true) {
                        $scope.messages[messageKey] = true;

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

            this.init();
        }]);
})(window.angular, window.jQuery, monitorMetadata.testTypes);
