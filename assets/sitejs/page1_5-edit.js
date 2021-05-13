/* page1_5-edit */

var typeidback = "";
function dbackup(id){
    typeidback = id.value;
}
function imeiChenge() {
    var typename = document.getElementById('TypeName');
    var imei = document.getElementById('IMEI');
    var type_id = document.getElementById('TypeID');
    var dobj = JSON.stringify(jdata);
    var imei_str = imei.value.trim();
    if(dobj!=="null"){
        dobj = JSON.parse(dobj);
    
        for (var i = 0; i < dobj.length; i++) {
            var jd = JSON.stringify(dobj[i]);
            jd = JSON.parse(jd);
            if (jd.IMEI.trim() == imei_str) {
                alert(serial_check_str);
                typename.textContent = "";
                return;
            }
        }
    }
    
    
    var s_type_name = "";
    var s_type_id = 0;
    // 検証文字数以外は旧センサー扱い
    if (imei_str.length == 12) {
        var type_no = imei_str.substr(5, 1);
        switch (type_no) {
            case '0':
                s_type_name = temperature_str;
                s_type_id = 18;
                break;
            case '1':
                s_type_name = temperature_humidity_str;
                s_type_id = 19;
                break;
            default:
                break;
        } 
    } else if (imei_str.length == 8) {
        var type_no = imei_str.substr(1, 1);
        switch (type_no) {
            case '1':
                s_type_name = rf_temperature_str;
                s_type_id = 8;
                break;
            case '2':
                s_type_name = rf_temperature_humidity_str;
                s_type_id = 9;
                break;
            case 'A':
                s_type_name = lora_temperature_str;
                s_type_id = 16;
                break;
            case 'B':
                s_type_name = lora_temperature_humidity_str;
                s_type_id = 17;
                break;
            default:
                break;
        } 
    } else {
        // 旧センサーの場合は別途typeidを設定し直す必要あり。
        s_type_name = old_sensor_str;
        s_type_id = 0;
    }
    typename.textContent = s_type_name;
    type_id.value = s_type_id;
}

$(function(){
    $("input"). keydown(function(e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            return false;
        } else {
            return true;
        }
    });
});
function dataCheck(){
    //var type_id = document.getElementById('TypeID');
    var group_id = document.getElementById('GroupID');
    /* if (type_id.value == "0") {
        alert('シリアル番号が存在しません！！');
        type_id.focus();
        return false;
    } */
    if (group_id.value == "0") {
        alert(group_name_msg);
        group_id.focus();
        return false;
    }
}
