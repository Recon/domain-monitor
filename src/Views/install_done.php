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
            padding-top: 20px;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body ng-app="app">

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <p>
                    <strong>Installation complete!</strong>

                    <br>

                    You can proceed to the <a href="/#/login"><strong>login</strong></a> page.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="bower/jquery/dist/jquery.js"></script>
<script src="bower/bootstrap/dist/js/bootstrap.js"></script>
<script>
    (function ($) {
        var updateMailConfigurationFields = function () {
            var type = $('#select-mail-transport').val();
            $('.form-group.transport-smtp').toggle(type === 'smtp');
        }

        $('#select-mail-transport').on('change', updateMailConfigurationFields);
        updateMailConfigurationFields();
    })(jQuery);
</script>
</body>

</html>
