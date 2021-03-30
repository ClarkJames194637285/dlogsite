<?php



$tname = "users";
$fieldname = "ID";
$dstr = $_POST["login_id"];

if (isset($_POST["newpass"]) && $_POST["newpass"] !=="") {
    $methodclass = new Methodclass();
    $enc_password = openssl_encrypt(trim($_POST["newpass"]), $this->config->item('cipher') ,$this->config->item('key'));
    //$enc_password = $methodclass->enCrypt(trim($_POST["newpass"]), $cipher, $key, $sslkey); // yamaguchi
    $up_darry = array(
        "Password"=>$enc_password
    );
    $methodclass = null;
}
$dlogdb = new Dbclass();
$dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
try{
    $userlist = $dlogdb->dbUpdate($dbpdo, $tname, $up_darry, $fieldname, $dstr);
    if($userlist) $res_status = "正常に変更されました。";
}catch(exception  $e){
    $res_status="変更が失敗しました。";
}

$dlogdb = null;

?>


    <!-- slider, preloader style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">


    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="
    sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <!-- BootStrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
  

    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>

</head>

<body id="pg_index" class="pg_index page1_1-confirm">
    
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title">ユーザー設定</h1>
            <section class="main-content flexlyr">
                <div class="content-grid">
                    
                    <p class="nrl confirm-msg"><?php echo $res_status; ?></p>
                    <a href="<?php echo base_url().'home';?>" class="confirm-btn">ホームに戻る</a>
                </div>
            </section>
        </div>
    </div>
</body>

</html>