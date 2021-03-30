/* page1_3-edit */
function form_submit() {
    var form = document.getElementById('form1');
    var roleid = "";
    $("#form1 .checkboxes").each(function(e) {
        if (e > 0) {
            if ($(this).is(":checked")) {
                roleid += "1";
            } else {
                roleid += "0";
            }
        }
    });
    form.RoleID.value = roleid;
}
