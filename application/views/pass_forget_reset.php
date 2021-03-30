<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>BSCM</title>
    <!-- viewport setting -->
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
    <!-- slider, preloader style -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/loaders.css" type="text/css">


    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <!-- BootStrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- custom style -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/layout_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/layout_tablet.css" media="screen and (min-width: 769px)" type="text/css">

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/menu_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/menu_tablet.css" media="screen and (min-width: 769px)" type="text/css">


    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/wow.min.js"></script>
    <style>
        input[type="password"]{
            width: 100%;
            font-size: 2rem;
            line-height: 1.8;
        }
    </style>
</head>
<body id="pg_index" class="pg_index register">

<div class="pg-header flexlyr">
    <a href="<?php echo base_url();?>" class="logo-icon"><img src="<?php echo base_url()?>assets/img/asset_01.png" alt=""></a>

</div>
<div class="wrapper">


    <!-- Sidebar  -->
    <nav id="sidebar" class="sidebar">
    </nav>

    <!-- Page Content  -->
    <div class="content">
        <ul class="list-unstyled lead">
            <?= ($error = $this->session->flashdata('error')) ? "<li class='alert alert-danger'>{$error}</li>" : "" ?>
            <?= validation_errors(); ?>
        </ul>

        <h1 class="page-title">パスワードの再送信</h1>
        <?= form_open('Passforget/resetConfirm'); ?>
        <section class="main-content flexlyr">
            <div class="content-grid">
                <div class="mail-send-block flexlyr">
                    <input type="hidden" id="payload" name="payload" value="<?= htmlspecialchars($payload) ?>">
                    <input type="hidden" id="token" name="token" value="<?= htmlspecialchars($token) ?>">

                    <p class=" confirm-msg">パスワード</p>
                    <p class=" confirm-input"><input type="password" id="password" name="password" placeholder="パスワード" required value="<?= set_value('password'); ?>"></p>

                    <p class=" confirm-msg">パスワードの再入力</p>
                    <p class=" confirm-input"><input type="password" id="password_confirm" name="password_confirm" placeholder="パスワードの再入力" required
                                                     value="<?= set_value('password_confirm'); ?>"></p>


                    <p class=" confirm-msg"></p>
                    <p class=" confirm-input"><img src="<?php echo base_url() ?>captcha/generate" alt=""></p>

                    <p class=" confirm-msg">認証コード</p>
                    <p class=" confirm-input"><input type="text" required name="captcha"></p>

                    <button id="update" class="confirm-btn">リセット</button>

                </div>
            </div>
        </section>
        <?= form_close() ?>
    </div>

</div>

</body>

</html>
