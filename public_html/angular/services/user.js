(function (angular, $) {
    'use strict';

    var UserService = function ($http, $rootScope) {
        var self = this;

        this.info = {is_authenticated: false};

        this.refresh = function () {
            $http.get('user/info_authenticated.json').then(function (response) {
                if (response.data.id) {
                    self.info = response.data;
                    self.info.is_authenticated = true;
                } else {
                    self.info = {is_authenticated: false};
                }
            });
        };

        this.refresh();

        $rootScope.$on('user.login', function(){
            self.refresh();
        });
    };

    app.factory('userService', ['$http', '$rootScope', function ($http, $scope) {
            return new UserService($http, $scope);
        }]);
})(window.angular, window.jQuery);
