<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <a href="/admin/forums"><button class="btn btn-info"><span class="glyphicon glyphicon-chevron-left"></span>  Go back</button></a>
            <br><br><br>
            <form id="submitForm">
                <div class="input-group form-group">
                    <span class="input-group-addon">Title</span>
                    <input type="text" class="form-control" value="<?php echo $forum->title ?>">
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">Description</span>
                    <input type="text" class="form-control" value="<?php echo $forum->text ?>">
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">List order</span>
                    <input type="number" class="form-control" value="<?php echo $forum->listorder ?>">
                </div>
            </form>
        </div>
    </div>
</div>
