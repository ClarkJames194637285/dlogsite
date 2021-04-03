<?php 
$select_arr = array(
    '+12'   => 12,
    '+11'   => 11,
    '+10.5'   => 10.5,
    '+10'   => 10,
    '+9.5'   => 9.5,
    'タイムゾーン +9（日本）'   => "9",
    '+8'   => 8,
    '+7'   => 7,
    '+6.5'   => 6.5,
    '+6'   => 6,
    '+5.5'   => 5.5,
    '+5'   => 5,
    '+4'   => 4,
    '+3'   => 3,
    '+2'   => 2,
    '+1'   => 1,
    'UTC'   => 0,
    '-1'   => -1,
    '-2'   => -2,
    '-3'   => -3,
    '-4'   => -4,
    '-5'   => -5,
    '-6'   => -6,
    '-7'   => -7,
    '-8'   => -8,
    '-9'   => -9,
    '-10'   => -10,
    '-11'   => -11,
    '-12'   => -12
);

if (isset($_POST['login'])) {
    $mysqli = new mysqli($this->config->item('host'),$this->config->item('username'),$this->config->item('password'), $this->config->item('dbname'));

    if ($mysqli->connect_error) {
        error_log($mysqli->connect_error, 3, APPPATH."logs/test.log");
        exit;
    }
    if ($_POST['user_name'] != "") {
        $mcls = new Methodclass;
        $user_name = $mysqli->real_escape_string($_POST['user_name']);
        $password = $mysqli->real_escape_string($_POST['password']);
        $timezone = $_POST['Timezone'];
        //timezone insert
        $query = 'update users set TimeZone='.$timezone.' WHERE UserName="' . $user_name . '"';
        $result = $mysqli->query($query);
        if (!$result) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
        }
        //クエリの実行
        $query = 'SELECT * FROM `users` WHERE `UserName` LIKE "' . $user_name . '"';
        $result = $mysqli->query($query);
        if (!$result) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
        }
    
        if (!$result) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
        }
        $password_registered = "";
        // パスワード(暗号化済み）とユーザーIDの取り出し
        while ($row = $result->fetch_assoc()) {
            $password_registered = $row['Password'];
            $user_id = $row['ID'];
            $time_zone = $row['TimeZone'];
            // 複合化
            //$password_registered = $mcls->deCrypt($password_registered, $cipher, $key, $sslkey); //yamaguchi
            
            $password_registered = openssl_decrypt($password_registered,$this->config->item('cipher') ,$this->config->item('key'));
        }
        session_regenerate_id(true); // セッションIDをふりなおす
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name; // ユーザー名をセッション変数にセット
            $_SESSION['TimeZone'] = $time_zone;
            $cookiestr = 'user_id:' . $user_id;
            $cookiestr .= ',user_name:' . $user_name;
            $cookiestr .= ',password:' . $password;
            $cookiestr .= ',TimeZone:' . $time_zone;
            $cookiestr .= ',resaved:' . $_POST['resaved'];
            $expiration_time = time() + 60 * 60 * 24;
            setcookie('BSCM', $cookiestr, $expiration_time);
            if (isset($_COOKIE['BSCM'])) {
                $get_cookie = $_COOKIE['BSCM'];
                $cookie_arry = explode(',', $get_cookie);
                for ($i = 0; $i < count($cookie_arry); $i ++) {
                    $keyval = explode(':', $cookie_arry[$i]);
                    $get_data[$keyval[0]] = $keyval[1];
                }
                $resaved = "checked";
            }
            setcookie('register', 'false');
        // データベースの切断
        $result->close();
        if ($password == $password_registered && $password != '') {
            // ログイン認証成功の処理
            //session_start();
            
            redirect(base_url().'home');
            exit;
        } else {
            $text = "ユーザー名とパスワードが一致しません。";
            $alert = "<script type='text/javascript'>alert('". $text. "');</script>";
            echo $alert;
            if (isset($_COOKIE['BSCM'])) {
                $get_cookie = $_COOKIE['BSCM'];
                $cookie_arry = explode(',', $get_cookie);
                for ($i = 0; $i < count($cookie_arry); $i ++) {
                    $keyval = explode(':', $cookie_arry[$i]);
                    $get_data[$keyval[0]] = $keyval[1];
                }
                // $resaved = "checked";
            }
            if (isset($_COOKIE['register'])) {
                $register=$_COOKIE['register'];
            }else{
                $register="false";
            }
            // <div class="alert alert-danger" role="alert">ユーザー名とパスワードが一致しません。</div> -->
        }
    }
} else {
    if (isset($_COOKIE['BSCM'])) {
        $get_cookie = $_COOKIE['BSCM'];
        $cookie_arry = explode(',', $get_cookie);
        for ($i = 0; $i < count($cookie_arry); $i ++) {
            $keyval = explode(':', $cookie_arry[$i]);
            $get_data[$keyval[0]] = $keyval[1];
        }
        // $resaved = "checked";
    }
    if (isset($_COOKIE['register'])) {
        $register=$_COOKIE['register'];
    }else{
        $register="false";
    }
}
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
    <script>
    function check_valset(id) {
        if (id.checked){
            id.value = 1;
        } else {
            id.value = 0;
        }
    }
    function login_submit() {
        var form = document.getElementById('form');
        if (form.resaved.checked) {
            form.resaved.value = 1;
        } else {
            form.resaved.value = 0;
        }
    }
    </script>
   
    <style type="text/css">
        #form input[type="password"]
        {
            width: 100%;
            border: solid #707070 1px;
            font-size: 1.4rem;
            line-height: 1.5;
            margin: 0.5rem 0;
        }
    </style>
        
