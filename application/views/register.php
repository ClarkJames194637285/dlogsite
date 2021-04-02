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
    <div style="display:none;" id="base_url"><?php echo base_url();?></div>
    <style>
       input[type="password"]{
            width: 100%;
            font-size: 2rem;
            line-height: 1.8;
        }
        .modal-title{
            font-size: 25px;
            text-align: left;
        }
        .modal-body{
            font-size: 15px;
            max-height: calc(100vh - 250px);
            overflow-y: auto;
            text-align: left;
            line-height: 1.5em;
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
            <h1 class="page-title"><?=$this->lang->line('register_title');?></h1>
            <?php if($this->session->userdata('lang')=='english'){?>
                <p class="nrl login-pg-link">If you have already registered, go to the <a href="<?php echo base_url(); ?>" class="login-link">Login</a> page.</p>
            <?php }else{?>
                <p class="nrl login-pg-link"><a href="<?php echo base_url(); ?>" class="login-link">ログイン</a>ページに移動します。</p>
            <?php }?>
           
            <ul class="list-unstyled lead">
                <?= ($error = $this->session->flashdata('error')) ? "<li class='alert alert-danger'>{$error}</li>" : "" ?>
                <?= validation_errors(); ?>
            </ul>

            <?= form_open('Register/confirm'); ?>
            <section class="main-content flexlyr">
                <div class="content-grid">
                    <div class="register-block flexlyr">
                        <p class=" confirm-msg" for="username"><?=$this->lang->line('username');?></p>
                        <p class=" confirm-input"><input type="text" id="username" name="username" placeholder="<?=$this->lang->line('username');?>" required value="<?= set_value('username'); ?>"></p>

                        <p class=" confirm-msg"><?=$this->lang->line('mailaddress');?></p>
                        <p class=" confirm-input" ><input type="text" id="email" name="email" placeholder="<?=$this->lang->line('mailaddress');?>" required value="<?= set_value('email'); ?>"></p>

                        <p class=" confirm-msg"><?=$this->lang->line('password');?></p>
                        <p class=" confirm-input"><input type="password" id="password" name="password" placeholder="<?=$this->lang->line('password');?>" required value="<?= set_value('password'); ?>"></p>

                        <p class=" confirm-msg"><?=$this->lang->line('confirm_pass');?></p>
                        <p class=" confirm-input"><input type="password" id="password_confirm" name="password_confirm" placeholder="<?=$this->lang->line('confirm_pass');?>" required value="<?= set_value('password_confirm'); ?>"></p>

                        <p class=" confirm-msg"></p>
                        <p class=" confirm-input"><img src="<?php echo base_url() ?>captcha/generate" alt=""></p>

                        <p class=" confirm-msg"><?=$this->lang->line('authentication');?></p>
                        <p class=" confirm-input"><input type="text" required name="captcha"></p>
                    </div>
                    <?php if($this->session->userdata('lang')=='english'){?>
                        <p class="nrl">「<a class="policy-link" data-toggle="modal" data-target="#terms">dlog-cloud Terms of service</a>」read and 「Please agree and register as a user。</p>
                    <?php }else{?>
                        <p class="nrl"><input type="checkbox">「<a class="policy-link" data-toggle="modal" data-target="#terms">dlog-cloud 利用規約</a>」を読み、「同意してユーザー登録する」を押してください。</p>
                    <?php }?>
                    
                    <button id="update" class="confirm-btn"><?=$this->lang->line('agree_button');?></button>
                </div>
            </section>
            <?= form_close() ?>
        </div>
        <div id="terms" class="modal fade" role="dialog">
            <div class="modal-dialog">
          
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">サービス利用規約</h4>
                </div>
                <div class="modal-body">
                    <p>１．サービス内容</p>
                    <p>本サービスは下記の内容を無料で提供致します。</p>
                    <br>
                    <p>１ユーザー様あたり１００台のセンサーを登録可能です。</p>
                    <p>１ユーザー様あたり５，０００，０００件分のデーターを保存致します。</p>
                   <p> センサー１台、1 分間隔で保存した場合、３，０００日分に相当します。</p>
                    <p>（2020 年１１月１日付）</p>
                    <br>
                    <p>上記保存件数を超えた場合は、古いデーターから上書きされます消える前に PDF や CSV データーとしてお客様の PC に保存する事も出来ます。</p>
                    <br><br>
                    <p>２．サービス規約</p>
                    <p>① 利用規約の適用</p>
                    <p>この利用規約は、サービス利用者が、株式会社カスタム（以下「当社」という）が提供するクラウドサービスたる本サービスを利用するための条件、並びにサービス利用者と当社の間の法律関係を定めることを目的とする。サービス利用者は、利用規約を遵守して本サービスを利用するものとし、利用規約に同意できない場合、本サービスの利用はできない。本規約とサービスの細目に齟齬、矛盾がある場合は、特定の定めのない限りサービスの細目が優先される。規約は利用者の承諾なく変更する事があり、この場合事前に相当期間をおいてWeb サイト上及び登録されたメールアドレスを使用してサービス利用者に説明する。</p>
                    <br>
                    <p>② 用語の定義</p>
                    <p>利用規約における以下の用語は、別段の記載がない限り、以下の意味を持つ。「本サービス」ネットワークを通じて当社が提供する WEB のサービスの名称を言う。</p>
                    <p>「サービス利用者」</p>
                    <p>利用規約に同意し、当社との間で本サービスの利用に関する契約を締結した法人又は団体、個人をいう。</p>
                   <p> 「利用契約」</p>
                    <p>本サービスの利用についてサービス利用者と当社との間で締結した契約をいう。</p>
                    <br>
                    <p>③ 契約者の資格</p>
                    <p>利用契約を締結して本サービスを利用できるのは、日本国内の法人又は団体、個人とし、当社が本サービスの利用を適当と認めた方とする。</p>
                    <p>④ 利用の申込</p>
                    <p> 本サービスの利用を希望する法人又は団体、個人は、当社が指定するウェブサイト上の申込フォームに必要事項を入力して、申込をするものとする。当社は、利用規約及び当社の基準にそって、前項の申込を審査し、本サービスの利用を承諾に基づいて開始するものとする。</p>
                    <br>
                    <p>⑤ 利用契約の謝絶</p>
                    <p>申込者が以下の各号のいずれかの事由に該当する場合、本サービスの利用を謝絶することがある。</p>
                    <p>この場合、当社は、申込を謝絶する理由について開示する義務を負わない。</p>
                    <p>（１）申込者が日本国内に拠点を持たない場合、又は実在しない法人若しくは団体である場合。</p>
                    <p>（２）過去に、利用規約又は当社との他の契約に違反したことがある場合。</p>
                    <p>（３）反社会的勢力に該当し、又はその疑いがある場合。</p>
                    <p>（４）サービス利用規約（変更時も含む）に同意しない場合。</p>
                    <p>（５）その他、当社が、利用契約の締結が適切でないと判断した場合。</p>
                    <br>
                   <p> ⑥ 利用期間・解約</p>
                    <p>利用契約の期間は無期限とし、サービス利用者は当社が定める手続きに従いいつでも解約する事が出来る。</p>
                    <p>また最終ログイン後に１年以上が経過したユーザーは契約時に登録したメールアドレスに通知を行い更に６ヶ月間連絡が無かった場合継続の意思が無い物とし、登録を解除出来る。</p>
                    <p>登録解除の際は、ログインを停止し、本サービスの保有する情報の一切を消去するものとする。</p>
                    <p>⑦ 認証情報の管理</p>
                    <p>サービス利用者は、利用申し込み時に作成した ID・パスワード等の認証情報を、関係者以外の第三者に開示、貸与若しくは共有してはならない。</p>
                    <p>また、認証情報の漏洩や紛失が生じないよう厳重に管理しなければならない。</p>
                    <p>認証情報の漏洩、紛失、により不正利用が第三者によってなされた場合も、当社は何ら責任を負わない。</p>
                    <br>
                    <p>⑧ 知的財産権の帰属</p>
                    <p>本サービスにおいて当社が使用する、プログラム、データベース、レイアウト、並びに、画像、映像、文章及び他のコンテンツに関する著作権、特許権、ノウハウ、及び他一切の知的財産権は、当社に帰属する。</p>
                    <p>サービス利用者は、本サービスの本来の用途に従って、本サービスのユーザーとしての通常の方法によってのみ、前項の知的財産を利用することができ、いかなる方法でも、当社の許諾を得ずに、これらを複製、解析はできない。</p>
                    <br>
                    <p>⑨ 保存情報の管理</p>
                    <p>保存情報の維持管理はサービス利用者の責任でこれを行うものとする。</p>
                    <p>当社は保存情報に対して破損、消失、コンピュータウィルス感染等の対策について最善の注意を払いサービスの提供を行うが、完全な防止を保証するものではない。</p>
                    <br>
                   <p> ⑩ サービスの提供停止、変更、終了</p>
                    <p>下記の事由により本サービスを一時的に或いは永久的に停止する場合がある。</p>
                    <p>（１）設備の工事（メンテナンスを含む）などやむを得ない場合。</p>
                    <p>（２）設備にやむを得ない障害が生じた場合。</p>
                    <p>（３）災害等非常事態による本サービスの継続が困難な場合。</p>
                    <p>（４）その他当社が継続困難と判断した場合。</p>
                    <br>
                   <p> ⑪ 免責事項</p>
                    <p>サービス利用者が本サービスおよび本サービスを通じて他のネットワークサービスを利用することにより発生した一切の損害について、当社は契約者に対し何らの責任を負わない。</p>
                    <p>当社は本サービスの利用を通じてサービス利用者が得る情報について、その完全性、正確性、適用性、有用性等いかなる保証も行わない。</p>
                    <p>当社は、当社のシステム内に保管された契約者の個別ファイルについて何らの責任を負わない。</p>
                    <p>当社とサービス利用者間の本利用契約が効力を失った後、当社はその元契約者の個別ファイルを削除しうるものとする。</p>
                    <p>当社の故意または重過失を除き当社の責に帰すべき事由によりサービス利用者に損害が発生した場合の賠償の上限はサービス利用者が当社に支払った製品の金額を上限とする。</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                </div>
              </div>
          
            </div>
          </div>
    </div>
</body>
<script>

</script>

</html>
