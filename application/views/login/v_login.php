<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v2.0.0
* @link https://coreui.io
* Copyright (c) 2018 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<style>
    .valError {
        color: #c40000;
        font-size: 14px;
    }
</style>

<html lang="en">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>Sistem Sewaan MPKj</title>
    <!-- Icons-->
    <link href="/assets/node_modules/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="/assets/node_modules/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="/assets/node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/node_modules/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/style_extend.css" rel="stylesheet">
    <link href="/assets/node_modules/pace-progress/css/pace.min.css" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics-->
</head>
<body class="hold-transition login-page" style="background: url('/assets/images/bg.jpg');background-repeat: no-repeat;background-size:cover">
    <div class="login-box">
        <div class="login-box-body">
            <div class="text-center">
                <img class="center" src="/assets/images/logompkj.gif" style="height: 110px;">
            </div>
            <p class="login-box-msg" style="color: #1F1F1F;font-size: 22px; font-weight: bold;margin: 0px;padding: 0px;">SPS MPKj</p>
            <p class="login-box-msg" style="color: #1F1F1F;font-size: 18px; font-weight: bold">Sistem Pengurusan Sewaan</p>
            <form method="post" action="/login" class="form-horizontal">
                <div class="form-group has-feedback">
                    <input class="form-control" type="text" name="username" value="<?php echo input_data('username')?>" placeholder="ID Pengguna">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <?php echo form_error('username')?>
                </div>
                <div class="form-group has-feedback">
                    <input class="form-control" type="password" name="password" placeholder="Katalaluan">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <?php echo form_error('password')?>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <button class="btn btn-success btn-square px-4" type="submit">MASUK</button>
                    </div>
                </div>
            </form>

    <!--        <a href="#">Lupa katalaluan?</a><br>-->
    <!--        <a href="register.html" class="text-center">Register a new membership</a>-->

        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
<!-- CoreUI and necessary plugins-->
<script src="/assets/node_modules/jquery/dist/jquery.min.js"></script>
<script src="/assets/node_modules/popper.js/dist/umd/popper.min.js"></script>
<script src="/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/assets/node_modules/pace-progress/pace.min.js"></script>
<script src="/assets/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
<script src="/assets/node_modules/@coreui/coreui/dist/js/coreui.min.js"></script>
</body>
</html>