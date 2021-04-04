<?php
    $tname = "users";
    $fieldname = "UserName";
    $user_name = $this->session->userdata('user_name');
    // タイムゾーンをセットする。
    $tzstr = date_default_timezone_get();
    $defoulttz = "Asia/Tokyo";
    date_default_timezone_set($defoulttz);

    $dlogdb = new Dbclass();
    $dbpdo = $dlogdb->dbCi($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));
    $like = ' LIKE ';
    $userlist = $dlogdb->dbSelect($dbpdo, $tname, $like, $fieldname, $user_name);
    $dlogdb = null;
    // List読み込み
    $res = $userlist->fetchAll(\PDO::FETCH_ASSOC);
    $row = $res[0];
    $mcls = new Methodclass();

    $oldPassword = openssl_decrypt($row["Password"], $this->config->item('cipher') ,$this->config->item('key'));
    // $oldPassword = $mcls->deCrypt($row["Password"], $cipher, $key, $sslkey); // yamaguchi

    //test
    //$newdatetime = $mcls->getTimeDifference("UTC", "Asia/Tokyo");


    $mcls = null;
    $temp=explode('.',$row["LastLoginTime"]);
    $row["LastLoginTime"]=$temp[0];

    $temp=explode('.',$row["LoginTime"]);
    $row["LoginTime"]=$temp[0];
    $_SESSION['LoginTime'] = $row["LoginTime"];

    $dteStart = new \DateTime($_SESSION['LoginTime'], new \DateTimeZone($defoulttz));
    $newTime = new \DateTime();
    $dteEnd = new \DateTime($newTime->format('Y-m-d H:i:s'), new \DateTimeZone($defoulttz));
    $dteDiff = $dteStart->diff($dteEnd);
    $d_str = (int)$dteDiff->format("%d");
    $had_day = $d_str * 24;
    $h_str = $had_day + (int)$dteDiff->format("%H");
    $i_str = $dteDiff->format("%I");
    $s_str = $dteDiff->format("%S");
    $dteDiffStr = $h_str.' 時間 '.$i_str.' 分 '.$s_str.' 秒 ';
    $login_id = $row["ID"];
    date_default_timezone_set($tzstr);

    $inp_swichi = 0;
    $input_pass = "";
    $pass_non = "";
    if (!empty($_POST)) {
    $input_pass = trim($_POST["oldpass"]);
    if (strcmp($input_pass, trim($oldPassword)) != 0) {
        $input_pass = "";
        $inp_swichi = 0;
        $pass_non = "現在のパスワードを入力してください。";
    }
}


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

    <!-- custom style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_tablet.css" media="screen and (min-width: 769px)" type="text/css">

    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_tablet.css" media="screen and (min-width: 769px)" type="text/css">


    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
    
    <!-- yamaguchi -->
    <script type="text/javascript">
    var sitekey = <?php echo json_encode($this->config->item('siteKey')); ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/sitejs/page1_1.js"></script>
</head>
<style>
    #errorMessage {
        color: red;
    }
    input[type="password"]{
        width: 100%;
    font-size: 2rem;
    line-height: 1.8;
    }
</style>
<body id="pg_index" class="pg_index user_setting">

    
    <div class="wrapper">


        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>

        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('user_title');?></h1>
            <div class="content-grid">
                <form id="form1" method="post">
                    <div id="errorMessage"></div>
                    <div class="user-info-block flexlyr">
                        <p class=" confirm-msg">ユーザー名</p>
                        <p class=" confirm-input">
                            <?php
                            echo $row["UserName"];
                            ?>
                        </p>
                        <p class=" confirm-msg">メールアドレス</p>
                        <p class=" confirm-input">
                            <?php
                            echo $row["Email"];
                            ?>
                        </p>
                        <p class=" confirm-msg">最新ログイン時刻</p>
                        <p class=" confirm-input">
                            <?php
                            echo $row["LoginTime"];
                            ?>
                        </p>
                        <p class=" confirm-msg">前回のログイン時間</p>
                        <p class=" confirm-input">
                            <?php
                            echo $row["LastLoginTime"];
                            ?>
                        </p>
                        <p class=" confirm-msg">ログイン時間</p>
                        <p class=" confirm-input">
                            <?php
                            echo $dteDiffStr;
                            ?>
                        </p>
                    </div>
                    <a href="<?php echo base_url().'home';?>" class="confirm-btn">ホームに戻る</a>

                    <div class="user-info-block flexlyr">
                        <p class=" confirm-msg">パスワードの変更　　</p>
                        <p class=" confirm-input"><?php echo $pass_non;?></p>
                        <p class=" confirm-msg">現在のパスワード</p>
                        <p class=" confirm-input">
                            <input type="text" value="<?php
                        echo $input_pass;
                        ?>" id="oldpass" name="oldpass" onChange="oldPasscheck(this,<?php
                        echo $inp_swichi;?>);">
                        </p>
                        <p class=" confirm-msg">新しいパスワード</p>
                        <p class=" confirm-input">
                            <input type="password" class="form_control" id="newpass" name="newpass" required />

                        </p>
                        <p class=" confirm-msg">新しいパスワード（再入力）</p>
                        <p class=" confirm-input">
                            <input type="password" class="form_control"
                            onChange="CheckPassword();" id="confirm" name="confirm" required />
                            
                        </p>
                       
                        <p class=" confirm-input">   
                            <input type="hidden" id="login_id" name="login_id" value="
                            <?php
                                echo $login_id;
                            ?>"/>
                        </p>
                    </div>
                    <button id="update" class="confirm-btn" onclick="formSubmit();">パスワードを変更する</button>
                </form>
                <div style="display:none;" id="base_url"><?php echo base_url();?></div>
                <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
                 async defer></script>

            </div>
        </div>
    </div>
</body>

</html>