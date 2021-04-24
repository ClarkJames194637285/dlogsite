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
    dobj = JSON.parse(dobj);
    var imei_str = imei.value.trim();
    for (var i = 0; i < dobj.length; i++) {
        var jd = JSON.stringify(dobj[i]);
        jd = JSON.parse(jd);
        if (jd.IMEI.trim() == imei_str) {
            alert("シリアル番号はすでに登録済みです！");
            typename.textContent = "";
            return;
        }
    }
    
    var s_type_name = "";
    if (imei_str.length == 12) {
        var type_no = imei_str.substr(5, 1);
        switch (type_no) {
            case '0':
                s_type_name = "温度センサー";
                break;
            case '1':
                s_type_name = "温湿度センサー";
                break;
            default:
                break;
        } 
    } else if (imei_str.length == 8) {
        var type_no = imei_str.substr(1, 1);
        switch (type_no) {
            case '1':
                s_type_name = "RF温度センサー";
                break;
            case '2':
                s_type_name = "RF温湿度センサー";
                break;
            case 'A':
                s_type_name = "LoRa湿度センサー";
                break;
            case 'B':
                s_type_name = "LoRa温湿度センサー";
                break;
            default:
                break;
        } 
    } else {
        s_type_name = "旧型センサー?";
    }
    typename.textContent = s_type_name;
    type_id.value = jd.TypeID;
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
    var type_id = document.getElementById('TypeID');
    var group_id = document.getElementById('GroupID');
    if (type_id.value == "0") {
        alert('シリアル番号が存在しません！！');
        type_id.focus();
        return false;
    }
    if (group_id.value == "0") {
        alert('グループ名を選択してください！！');
        group_id.focus();
        return false;
    }
}
