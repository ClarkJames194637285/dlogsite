
<style>
  .choosefile{
    margin-bottom:10px;
    /* margin-left:-120px; */
    overflow:auto;
  }
  textarea{
    width:90vw;
  }
  @media screen and (min-width: 600px) {
    textarea{
    width:50vw;
  }
}
  </style>
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
  <script type="text/javascript">

    $(function()
    {
        $('#userfile').on('change',function ()
        {
          loadFileAsText();
        });
    });

</script>
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
            <h1 class="page-title"><?=$this->lang->line('advertisement');?></h1>
            <div class="publish-form">
              <div class="form-input">
                  <section class="main-content flexlyr">
                    <div class="content-grid">
                        <div class="register-block flexlyr">
                            <input type="hidden" name="name" value="value">
                            <div class="choosefile"><input type="file" id="userfile"></div>
                            <div class="textarea"><textarea id="inputTextToSave" style="height:256px"></textarea></div>       
                            <button class="confirm-btn" type="button" onclick="filesave();"><?=$this->lang->line('upload');?></button>
                        </div>
                      </div>
                    </section>
              </div>
            </div>
        </div>
        
        <div class="pg-footer">
            <p class="footer-label">©︎2020 - CUSTOM corporation</p>
        </div>
    <div>
    <script type="text/javascript">

    function loadFileAsText() {
      var fileToLoad = document.getElementById("userfile").files[0];

      var fileReader = new FileReader();
      fileReader.onload = function (fileLoadedEvent) {
        var textFromFileLoaded = fileLoadedEvent.target.result;
        document.getElementById("inputTextToSave").value = textFromFileLoaded;
      };
      fileReader.readAsText(fileToLoad, "UTF-8");
    }
    function filesave(){
      var fullPath = document.getElementById('userfile').value;
      if (fullPath) {
          var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
          var filename = fullPath.substring(startIndex);
          if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
              filename = filename.substring(1);
          }
      }
      var text=$('#inputTextToSave').val();
      if(text=="")return;
      $.ajax({
                url:"<?php echo base_url()?>setting/admin/advertisement/uploadtext",
                type:'post',
                data:{
                    'filename':filename,
                    'text':text
                },
                success:function(responce){
                    if(responce==true){
                        alert('正常にマップに登録されました。');
                        document.getElementById("inputTextToSave").value = "";
                        document.getElementById("userfile").value = "";
                    }
                    else {alert('マップに登録が失敗しました。');}
                }
            })
    }

  </script>
</body>

</html>