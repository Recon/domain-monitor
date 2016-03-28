(function (angular, app, $) {
    'use strict';

    app.config(['cfpLoadingBarProvider', function (cfpLoadingBarProvider) {
        cfpLoadingBarProvider.includeSpinner = false;
        cfpLoadingBarProvider.includeBar = true;
    }]);
})(angular, window.app, window.jQuery);
