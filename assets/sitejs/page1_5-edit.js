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
    var typenameback = typename.textContent;
    for (var i = 0; i < dobj.length; i++) {
        var jd = JSON.stringify(dobj[i]);
        jd = JSON.parse(jd);
        if (jd.IMEI.trim() == imei.value.trim()) {
            typename.textContent = jd.TypeName;
            type_id.value = jd.TypeID;
            break;
        } else {
            typename.textContent = "";
        }
    }
    if (typename.textContent == "") {
        alert("シリアル番号の登録がありません！");
        imei.value = typeidback;
        typename.textContent = typenameback;
    }
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
    if (type_id.value == "0") {
        alert('シリアル番号が存在しません！！');
        type_id.focus();
        return false;
    }
}
