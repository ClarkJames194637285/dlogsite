/* page1_8 */

/* グループ、センサー共通 */

(function(){
    if (!Element.prototype.matches) Element.prototype.matches = Element.prototype.msMatchesSelector;

    let dragging = false;
    // 【ドラッグする側】ドラッグ開始
    document.addEventListener("dragstart", function(e){
        //console.log("dragstart", e);
        if (e.target.matches(".list-block:not(.dummy)")) {
            e.dataTransfer.effectAllowed = "move"; // 移動のみ許可
            e.target.classList.add("dragging");
            dragging = true;
        }
    });
    // 【ドラッグする側】ドラッグが終了した
    document.addEventListener("dragend", function(e){
        //console.log("dragend", e);
        if (e.target.matches(".list-block:not(.dummy)")) {
            e.target.classList.remove("dragging");
            dragging = false;
        }
    });

    // 【ドラッグされた側】ドラッグされた要素が入ってきている
    document.addEventListener("dragenter", function(e){
        //console.log("dragenter", e);
        if (dragging && e.target.matches(".list-block")) {
            e.preventDefault();
            e.target.classList.add("drag-over");
        }
    });
    // 【ドラッグされた側】ドラッグされた要素が乗っている
    document.addEventListener("dragover", function(e){
        //console.log("dragover", e, dragging);
        if (dragging && e.target.matches(".list-block")) {
            e.preventDefault();
            e.dataTransfer.dropEffect = "move";
        }
    });
    // 【ドラッグされた側】ドラッグされた要素が離れた
    document.addEventListener("dragleave", function(e){
        //console.log("dragleave", e);
        if (dragging && e.target.matches(".list-block")) {
            e.target.classList.remove("drag-over");
        }
    });
    // 【ドラッグされた側】ドロップされた
    document.addEventListener("drop", function(e){
        //console.log("drop", e);
        if (dragging && e.target.matches(".list-block")) {
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
    });
})();

/* 共通 */

function sortsubmit(no) {
    /**
     * no=sort category
     * 1 = グループ
     * 2 = センサー
     */

    switch (no) {
        case 1:
            var form_id = document.getElementById('sort_form1'); 
            var url = "./page1_8.php?M=Sort1";       
            break;
        case 2:
            var form_id = document.getElementById('sort_form2');
            var url = "./page1_8.php?M=Sort2";
            break;
    }
    form_id.action = url;
    form_id.submit();
}
