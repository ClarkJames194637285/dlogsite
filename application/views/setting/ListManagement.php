<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>BSCM</title>
    <!-- viewport setting -->
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
    <!-- slider, preloader style -->
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/loaders.css" type="text/css">


    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <!-- BootStrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <!-- custom style -->
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/layout_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/layout_tablet.css" media="screen and (min-width: 769px)" type="text/css">

    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/menu_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/menu_tablet.css" media="screen and (min-width: 769px)" type="text/css">
    

    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>/assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>/assets/js/wow.min.js"></script>
    
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
    $( function() {
        $( "#sortableGroup" ).sortable();
        $( "#sortableGroup" ).disableSelection();
        $( "#sortableSensor" ).sortable();
        $( "#sortableSensor" ).disableSelection();
    } );
  </script>
 
</head>

<body id="pg_index" class="pg_index list-setting">
    <div class="pg-header flexlyr">
        <a href="<?php echo base_url()?>home" class="logo-icon"><img src="<?php echo base_url()?>assets/img/asset_01.png" alt=""></a>
        <div class="user-infor flexlyr">
            <a href="<?php echo base_url()?>Message/inbox" class="user-comment"><img src="<?php echo base_url()?>assets/img/asset_08.png" alt="">
                <?php if($unread<1){?><p style="display:none;"><?php echo $unread;?></p><?php }else{?><p><?php echo $unread;?></p><?php }?>
            </a>
            <a href="<?php echo base_url()?>User/logout" class="user-name"><img src="<?php echo base_url()?>assets/img/asset_02.png" alt=""><span><?php echo $user_name;?></span></a>
        </div>
    </div>
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title">リスト管理</h1>
            <p class="nrl1">フィルター選択時などで表示されるリストの順番を管理します。</p>
            <div class="list-content flexlyr">
                <div class="list1-grid setting-grid">
                    <p class="grid-label list1-label">グループ</p>
                    <div id="sortableGroup">
                        <?php echo $groupName;?>
                    </div>
                    <div class="operation-block flexlyr">
                        <a  onclick="groupUndo()" class="text-btn">元に戻す</a>
                        <a  onclick="groupDecide()" class="text-btn">決定</a>
                    </div>
                   
                </div>
                <div class="list2-grid setting-grid">
                    <p class="grid-label list2-label">センサー</p>
                    
                    <div id="sortableSensor">
                        <?php echo $sensorName;?>
                    </div>
                    

                    <div class="operation-block flexlyr">
                        <a  onclick="sensorUndo()" class="text-btn">元に戻す</a>
                        <a  onclick="sensorDecide()" class="text-btn">決定</a>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url()?>home" class="confirm-btn">ホームに戻る</a>
        </div>
    </div>
    <script>
        function groupUndo(){
           $.ajax({
                url:"<?php echo base_url()?>Setting/ListManagement/groupUndo",
                type:'get',
                success:function(responce){
                    res=JSON.parse(responce);
                    if(res){
                        $('#sortableGroup').replaceWith(res);
                        $( "#sortableGroup" ).sortable();
                        $( "#sortableGroup" ).disableSelection();
                    }
                    else alert("失敗しました。");
                }
                
           })
        }
        function groupDecide(){
            var data=[];
            $('#sortableGroup').find('div').each(function(index){
                var groupName = $(this).find('p').text();
                data.push([groupName,index]);
            });
           $.ajax({
                url:"<?php echo base_url()?>Setting/ListManagement/groupDecide",
                type:'post',
                data:{'data':data},
                success:function(responce){
                    if(responce){
                        alert("成功裏に変更されました。");
                    }
                    else alert("失敗しました。");
                }
                
           })
        }
        function sensorUndo(){
           $.ajax({
                url:"<?php echo base_url()?>Setting/ListManagement/sensorUndo",
                type:'get',
                success:function(responce){
                    res=JSON.parse(responce);
                    if(res){
                        $('#sortableSensor').replaceWith(res);
                        $( "#sortableSensor" ).sortable();
                        $( "#sortableSensor" ).disableSelection();
                    }
                    else alert("失敗しました。");
                }
                
           })
        }
        function sensorDecide(){
            var data=[];
            $('#sortableSensor').find('div').each(function(index){
                var groupName = $(this).find('p').text();
                data.push([groupName,index]);
            });
           $.ajax({
                url:"<?php echo base_url()?>Setting/ListManagement/sensorDecide",
                type:'post',
                data:{'data':data},
                success:function(responce){
                    if(responce){
                        alert("成功裏に変更されました。");
                    }
                    else alert("失敗しました。");
                }
                
           })
        }
    </script>
</body>

</html>




