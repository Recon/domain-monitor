<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Website Monitor</title>
    <base href="/">

    <!-- build:css dist/css/style.min.css -->
    <link href="bower/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="bower/angular-loading-bar/build/loading-bar.css" rel="stylesheet">
    <link href="bower/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="bower/amitava82-angular-multiselect/dist/multiselect.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <!-- /build -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body ng-app="app">

<ng-include src="'templates/common/navigation.html'"></ng-include>

<!-- Page Content -->
<div class="site-view">
    <ng-view autoscroll="true"></ng-view>
</div>

<!-- build:js dist/js/vendor.min.js -->
<script src="bower/jquery/dist/jquery.js"></script>
<script src="bower/bootstrap/dist/js/bootstrap.js"></script>
<script src="bower/angular/angular.js"></script>
<script src="bower/moment/moment.js"></script>
<script src="bower/angular-loading-bar/build/loading-bar.js"></script>
<script src="bower/angular-route/angular-route.js"></script>
<script src="bower/bootstrap-confirmation2/bootstrap-confirmation.js"></script>
<script src="bower/amitava82-angular-multiselect/dist/multiselect-tpls.js"></script>
<!-- /build -->

<!-- build:js dist/js/app.min.js -->
<script src="js/monitor-metadata.js"></script>
<script src="angular/app.js"></script>
<script src="angular/config/http-error-interceptor.js"></script>
<script src="angular/config/http-default-headers.js"></script>
<script src="angular/config/routing.js"></script>
<script src="angular/config/loading-bar.js"></script>
<script src="angular/config/json-to-query-strings.js"></script>
<script src="angular/controllers/index.js"></script>
<script src="angular/controllers/overview.js"></script>
<script src="angular/controllers/navigation.js"></script>
<script src="angular/controllers/domain-settings.js"></script>
<script src="angular/controllers/user-manager.js"></script>
<script src="angular/controllers/user-settings.js"></script>
<script src="angular/controllers/login.js"></script>
<script src="angular/controllers/profile/profile-user-info.js"></script>
<script src="angular/controllers/password-reset.js"></script>
<script src="angular/services/user.js"></script>
<script src="angular/filters/moment.js"></script>
<script src="angular/filters/range.js"></script>
<script src="angular/filters/unsafe.js"></script>
<script src="angular/filters/capitalize.js"></script>
<!-- /build -->

</body>

</html>
