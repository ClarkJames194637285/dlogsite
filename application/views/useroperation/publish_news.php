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

    //パスワードの照会
    $oldPassword = openssl_decrypt($user["Password"], $this->config->item('cipher') ,$this->config->item('key'));
    //パスワードの照合
    if (strcmp($password, trim($oldPassword)) == 0) {
      if (isset($_FILES['userfile'])) {
        $tempfile = $_FILES['userfile']['tmp_name'];
        //info.htmlに上書き

        $filename = './news.html';

        if (is_uploaded_file($tempfile)) {
          if (move_uploaded_file($tempfile, $filename)) {
            echo "ファイルをアップロードしました。";
          } else {
            echo "ファイルをアップロードできません。";
          }
        } else {
          echo "ファイルが選択されていません。";
        };
      }
    } else {
      echo 'パスワードが一致しません';
    }
  } else {
    echo 'ユーザが登録されていません';
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
  <title>システムメンテナンス情報アップロード</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">

  <!-- slider, preloader style -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">


  <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> -->
  <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
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

<body>
  <div class="pg-header flexlyr">
    <div class="logo-icon">
      <img src="<?php echo base_url()?>assets/img/asset_01.png" alt="">
    </div>
  </div>

  <div class="publish_pg">
    <h1 class="page-title">広告欄アップロード</h1>
    <div class="publish-form">
      <div class="form-input">
        <form enctype="multipart/form-data" action="" method="POST">
          <input type="hidden" name="name" value="value">
          <p class="title">アカウント名：</p><input type="text" lang="ja" placeholder="アカウント名を入力してください" name="user_name" required>
          <p class="title">パスワード：</p><input type="password" lang="ja" placeholder="パスワードを入力してください" size="45" name="password" required></p>
          <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
          <input name="userfile" type="file" class="input-form ">
          <button class="publish-btn" type="submit">ファイル送信</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>