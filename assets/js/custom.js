// html dom is done
$( window ).on( "load", function() {
    // menu bg color setting by page url
    // var cs1 = $(".pg_index").attr("class").split(" ");
    // console.log( cs1[1] );
    switch ( $(".pg_index").attr("class").split(" ")[1] ) {
        case 'home':            //<?php echo base_url();?>
            $(".menu0").addClass('select');
            break;

        case 'user_setting':        //page1_1.php
            $('#pageSubmenu').collapse("show");
            $(".menu1_1").addClass('select');
            break;
        case 'system_setting':      //page1_2.php
            $('#pageSubmenu').collapse("show");
            $(".menu1_2").addClass('select');
            break;
        case 'sub-account-list':    //page1_3.php
            $('#pageSubmenu').collapse("show");
            $(".menu1_3").addClass('select');
            break;
        case 'group-setting':       //page1_4.php
            $('#pageSubmenu').collapse("show");
            $(".menu1_4").addClass('select');
            break;
        case 'sensor_setting':      //page1_5.php
            $('#pageSubmenu').collapse("show");
            $(".menu1_5").addClass('select');
            break;
        case 'gateway_setting':     //page1_6.php
            $('#pageSubmenu').collapse("show");
            $(".menu1_6").addClass('select');
            break;
        case 'mapping-setting':     //page1_7.php
            $('#pageSubmenu').collapse("show");
            $(".menu1_7").addClass('select');
            break;
        case 'list-setting':        //page1_8.php
            $('#pageSubmenu').collapse("show");
            $(".menu1_8").addClass('select');
            break;


            
        case 'sensor-monitor':      //page2.php
            $(".menu2").addClass('select');
            break;
        case "mapping":             //page3.php
            $(".menu3").addClass('select');
            break;
        case 'alarm-history':       //page4.php
            $(".menu4").addClass('select');
            break;



        case 'message-admin':       //page5_1.php
            $('#pageSubmenu2').collapse("show");
            $(".menu5_1").addClass('select');
            break;
        case 'message-receive':     //page5_2.php
            $('#pageSubmenu2').collapse("show");
            $(".menu5_2").addClass('select');
            break;
        case 'message-send':        //page5_3.php
            $('#pageSubmenu2').collapse("show");
            $(".menu5_3").addClass('select');
            break;
        case 'message-type':        //page5_4.php
            $('#pageSubmenu2').collapse("show");
            $(".menu5_4").addClass('select');
            break;



        case 'report':              //page6_1.php
            $('#pageSubmenu1').collapse("show");
            $(".menu6_1").addClass('select');
            break;
        case 'graph':               //page6_2.php
            $('#pageSubmenu1').collapse("show");
            $(".menu6_2").addClass('select');
            break;
        case 'graph-compare':        //page6_3.php
            $('#pageSubmenu1').collapse("show");
            $(".menu6_3").addClass('select');
            break;
    }
});

// html dom is done
$(document).ready(function () {
    // hambugar button click
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    // menu click
    $("[href]").on('click', function () {
        $("li").removeClass('select');
        $("li").removeClass('subOpen');
        $(this).parent().addClass('select');

        if ( $(this).next().hasClass( "collapse" ) ) {      //click collapse menu
            
            if( $(this).attr('aria-expanded') == "true" ) {
                // when collapse menu is open
                $(this).parent().removeClass('subOpen');
            } else {
                // when collapse menu is close,
                $(this).parent().addClass('subOpen');
            }
            
        } else if ( $(this).parent().parent().hasClass( "collapse" ) ) {    //if click sub menu of collapse,
            $('.collapseItem').addClass('subOpen');
        } else {    //click other 5 buttons
            $('.collapse').collapse("hide");
        }
        // console.log( "=======" );
        // console.log( $(this).attr('aria-expanded') );
    });




    // click search icon
    //変数定義
    var navigationOpenFlag = false;
    var navButtonFlag = true;
    var focusFlag = false;
    //ハンバーガーメニュー
    $(function(){
    
        $(document).on('click','#searchIcon',function(){
            if(navButtonFlag){
            spNavInOut.switch();
            //一時的にボタンを押せなくする
            setTimeout(function(){
                navButtonFlag = true;
            },200);
                navButtonFlag = false;
            }
        });
        $(document).on('click touchend', function(event) {
            if (!$(event.target).closest('.searchTxt').length && $('.search-box').hasClass('searchOpen') && focusFlag) {
                focusFlag = false;
                //scrollBlocker(false);
                spNavInOut.switch();
            }
        });
    });
    
    //ナビ開く処理
    function spNavIn(){
        // $('.search-box').removeClass('js_humburgerClose');
        $('.search-box').addClass('searchOpen');
        setTimeout(function(){
            focusFlag = true;
        },200);
        setTimeout(function(){
            navigationOpenFlag = true;
        },200);
    }
    
    //ナビ閉じる処理
    function spNavOut(){
        $('.search-box').removeClass('searchOpen');
        // $('.search-box').addClass('js_humburgerClose');
        setTimeout(function(){
            focusFlag = false;
        },200);
        navigationOpenFlag = false;
    }
    
    //ナビ開閉コントロール
    var spNavInOut = {
        switch:function(){
            if($('.search-box.spNavFreez').length){
                return false;
            }
            if($('.search-box').hasClass('searchOpen')){
                spNavOut();
            } else {
                spNavIn();
            }
        }
    };


    


    
    // mapping page - plus icon click
    $('#plus').click(function () {
        var classNames = $("#map-layer").attr("class").toString().split(' ');
        $.each(classNames, function (i, className) {
            // console.log( className );
            if ( className == 'zoom0' ) {
                $('#map-layer').removeClass('zoom0');
                $('#map-layer').addClass('zoom1');
            } else if ( className == 'zoom1' ) {
                $('#map-layer').removeClass('zoom1');
                $('#map-layer').addClass('zoom2');
            } else if ( className == 'zoom2' ) {
                $('#map-layer').removeClass('zoom2');
                $('#map-layer').addClass('zoom3');
            } else if ( className == 'zoom3' ) {
                $('#map-layer').removeClass('zoom3');
                $('#map-layer').addClass('zoom4');
            } else if ( className == 'zoom4' ) {
                $('#map-layer').removeClass('zoom4');
                $('#map-layer').addClass('zoom5');
            } else if ( className == 'zoom5' ) {
                $('#max-zoom').toast('show');
            }
        });

    })

    // mapping page - minus icon click
    $('#minus').click(function () {
        var classNames = $("#map-layer").attr("class").toString().split(' ');
        $.each(classNames, function (i, className) {
            // console.log( className );
            if ( className == 'zoom0' ) {
                $('#min-zoom').toast('show');
            } else if ( className == 'zoom1' ) {
                $('#map-layer').removeClass('zoom1');
                $('#map-layer').addClass('zoom0');
            } else if ( className == 'zoom2' ) {
                $('#map-layer').removeClass('zoom2');
                $('#map-layer').addClass('zoom1');
            } else if ( className == 'zoom3' ) {
                $('#map-layer').removeClass('zoom3');
                $('#map-layer').addClass('zoom2');
            } else if ( className == 'zoom4' ) {
                $('#map-layer').removeClass('zoom4');
                $('#map-layer').addClass('zoom3');
            } else if ( className == 'zoom5' ) {
                $('#map-layer').removeClass('zoom5');
                $('#map-layer').addClass('zoom4');
            }
        });

    })


    // mapping page - time line seek bar
    $(".time-ctrl").change(function (e) {
        var value = e.target.value;
        $(".time-bar").val(value);
    });


   
});




