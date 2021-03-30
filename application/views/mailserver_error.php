<style>
    .errormail{
        color: black;
        padding: 12px;
        font-size: 15px;
        text-align: center;
        background-color: #a99f42 !important;
    }
    #close_error {
        float:right;
        display:inline-block;
        padding:2px 5px;
        background:#ccc;
    }
    #close_error:hover {
        float:right;
        display:inline-block;
        padding:2px 5px;
        background:#ccc;
        color:#fff;
        cursor: pointer;
    }
</style>

<div class="errormail">メールサーバーが見つかりません。<span id='close_error' onclick='this.parentNode.remove(); return false;'>x</span></div>
