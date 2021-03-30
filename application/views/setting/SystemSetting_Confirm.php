<?php

$user_name = $this->session->userdata('user_name');
// TimeZone　field update
// システム時間、温度単位　テーブル・フィールドが判明したら更新追記する。
if (isset($_POST['timezone']) && isset($_POST['login_id']) &&
 isset($_POST['systime']) && isset($_POST['corf']) && isset($_POST['setting_id'])) {
    $up_darry = array(
        'TimeZone' => (int)$_POST['timezone']
    );
    $dstr = (int)trim($_POST["login_id"]);
    $tname = "users";
    $fieldname = "ID";
    $dlogdb = new Dbclass();
    $dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
    $userlist = $dlogdb->dbUpdate($dbpdo, $tname, $up_darry, $fieldname, $dstr);
    $tname = "usersetting";
    $dstr = (int)trim($_POST['setting_id']);
    $up_darry = null;
    $up_darry = array(
        'IsSearchRTC'       => (int)$_POST['systime'],
        'TemperatureUnit'   => (int)$_POST['corf']
    );
    $userlist = $dlogdb->dbUpdate($dbpdo, $tname, $up_darry, $fieldname, $dstr);
}

$dlogdb = null;


?>
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


    <script src="https://code.jquery.com/jquery-3.4.1.min.js" 
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

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

</head>

<body id="pg_index" class="pg_index page1_2-confirm">
    
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
       
        <?php $this->load->view('menu'); ?>
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title">システム設定</h1>
            <section class="main-content flexlyr">
                <div class="content-grid">
                    <p class="nrl confirm-msg">設定を保存しました。</p>
                    <a href="<?php echo base_url();?>home" class="confirm-btn">ホームに戻る</a>
                </div>
            </section>
        </div>
    </div>
</body>

</html>