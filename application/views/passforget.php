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
</head>
<body id="pg_index" class="pg_index register">

<div class="pg-header flexlyr">
    <a href="<?php echo base_url();?>" class="logo-icon"><img src="<?php echo base_url() ?>assets/img/asset_01.png" alt=""></a>

</div>
<div class="wrapper">


    <!-- Sidebar  -->
    <nav id="sidebar" class="sidebar">
    </nav>

    <div class="content">
        <h1 class="page-title"><?=$this->lang->line('forgot_password_heading');?></h1>
        <?php if($this->session->userdata('lang')=='english'){?>
            <p class="nrl login-pg-link">Please return to the <a href="<?php echo base_url(); ?>" class="login-link">Login</a> page.</p>
        <?php }else{?>
            <p class="nrl login-pg-link"><a href="<?php echo base_url(); ?>" class="login-link">ログイン</a>ページに移動します。</p>
        <?php }?>
        

        <ul class="list-unstyled lead">
            <?= ($error = $this->session->flashdata('error')) ? "<li class='alert alert-danger'>{$error}</li>" : "" ?>
            <?= validation_errors(); ?>
        </ul>

        <?= form_open('Passforget/confirm'); ?>
        <section class="main-content flexlyr">
            <div class="content-grid">
                <div class="mail-send-block flexlyr">
                    <p class="confirm-msg"><?=$this->lang->line('username');?></p>
                    <p class="confirm-input"><input type="text" id="username" name="username" placeholder="<?=$this->lang->line('username');?>" required value="<?= set_value('username'); ?>"></p>
                    <p class="confirm-msg"><?=$this->lang->line('mailaddress');?></p>
                    <p class="confirm-input"><input type="text" id="email" name="email" placeholder="<?=$this->lang->line('mailaddress');?>" required value="<?= set_value('email'); ?>"></p>

                    <p class=" confirm-msg"></p>
                    <p class=" confirm-input"><img src="<?php echo base_url() ?>captcha/generate" alt=""></p>

                    <p class=" confirm-msg"><?=$this->lang->line('authentication');?></p>
                    <p class=" confirm-input"><input type="text" required name="captcha"></p>

                    <button id="update" class="confirm-btn" onclick="formSubmit();"><?=$this->lang->line('Send');?></button>
                </div>
            </div>
        </section>
        <?= form_close() ?>
    </div>

</div>
</body>

</html>
