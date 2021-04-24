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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css" />
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
        .editimage{
            position: absolute;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        } 
        </style>
    <script>
       var mapID='';
       var map_url='';
       var map_name='';
    function editMap(id){
        mapID=id;
        $.ajax({
               url:"MappingManagement/getMap",
                type:'post',
                data:{
                    'mapid': mapID
                },
                success:function(responce){
                    res=JSON.parse(responce);
                   if(res){
                        map_url=res.imageurl;
                        map_name=res.name;
                        // $('#upload_image').val(map_url);
                        $('#mapname').val(map_name);
                        $("#currentMap").text(mapID);
                        $('#uploadimageModal').modal('show');
                        $image_crop.croppie('bind', {
                            url: map_url
                        }).then(function(){
                            console.log('jQuery bind complete');
                        });
                   }else{
                    alert('編集できません。');
                   }
                }
                
           })
        
    }
    function addMap(){
        map_name='';
        $('#currentMap').text("");
        $('#mapname').val(map_name);
        map_url='<?php echo base_url();?>assets/upload/testRe/map_1614226602.png';
        $('#uploadimageModal').modal('show');
        $image_crop.croppie('bind', {
            url: map_url
        }).then(function(){
            console.log('jQuery bind complete');
        });
    }
    function mapDecide(){
        var data=[];
            $('#sortableMap').find('div').each(function(index){
                var id = $(this).attr('id');
                mapId=id.substring(3);
                data.push([mapId,index]);
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
            $('#sortableMap').find('div').each(function(index){
                var check=$(this).find('input').prop('checked');
                if(!check)return;
                var mapName=$(this).attr('id');
                mapName=mapName.substring(3);
                data.push(mapName);
            });
           $.ajax({
                url:"<?php echo base_url()?>setting/mappingManagement/deleteMap",
                type:'post',
                data:{'data':data},
                success:function(responce){
                    if(responce){
                        alert("成功裏に変更されました。");
                        $.ajax({
                            url:"<?php echo base_url()?>Setting/MappingManagement/mapList",
                            type:'get',
                            success:function(responce){
                                res=JSON.parse(responce);
                                if(res){
                                    $('#sortableMap').replaceWith(res);
                                    $( "#sortableMap" ).sortable();
                                    $( "#sortableMap" ).disableSelection();
                                }
                                else alert("失敗しました。");
                            }
                            
                        })
                    }
                    else alert("失敗しました。");
                }
                
           })
    }
    
    function currentEditMap(){
        map_name=$('#map_name').text();
        $('#mapname').val(map_name);
        map_url=$('#map_url').attr('src');
        $('#uploadimageModal').modal('show');
        mapID=$('#currentMap').text();
        $image_crop.croppie('bind', {
            url: map_url
        }).then(function(){
            console.log('jQuery bind complete');
        });
    }
    $( function() {
        $( "#sortableMap" ).sortable();
        $( "#sortableMap" ).disableSelection();
    } );
  </script>
</head>

<body id="pg_index" class="pg_index mapping-setting">
    
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
                            if(empty($mapName)){?>
                                <div class="mapping-block flexlyr">
                                <label class="container1">
                                </label>
                                <p class="mapping-name">地図なし</p>
                            </div>
                           <?php }else{
                                echo $mapName;
                            }
                        ?>
                        
                    </div>
                    <div class="operation-block flexlyr">
                        <a  onclick="addMap()" class="add-btn"></a>
                        <a  onclick="mapDecide()" class="text-btn" style="font-size:15px;">決定</a>
                        <a  onclick="deleteMap()" class="del-btn"></a>
                    </div>
                </div>
                <div class="mapping-map setting-grid">
                    <div id="editImage">
                        <p class="map-name" id="map_name"><?php echo $firstMap;?></p>
                        <div class="editimage">
                        <img src="<?php echo $map_url;?>" alt="" id="map_url"> </div>
                    </div>
                    <div style="display:none;" id="currentMap"><?php echo $ID;?></div>
                    <a  onclick="currentEditMap()" class="edit-btn"></a>
                </div>
            </div>
            <a href="<?php echo base_url();?>home" class="confirm-btn">ホームに戻る</a>
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
                    <div class="row">
                        <div class="col-md-12" style="display:flex;font-size:18px;margin-bottom:10px">
                            <div class="col-md-3" style="padding-top:10px;">地図名</div>
                            <div class="col-md-8"><input type="text" class="form-control" id="mapname"></div>
                        </div>
                        <div class="text-center">
                            <div id="image_demo"></div>
                        </div>
                        <div >
                        <div id="errormsg" style="font-size:16px;display:none;">ファイルがない</div><br>                              
                            <br />
                            <div id="uploaded_image" ></div>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="file" id="upload_image" style="display:none;" />
                                    <label class="custom" for="upload_image">マップ選択</label>
                                    <span id="file-chosen">ファイルがない</span>
                                </div>
                                <div>
                                    <button class="btn btn-success crop_image col-md-6" style="width:200px;margin-left: 65px;background-color: #2A3A62;">地図をアップロード</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script>  
   
$(document).ready(function(){
    
	$image_crop = $('#image_demo').croppie({
    url: '<?php echo base_url();?>assets/img/temp_map.png',
    showZoomer: false,
    enableResize: true,  
    viewport: {
      width:470,
      height:230,
      type:'square' //circle
    },
    boundary:{
      width:470,
      height:230
    },
    enableResize: true,
    mouseWheelZoom: 'ctrl'
  });
  
  const actualBtn = document.getElementById('upload_image');

        const fileChosen = document.getElementById('file-chosen');
    if(actualBtn){
        actualBtn.addEventListener('change', function(){
            fileChosen.textContent = this.files[0].name
        })
    }
  $('#upload_image').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
  });

  $('.crop_image').click(function(event){
    var mapname=$("#mapname").val();
    if(mapname==''){alert('マップ名を入力してください。');return;}
    if ($("#upload_image")[0].files[0] && $("#upload_image")[0].files[0]) {
     
      var mapID=$("#currentMap").text();
        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'original',
            }).then(function(response){
            $.ajax({
                url:"MappingManagement/uploadImage",
                type: "post",
                dataType: "text",
                data:{"map": response,"mapname":mapname,'mapid':mapID},
                success:function(res){
                    // res=JSON.parse(responce);
                    if(res==false) alert("アップロードに失敗しました");
                    else alert("アップロードに成功しました");
                    $('#uploadimageModal').modal('hide');
                    $('#editImage').replaceWith(res);
                    $.ajax({
                        url:"<?php echo base_url()?>Setting/MappingManagement/mapList",
                        type:'get',
                        success:function(responce){
                            res=JSON.parse(responce);
                            if(res){
                                $('#sortableMap').replaceWith(res);
                                $( "#sortableMap" ).sortable();
                                $( "#sortableMap" ).disableSelection();
                            }
                            else alert("失敗しました。");
                        }
                        
                    })
                }
            });
        })
    } else {
        alert('ファイルを追加してください。');
  }
  });

});  
</script>
</body>

</html>




