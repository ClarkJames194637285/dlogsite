<style>
		
		.pagination {
			display: block !important;
            margin-left:auto;
            margin-right:auto;
            text-align:center;
		}
		.pagination a, .pagination strong {
			width: 30px;
			height: 30px;
			line-height: 30px;
			text-align: center;
			text-decoration: none;
			border-style: none;
			border-radius: 4px;
			margin: 1px;
			display: inline-block;
			color: white;
			font-weight: 600;
		}
		.pagination a {
			background-color: #5a6268;
		}
		.pagination a:hover {
			background-color: #6c757d;
		}
		.pagination strong {
			background-color: #6c757dbd;
		}
	</style>
    <!-- slider, preloader style -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/loaders.css" type="text/css">


    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> -->
    
    <!-- BootStrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- toast window -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    

    <!-- jquery dragable -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- img object fit -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/object-fit-images/3.2.3/ofi.js"></script>

    <!-- custom jscript -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/wow.min.js"></script>
</head>

<script>
    objectFitImages();
    $( function() {
        $( "#map-layer" ).draggable();
        $( ".senseor-icon" ).draggable().css("position", "absolute");
    } );
</script>
<style>
    .modal_font{
        font-size:20px;
    }
    .btn-size{
        width:150px;
        height:30px;
        margin:0px 30px;
        text-align:center;
    }
    .background{
        background: white;
        /* border: blue; */
        outline: #29292e;
        }
        .revise{
            justify-content:flex-start;
        }
        .cell1{
            width:5% !important;
        }
        .cell5{
            width:20% !important;
        }
        .cell2{
            width:70% !important;
        }
        .cell6{
            width:5% !important;
        }
        /* .none{
            display:none;
        } */
</style>
<body id="pg_index" class="pg_index message-send">
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php   $this->load->view('menu');?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('outbox_title');?></h1>
            <section class="main-content ">

                <div class="content-grid">
                    
                    <div class="message_grid">
                        <div class="grid-header flexlyr revise">
                            <div class="hd-cell cell1">
                                <label class="container1">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="hd-cell cell5"><?=$this->lang->line('time');?></div>
                            <div class="hd-cell cell2"><?=$this->lang->line('content');?></div>
                            <div class="hd-cell cell6"><?=$this->lang->line('operation');?></div>
                        </div>
                       
                        <?php $n=0;
                       foreach($OutboxMessage as $message){?>
                            <div class="grid-content flexlyr" id="message_<?php echo $message->ID;?>">
                                <div class="ct-cell cell1 none">
                                    <label class="container1">
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="ct-cell cell5"><?php $time=explode(' ',$message->CreateTime); echo $time[0].'  '.$time[1];?></div>
                                <div class="ct-cell cell2">
                                    <?php  
                                        echo $message->MessageContent;
                                    ?>
                                </div>
                                
                                <div class="ct-cell cell6">
                                    <button class="background" onclick="deleteMessage(<?php echo $message->ID;?>)"><img src="<?php echo base_url()?>assets/img/asset_38.png" alt="" title="削除"></button>
                                </div>
                            </div>
                        <?php $n++;}?>
                        <div class="operation-block">
                            <a href="" class="del-btn"  onclick="deleteall()"></a>
                            <!-- <a href="" class="all-view-btn" title="全て既読"></a> -->
                        </div>
                        <?php echo $links; ?>
                    </div>
                </div>

            </section>

            <div class="pg-footer">
                <p class="footer-label">©︎2020 -  CUSTOM corporation</p>
            </div>
        </div>
    </div>

    <script>
        function deleteMessage(id){
            $.ajax({
                    url: "<?php echo site_url('message/Outbox/delete')?>",
                    type: "post",
                    data:{id},
                    success: function (data) {
                        if(data==1){
                            alert('<?=$this->lang->line('delete_success');?>');
                            window.location.href="<?php echo site_url('Outbox/')?>"+<?php echo $page;?>;
                        }
                        else alert('<?=$this->lang->line('delete_fail');?>');
                    },
                    error: function (error) {
                        console.log(`Error ${error}`);
                    }
                });

        }
        function deleteall(){
            var check=$('#select-all').prop( "checked" );
            if(check){
                $.ajax({
                    url: "<?php echo current_url();?>",
                    type: "post",
                    data:{'delete':'true'},
                    success: function (data) {
                        if(data==1){
                            alert('<?=$this->lang->line('delete_success');?>');
                            window.location.href="<?php echo site_url('Outbox/')?>"+<?php echo $page;?>;
                        }
                        else alert('<?=$this->lang->line('delete_fail');?>');
                    },
                    error: function (error) {
                        console.log(`Error ${error}`);
                    }
                });

            }else{
                var data=[];
                var id='';
                $(".message_grid").children('.grid-content').each(function(index){
                    var check=$(this).find('input').prop( "checked" );
                    if(check){
                        var id=$(this).attr("id");
                        messageId=id.substring(8);
                        data.push([messageId,index]);
                    }
                })
                $.ajax({
                    url: "<?php echo current_url();?>/deleteMulti",
                    type: "post",
                    async: false,
                    data:{'data':data},
                    success: function (res) {
                        if(res==1){
                            alert('<?=$this->lang->line('delete_success');?>');
                            window.location.href="<?php echo site_url('Outbox/')?>"+<?php echo $page;?>;
                        }
                        else alert('<?=$this->lang->line('delete_fail');?>');
                    },
                    error: function (error) {
                        console.log(`Error ${error}`);
                    }
                });
                return 0;
            }
            
        }
        // Listen for click on toggle checkbox
        $('#select-all').click(function(event) {   
            if(this.checked) {
                // Iterate each checkbox
                $('.message_grid .grid-content').each(function() {
                    // this.find('.cell1>input').checked = true; 
                    this.children[0].children[0].children[0].checked=true;
                    // console.log(this.find('.cell1 .container1 input'));                       
                });
            } else {
                $('.message_grid .grid-content').each(function() {
                    this.children[0].children[0].children[0].checked=false;                 
                });
            }
        });
        // $('.grid-content input[type=checkbox]').click(function(event){
        //     if(this.checked){
        //         $('#select-all').attr("checked",true);
        //     }else{
        //         $('#select-all').attr("checked",false);
        //     }
            
        // })
    </script>  
</body>

</html>