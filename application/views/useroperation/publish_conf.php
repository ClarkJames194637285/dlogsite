<link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">
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
      .title{
        margin:6vw;
        font-size:30px;
      }
  </style>

  <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> -->
  <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
  <!-- BootStrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

   <!-- custom jscript -->
  <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
  <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
<body id="pg_index" class="pg_index useroperation">
  <div class="wrapper">
        <?php $this->load->view('menu'); ?>
        
        <div class="content">
          <h1 class="page-title">アップロードの成功</h1>
          <section class="main-content flexlyr">
              <div class="content-grid">
                  <p class="title">アップロードに成功しました！</p>
                  <a href="<?php echo base_url();?>home" class="confirm-btn">ホーム画面を見てみる</a>
              </div>
          </section>
        </div>
  </div>
  </body>
</html>
