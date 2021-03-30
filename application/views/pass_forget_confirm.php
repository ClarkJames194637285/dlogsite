<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>BSCM</title>
    <!-- viewport setting -->
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
    <!-- slider, preloader style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">


    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <!-- BootStrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <!-- custom style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_tablet.css" media="screen and (min-width: 769px)" type="text/css">

    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_tablet.css" media="screen and (min-width: 769px)" type="text/css">
    

    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
    <style>
        .content-grid{
            font-size:23px !important;
            line-height: 2.5;
            padding-top: 10vw;
        }
    </style>

</head>

<body id="pg_index" class="pg_index pass-forget-confirm">
    
    <div class="pg-header flexlyr">
        <a href="<?php echo base_url();?>" class="logo-icon"><img src="<?php echo base_url()?>assets/img/asset_01.png" alt=""></a>
        <!-- <div class="user-infor flexlyr">
            <a href="" class="user-comment"><img src="<?php echo base_url()?>assets/img/asset_08.png" alt=""><p>19</p></a>
            <a href="" class="user-name"><img src="<?php echo base_url()?>assets/img/asset_02.png" alt=""><span>USERNAME</span></a>
        </div> -->
    </div>
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <nav id="sidebar" class="sidebar">
        </nav>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title">パスワードの再送信</h1>
            <section class="main-content flexlyr">
                <div class="content-grid">
                    <p class="confirm-msg" style="font-size: 28px;">送信が完了しました。</p>
                    <a href="<?php echo base_url();?>" class="confirm-btn">ログイン画面に移動。</a>
                </div>
            </section>
        </div>
    </div>
</body>

</html>