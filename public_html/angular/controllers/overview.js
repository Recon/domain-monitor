(function (angular, $, monitorMetadata) {
    'use strict';
    window.app.controller('overview', ['$scope', '$http', 'userService', function ($scope, $http, userService) {
            var self = this;
            $scope.user = userService;
            $scope.domains = [];
            $scope.type_names = monitorMetadata.testTypesNames;
            $scope.statuses = monitorMetadata.domainStatuses;

            $http.get('domains/list.json').then(function (response) {
                var domains = response.data;

                for (var i in domains){
                    domains[i].successful_test_count = self.countSuccessfulTests(domains[i]);
                }

                $scope.domains = domains;
            });

            $scope.delete = function (index, $event) {
                $($event.target).confirmation({
                    placement: 'left',
                    btnOkIcon: 'fa fa-check',
                    btnCancelIcon: 'fa fa-times',
                    onConfirm: function(){
                        self.delete(index);
                        $($event.target).confirmation('destroy');
                    },
                    onCancel: function(){
                        $($event.target).confirmation('destroy');
                    }
                });

                $($event.target).confirmation('show');
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

            self.countSuccessfulTests = function (record) {
                var count = 0;
                for (var i in record.tests) {
                    if (record.tests[i].status == true)
                        count++;
                }

                return count;
            };

            self.delete= function(index){
                $http.get('domains/delete', {
                    params: {
                        id: $scope.domains[index].id
                    }
                }).then(function (response) {
                    if (response.data.success == true) {
                        $scope.domains.splice(index, 1);
                    }
                });
            }
        }]);
})(window.angular, window.jQuery, monitorMetadata);
