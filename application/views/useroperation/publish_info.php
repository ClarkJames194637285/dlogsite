<?php

if (isset($_POST['name'])) {
  $user_name = filter_input(INPUT_POST, 'user_name');
  $password = filter_input(INPUT_POST, 'password');
  //入力されたusernameのデータをuserテーブルから所得
  $dlogdb = new User_logic();
  
  $data = array(
    'host'=>$this->config->item('host'),
    'dbname'=>$this->config->item('dbname'),
    'username'=>$this->config->item('username'),
    'password'=>$this->config->item('password')
    
  );
  $dbpdo = $dlogdb->connect($data);
  $user = $dlogdb->getUser( $dbpdo,$user_name);
  if ($user) {
    //ここに権限を確認する処理が必要
    $oldPassword = openssl_decrypt($user["Password"], $this->config->item('cipher') ,$this->config->item('key'));
    //パスワードの照合
    if (strcmp($password, trim($oldPassword)) == 0) {
      if (isset($_FILES['userfile'])) {
        $tempfile = $_FILES['userfile']['tmp_name'];
        //info.htmlに上書き
        $filename = './info.html';
        if (is_uploaded_file($tempfile)) {
          if (move_uploaded_file($tempfile, $filename)) {
            redirect(base_url().'setting/useroperation/publish_conf');
          } else {
            $text = "ファイルをアップロードできません。";
          }
        } else {
          $text = "ファイルが選択されていません。";
        };
      }
    } else {
      $text = 'パスワードが一致しません';
    }
  } else {
    $text = 'ユーザが登録されていません';
  }
  $dlogdb = null;
}
if (isset($text)) {
  $alert = "<script type='text/javascript'>alert('" . $text . "');</script>";
  echo $alert;
}
?>

  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">


  <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> -->
  <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
  <!-- BootStrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

   <!-- custom jscript -->
  <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
  <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
  <style>
      .confirm-btn{
        text-align: center;
        display: inline-block;
        /* width: 19rem; */
        padding: 0 2rem;
        margin: 4rem auto;
        background: #2A3A62;
        border-radius: 8px;
        font-family: HKGProNW6;
        font-size: 2rem;
        line-height: 1.64;
        color: #fff;
      }
      input[type="password"]{
            width: 100%;
        font-size: 2rem;
        line-height: 1.8;
        }
        .confirm-input{
          margin-bottom: 20px !important;
        }
        .upload{
          padding:10px
        }
  </style>
</head>

<body id="pg_index" class="pg_index useroperation">

    <div class="wrapper">
        <?php $this->load->view('menu'); ?>
        <div class="content">
        <div class="publish_pg">
            <h1 class="page-title">システム情報アップロード</h1>
            <div class="publish-form">
              <div class="form-input">
                <form enctype="multipart/form-data" action="useroperation" method="POST">
                  <section class="main-content flexlyr">
                    <div class="content-grid">
                        <div class="register-block flexlyr">
                            <input type="hidden" name="name" value="value">
                            
                            <p class=" confirm-msg">アカウント名：</p>
                            <p class=" confirm-input"><input type="text" placeholder="アカウント名を入力してください" name="user_name" required></p>
                            
                            <p class=" confirm-msg">パスワード：</p>
                            <p class=" confirm-input"><input type="password" placeholder="パスワードを入力してください"  name="password" required></p>
                            <div class="upload">
                              <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                              <input name="userfile" type="file" class="input-form ">
                            </div>
                          </div>
                        <button class="confirm-btn" type="submit">ファイル送信</button>
                      </div>
                    </section>
                </form>
              </div>
            </div>
        </div>
        
        <div class="pg-footer">
            <p class="footer-label">©︎2020 - CUSTOM corporation</p>
        </div>
    <div>
</body>

</html>