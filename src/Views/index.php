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

        <link href="bower/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
        <link href="bower/angular-loading-bar/build/loading-bar.css" rel="stylesheet">
        <link href="bower/font-awesome/css/font-awesome.css" rel="stylesheet">

        <style>
            body {
                padding-top: 70px;
            }
            .table > tbody > tr > td,
            .table > tbody > tr > th{
                vertical-align: middle;
            }
        </style>

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

    <script src="bower/jquery/dist/jquery.js"></script>
    <script src="bower/bootstrap/dist/js/bootstrap.js"></script>
    <script src="bower/angular/angular.js"></script>
    <script src="bower/moment/moment.js"></script>
    <script src="bower/angular-loading-bar/build/loading-bar.js"></script>
    <script src="bower/angular-route/angular-route.js"></script>

    <script src="js/monitor-metadata.js"></script>
    <script src="angular/app.js"></script>
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
</body>

</html>
