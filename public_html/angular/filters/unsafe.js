(function (angular, app, $) {
    'use strict';

    app.filter('unsafe', function ($sce) {
        return $sce.trustAsHtml;
    });
})(angular, window.app, window.jQuery);
