<div id="texteditor_main_div">
    <div id="texteditor_controls">
        <div class="btn-toolbar" role="toolbar" id="texteditor_toolbar">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default" id="bold"><span class="fa fa-bold"></span></button>
                <button type="button" class="btn btn-default" id="italic"><span class="fa fa-italic"></span></button>
                <button type="button" class="btn btn-default" id="underline"><span class="fa fa-underline"></span></button>
                <button type="button" class="btn btn-default" id="strikethrough"><span class="fa fa-strikethrough"></span></button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default" id="alignleft"><span class="fa fa-align-left"></span></button>
                <button type="button" class="btn btn-default" id="aligncenter"><span class="fa fa-align-center"></span></button>
                <button type="button" class="btn btn-default" id="alignright"><span class="fa fa-align-right"></span></button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default" id="listnumbers"><span class="fa fa-list-ol"></span></button>
                <button type="button" class="btn btn-default" id="listpoints"><span class="fa fa-list-ul"></span></button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default" id="link"><span class="fa fa-link"></span></button>
                <button type="button" class="btn btn-default" id="youtube"><span class="fa fa-youtube"></span></button>
            </div>
            <button type="button" class="btn btn-default" id="preview"><span class="fa" style="font-family: arial;">Preview</span></button>
        </div>
    </div>
    <div>
        <textarea class="form-control" id="textarea"></textarea>
    </div>
    <div style="display: none;" id="textarea_preview_div">
        <div class="btn-toolbar" role="toolbar">
            <button type="button" class="btn btn-default" id="editor"><span class="fa" style="font-family: arial;">Editor</span></button>
        </div>
        <div style="text-align:center;">Preview:</div>
        <div id="preview_text"></div>
    </div>
</div>
