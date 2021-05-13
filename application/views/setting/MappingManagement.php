
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
    <?php
    $site_lang=$this->session->userdata('lang');
    if ($site_lang=='english') {?>
        <script src="<?php echo base_url()?>assets/fileinput/js/locales/en.js" type="text/javascript"></script>
        <?php } else { ?>
        <script src="<?php echo base_url()?>assets/fileinput/js/locales/ja.js" type="text/javascript"></script>
    <?php }
    ?>
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
        .mouseup(function(){
            var wasDragging = isDragging;
            if (wasDragging) {
                setTimeout(function(){
                    var data=[];
                    var id='';
                    document.querySelectorAll('#sortableMap div').forEach(function(node,index){
                        var id = node.getAttribute('id');
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
                                alert("<?=$this->lang->line('map_sort');?>");
                            }
                            else alert("<?=$this->lang->line('map_delete_fail');?>");
                        }
                        
                    })
                
                },1000);
            }
        })
    } );


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
                    alert("<?=$this->lang->line('map_delete');?>");
                    location.href = "<?php echo base_url()?>setting/mappingmanagement";
                }
                else alert("<?=$this->lang->line('map_delete_fail');?>");
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
            <h1 class="page-title"><?=$this->lang->line('map_title');?></h1>
            <p class="nrl1"><?=$this->lang->line('map_description');?></p>
            <div class="mapping-content flexlyr" >
                <div class="mapping-grid setting-grid" >
                    <p class="grid-label mapping-label"><?=$this->lang->line('map');?></p>
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
                    <p class="map-name" id="map_name"><?=$this->lang->line('untitled');?></p>
                    <div class="center">
                        <div> <img src="<?php echo base_url();?>assets/img/asset_32.png"id="map_url"  onclick="upload()"> </div><br>
                        <div style="color:black;"><?=$this->lang->line('map_upload');?></div>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url();?>home" class="confirm-btn"><?=$this->lang->line('home');?></a>
        </div>
        
    </div>
</div>
<div id="uploadimageModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h1 class="modal-title" style="font-size: 25px;"><?=$this->lang->line('map_edit');?></h1>
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
            if(mapname=="<?=$this->lang->line('untitled');?>"){
                alert("<?=$this->lang->line('map_select');?>");
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




