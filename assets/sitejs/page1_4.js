/* page1_4 */

(function(){
    if (!Element.prototype.matches) Element.prototype.matches = Element.prototype.msMatchesSelector;

    let dragging = false;
    // 【ドラッグする側】ドラッグ開始
    document.addEventListener("dragstart", function(e){
        //console.log("dragstart", e);
        if (e.target.matches(".group-block:not(.dummy)")) {
            e.dataTransfer.effectAllowed = "move"; // 移動のみ許可
            e.target.classList.add("dragging");
            dragging = true;
        }
    });
    // 【ドラッグする側】ドラッグが終了した
    document.addEventListener("dragend", function(e){
        //console.log("dragend", e);
        if (e.target.matches(".group-block:not(.dummy)")) {
            e.target.classList.remove("dragging");
            dragging = false;
        }
    });

    // 【ドラッグされた側】ドラッグされた要素が入ってきている
    document.addEventListener("dragenter", function(e){
        //console.log("dragenter", e);
        if (dragging && e.target.matches(".group-block")) {
            e.preventDefault();
            e.target.classList.add("drag-over");
        }
    });
    // 【ドラッグされた側】ドラッグされた要素が乗っている
    document.addEventListener("dragover", function(e){
        //console.log("dragover", e, dragging);
        if (dragging && e.target.matches(".group-block")) {
            e.preventDefault();
            e.dataTransfer.dropEffect = "move";
        }
    });
    // 【ドラッグされた側】ドラッグされた要素が離れた
    document.addEventListener("dragleave", function(e){
        //console.log("dragleave", e);
        if (dragging && e.target.matches(".group-block")) {
            e.target.classList.remove("drag-over");
        }
    });
    // 【ドラッグされた側】ドロップされた
    document.addEventListener("drop", function(e){
        //console.log("drop", e);
        if (dragging && e.target.matches(".group-block")) {
            // ドラッグ中要素を取得・再配置
            const draggingItem = e.target.parentNode.querySelector(".dragging");
            const refItem = e.target.matches(".dummy") ? e.target.previousSibling : e.target; // ダミーより後ろには置かない
            e.target.parentNode.insertBefore(draggingItem, refItem);
            e.target.classList.remove("drag-over");

            // 順序情報の更新
            Array.prototype.forEach.call(e.target.parentNode.querySelectorAll("input[type=hidden].item-order"),
            function(el, i){
                el.value = i + 1;
            });
        }
        var formid = document.getElementById('sort_form');
        formid.submit();
    });

    
})();

function Delete(id)
{
    if (!confirm("削除してもよろしいですか？")) {
        return;
    }
    location.href = "./GroupManagement?M=Delete&ids=" + id;
}

function DeleteMulti() {
    var ids = "";
    $("#sort_form .checkboxes").each(function (e) {
        if (e > 0) {
            if ($(this).is(":checked")) {
                ids += $(this).val() + ",";
            }
        }
    });
    
    if (ids.length > 0) {
        Delete(ids);
    } else {
        alert('チェックしてから削除をクリックしてください。');
    }
}

function CheckboxAllCheck(id)
{
    alert(id.checked);
    if (id.is(":checked")) {
        id.checked = false;
    } else {
        id.checked = true;
    }
}
