
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
    
    .confirm-btn{
        outline: none !important;
        height:3rem;
    }
</style>
<body id="pg_index" class="pg_index message-admin">
    
    
    <div class="wrapper">
        
        
        <!-- Sidebar  -->
        <?php $this->load->view('menu'); ?>
    
        <!-- Page Content  -->
        <div class="content">
            <h1 class="page-title"><?=$this->lang->line('outgoing_title');?></h1>
            <section class="main-content ">

                <div class="content-grid">
                    
                    <div class="admin-send-block flexlyr">
                        <p class=" confirm-msg"><?=$this->lang->line('sender');?></p>
                        <p class=" confirm-input">
                            <input type="text" value="<?=$this->lang->line('system');?>" disabled="disabled">
                        </p>
                        <p class=" confirm-msg"><?=$this->lang->line('message');?></p>
                        <p class=" confirm-input">
                            <textarea name="" id="message" cols="30" rows="10"></textarea>
                        </p>
                        <p id="warning" class="text-danger text-center"></p>
                        
                        <button class="confirm-btn" onclick="sendMessage()"><?=$this->lang->line('send');?></button>
                    </div>
                </div>
            </section>
            <div class="pg-footer">
                <p class="footer-label">©︎2020 -  CUSTOM corporation</p>
            </div>
        </div>
    </div>
<script>
    function sendMessage(){
        var message=$('#message').val();
        if(message=="") {
            alert('<?=$this->lang->line('empty_input');?>');
            return;
        }
        $.ajax({
            url: 'Outgoing/recordMessage',
            type: 'post',
            data: {
                message:message
            },
            dataType: 'json',
            success: function(res) {
                if(res==1){
                    alert('<?=$this->lang->line('success');?>');
                    $('#message').val("");
                }else{
                    alert('<?=$this->lang->line('fail');?>');
                }
               
            }
            
        });
    }
</script>
   
</body>


</html>