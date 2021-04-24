
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
    
    <!-- custom style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/fileinput/css/fileinputbase.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/layout_tablet.css" media="screen and (min-width: 769px)" type="text/css">

    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_mobile.css" media="screen and (max-width: 768px)" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/menu_tablet.css" media="screen and (min-width: 769px)" type="text/css">
    

    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link href="<?php echo base_url()?>assets/fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
    <link href="<?php echo base_url()?>assets/fileinput/themes/explorer-fas/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> -->
    <script src="<?php echo base_url()?>assets/fileinput/js/plugins/piexif.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>assets/fileinput/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>assets/fileinput/js/fileinput.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>assets/fileinput/js/locales/ja.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>assets/fileinput/themes/fas/theme.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>assets/fileinput/themes/explorer-fas/theme.js" type="text/javascript"></script>
    <style>
        
        label.custom {
            background-color: #2A3A62;
            color: white;
            font-weight: 400;
            text-align: center;
            padding: 6px 10px;
            font-family: sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            border-radius: 4px;
        }
        #file-chosen{
            margin-left: 0.3rem;
            font-family: sans-serif;
            font-size:15px;
            }
        .center{
            position: absolute;
            top: 50%;
            right: 50%;
            transform: translate(50%,-50%);
            color: white;
        } 
        </style>
    <script>
       
    $( function() {
        $( "#sortableMap" ).sortable();
        $( "#sortableMap" ).disableSelection();
        var isDragging = false;
        $(".mapping-block")
        .mousedown(function() {
            isDragging = false;
        })
        .mousemove(function() {
            isDragging = true;
        })
        .mouseup(function() {
            var wasDragging = isDragging;
            if (wasDragging) {
                mapDecide();
            }
        });
    } );
 
    function eidtMap(){
        var data=[];var count=0;var mapId='';var mapName='';
        $('#sortableMap').children('div').each(function(index){
            var check=$(this).find('input').prop('checked');
            if(!check){
                return;
            }
            count++;
            if(count>1){
                alert('一度二つ以上編集することができません。');
                return;
            }
            mapId=$(this).attr('id');
            mapName=$.trim($(this).text());
        });
        if(count==0){
            alert('編集するマップを選択します。');
            return;
        }
        
        if(mapId){
            mapId=mapId.substring(3);
            location.href = "<?php echo base_url()?>setting/mappingmanagement/edit?mapId="+mapId+"&mapName="+mapName;
        }else{
            location.href = "<?php echo base_url()?>setting/mappingmanagement/add";
        }
        
    }
    function mapDecide(){
        var data=[];
            $('#sortableMap').children('div').each(function(index){
                var id = $(this).attr('id');
                if(id){
                    mapId=id.substring(3);
                    data.push([mapId,index]);
                }
            });
           $.ajax({
                url:"<?php echo base_url()?>setting/mappingManagement/mapDecide",
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
    function deleteMap(){
        var data=[];
        $('#sortableMap').children('div').each(function(index){
            var check=$(this).find('input').prop('checked');
            if(!check)return;
            var mapName=$(this).attr('id');
            if(mapName){
                mapName=mapName.substring(3);
                data.push(mapName);
            }
            
        });
        $.ajax({
            url:"<?php echo base_url()?>setting/mappingManagement/deleteMap",
            type:'post',
            data:{'data':data},
            success:function(responce){
                if(responce){
                    alert("成功裏に変更されました。");
                    location.href = "<?php echo base_url()?>setting/mappingmanagement";
                }
                else alert("失敗しました。");
            }
        })
    }
  </script>
</head>

<body id="pg_index" class="pg_index mapping-setting">
    <div class="pg-header flexlyr">
        <a href="<?php echo base_url()?>home" class="logo-icon"><img src="<?php echo base_url()?>assets/img/asset_01.png" alt=""></a>
        <div class="user-infor flexlyr">
            <?php if($this->role!=="admin"){?>
            <a href="<?php echo base_url()?>alarmhistory" class="user-comment"><img src="<?php echo base_url()?>assets/img/asset_08.png" alt="">
                <?php if($unread<1){?><p style="display:none;"><?php echo $unread;?></p><?php }else{?><p><?php echo $unread;?></p><?php }?>
            </a>
            <?php }else{?>
            <a href="<?php echo base_url()?>message/inbox" class="user-comment"><img src="<?php echo base_url()?>assets/img/asset_08.png" alt="">
                <?php if($unread<1){?><p style="display:none;"><?php echo $unread;?></p><?php }else{?><p><?php echo $unread;?></p><?php }?>
            </a>
            <?php }?>
            <a href="<?php echo base_url()?>User/logout" class="user-name"><img src="<?php echo base_url()?>assets/img/asset_02.png" alt=""><span><?php echo $user_name;?></span></a>
        </div>
    </div>
    <div class="wrapper">
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title">マッピング管理</h1>
            <p class="nrl1">マッピング監視表示されるマップを管理します。</p>
            <div class="mapping-content flexlyr" >
                <div class="mapping-grid setting-grid" >
                    <p class="grid-label mapping-label">マップ</p>
                    <div id="sortableMap">
                        <?php 
                            echo $mapName;
                        ?>
                       
                    </div>
                    <div class="operation-block flexlyr">
                        <a href="mappingManagement/add"  class="add-btn"></a>
                        <a  onclick="eidtMap()" class="edit-btn"></a>
                        <a  onclick="deleteMap()" class="del-btn"></a>
                    </div>
                </div>
                <div class="mapping-map setting-grid">
                    <p class="map-name" id="map_name">名称未設定</p>
                    <div class="center">
                        <div> <img src="<?php echo base_url();?>assets/img/asset_32.png"id="map_url"  onclick="upload()"> </div><br>
                        <div style="color:black;">地図の画像をアップロードする</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url();?>home" class="confirm-btn">ホームに戻る</a>
        </div>
        
    </div>
</div>
<div id="uploadimageModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h1 class="modal-title" style="font-size: 25px;">地図編集</h1>
                </div>
                <div class="modal-body">
                <input id="mapImage" name="mapImage" type="file">
                    
                </div>
            </div>
        </div>
    </div>
</body>

<script>
     var mapname="";
      $('.mapping-block').click(function () {
            $('.mapping-block').css('background-color','white');
            $(this).css('background-color','darkseagreen');
            var name=$.trim($(this).text());
            mapname=$('#map_name').text(name);
      })
      function upload(){
            mapname=$('#map_name').text();
            if(mapname=="名称未設定"){
                alert("編集するマップを選択します。");
                return;
            }
            $("#uploadimageModal").modal('show');
         
      }
      $("#mapImage").fileinput({
        theme: 'fas',
        language: 'ja',
        uploadUrl: '<?php echo base_url()?>setting/mappingmanagement/uploadImage',
        uploadExtraData: function (previewId, index) {
            var info = {"mapname": $("#map_name").text()};
            return info;
          }
    })
     
</script>
</html>




