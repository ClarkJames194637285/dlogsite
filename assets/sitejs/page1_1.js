/* page1_1 */

$(function(){
    $("input"). keydown(function(e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            return false;
        } else {
            return true;
        }
    });
});

function oldPasscheck(pass_formId, on_off) {
    var form = document.getElementById('form1');    
    var url = $('#base_url').text() + 'setting/userSetting';
    if (on_off == 0) {
        form.action = url;
        form.submit();
        form.oldpass.onChange = "oldPasscheck(this, 0);";
    } 
}
function CheckPassword() {
    var newpassid = document.getElementById("newpass");
    var confirmid = document.getElementById("confirm");
    var recaptcha = document.getElementById("recaptcha");
    var input1 = newpassid.value;
    var input2 = confirmid.value;
    if (input2 != "") {
        if (input1 != input2 && input2 == "") {
            // alert("入力値が一致しません。");
            confirmid.setCustomValidity("入力値が一致しません。");
            //recaptcha.style = "display: none";
        } else {
            confirmid.setCustomValidity('');
            //recaptcha.style = "";
            return true;
        }
    } else {
        var url = $('#base_url').text() + 'setting/userSetting';
        var form = document.getElementById('form1');
        form.action = url;
    }
    return false;
}
function formSubmit() {
    var form = document.getElementById('form1');
    if (CheckPassword()) {
        var url = $('#base_url').text() + 'setting/userSetting/confirm';
        form.action = url;
        form.submit();
    }    
}

var onloadCallback = function() {
    grecaptcha.render('recaptcha', {
        'sitekey' : sitekey,
        'callback' : verifyCallback,
        'expired-callback' : expiredCallback
    });
};

var verifyCallback = function() {
    document.getElementById("warning").textContent = "";
    //document.getElementById("update").disabled = false;
    document.getElementById("update").style = "";
    var form = document.getElementById("form1");
    var url = $('#base_url').text() + 'setting/userSetting';
    form.action =url;
    //form.submit();
};

var expiredCallback = function() {
    document.getElementById("warning").textContent = "送信するにはチェックを入れてください。";
    document.getElementById("update").disabled = true;
    document.getElementById("update").style = "display: none;";
};


var myAlert = function(response) {
    var update = document.getElementById('update');
    var recaptcha = document.getElementById('recaptcha');
    alert("チェック承認されました！\nパスワード変更ボタンをクリックしてください。");

    update.style = "";
    //recaptcha.style = "display: none;";
};