</head>

<body id="pg_index" class="pg_index">

    <div class="login_bg">
        <div class="login_window">
            <div class="login-form">
                <div class="form-language">
                    <?php if($this->session->userdata('lang')=='english'){?>
                        <a href="<?php echo base_url();?>user/langset/japanese">日本語</a>
                        <a href="<?php echo base_url();?>user/langset/english" class="active">English</a>
                    <?php }else{?>
                        <a href="<?php echo base_url();?>user/langset/japanese" class="active">日本語</a>
                        <a href="<?php echo base_url();?>user/langset/english">English</a>
                    <?php }?>
                </div>
                <div class="form-input">
                    <form id="form" class="login" action="<?php echo base_url();?>" method="post">
                        <input type="text" class="input-form langCng" 
                        placeholder="<?=$this->lang->line('username');?>" name="user_name"
                        <?php
                        if (isset($get_data)) {
                            if($get_data['resaved']&&$register=="false")
                            echo 'value="' . $get_data['user_name'] . '"';
                        }
                        ?> required>
                        <!-- <input type="text" class="input-form langCng" 
                        lang="en" placeholder="User Name" name="user_name_en" required> -->
                        <input type="password" class="input-form langCng" 
                        placeholder="<?=$this->lang->line('password');?>" width="100%" name="password" value="" required>
                        <!-- <input type="password" class="input-form langCng" 
                        lang="en" placeholder="Password" name="password_en" required> -->
                        <select name="Timezone" id="Timezone">
                        <?php
                        if (isset($get_data)) {
                            foreach ($select_arr as $key => $val) {
                                if (floatval($get_data['TimeZone']) == floatval($val)) {
                                    echo '<option class ="langCng" value="' . $val . '" selected>';
                                    echo $key . '</option>';
                                } else {
                                    echo '<option value="' . $val . '">' . $key . '</option>';
                                }
                            }
                        } else {
                            ?>
                            <option value="12">+12</option>
                            <option value="11">+11</option>
                            <option value="10.5">+10.5</option>
                            <option value="10">+10</option>
                            <option value="9.5">+9.5</option>
                            <option class ="langCng" value="9" selected><?=$this->lang->line('timezone');?></option>
                            <option value="8">+8</option>
                            <option value="7">+7</option>
                            <option value="6.5">+6.5</option>
                            <option value="6">+6</option>
                            <option value="5.5">+5.5</option>
                            <option value="5">+5</option>
                            <option value="4">+4</option>
                            <option value="3">+3</option>
                            <option value="2">+2</option>
                            <option value="1">+1</option>
                            <option value="0">UTC</option>
                            <option value="-1">-1</option>
                            <option value="-2">-2</option>
                            <option value="-3">-3</option>
                            <option value="-4">-4</option>
                            <option value="-5">-5</option>
                            <option value="-6">-6</option>
                            <option value="-7">-7</option>
                            <option value="-8">-8</option>
                            <option value="-9">-9</option>
                            <option value="-10">-10</option>
                            <option value="-11">-11</option>
                            <option value="-12">-12</option>
                            <?php
                        }
                        ?>
                        </select>
                        <label class="container">
                            <p class="langCng"><?=$this->lang->line('remember_me');?></p>
                            <!-- <p class="langCng" lang="en">Remember Me</p> -->
                            <?php
                            echo '<input id="resaved" name="resaved" onChange="check_valset(this);"';
                            echo 'type="checkbox" ';
                            if (isset($get_data['resaved'])) {
                                if($get_data['resaved']==1)
                                echo "checked='checked'";
                            }
                            echo '" value=';
                            if (isset($get_data)) {
                                echo $get_data['resaved'];
                            }
                            echo '>';
                            ?>
                            <span class="checkmark"></span>
                        </label>
                        <button id = "login" name="login" class="login-btn langCng" type="submit"
                         onclick="login_submit();"><?=$this->lang->line('login');?></button>
                        <!-- <button class="login-btn langCng" lang="en" type="submit" name="login">login</button> -->
                    </form>
                </div>
                <div class="forget-pass">
                    <a href="<?php echo base_url();?>Passforget" class ="langCng"><?=$this->lang->line('forgot_password');?></a>
                    <!-- <a href="pass-forget.php" class ="langCng" lang="en">forget password?</a> -->
                </div>
                <a href="<?php echo base_url();?>register" class="register-btn langCng"><?=$this->lang->line('register');?></a>
                <!-- <a href="register.php" class="register-btn langCng" lang="en">Create new account</a> -->
            </div>
        </div>
    </div>
    <script>
        $('.form-language a').on('click',function(){
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        })
    </script>
    <footer class="pg-footer login-footer">
        <p class="footer-label">©︎2020 -  CUSTOM corporation</p>
    </footer>

</body>

</html>
  