<?php
    if(!file_exists($_SERVER['DOCUMENT_ROOT'] . "/config.php")){
?>

<style>
    #firstTimeDiv{
        width: 50%;
        margin: 20px auto;
        box-shadow: #4f4fff 0px 0px 30px 0px;
        border-color: #0300cc;
        overflow: overlay;
    }
</style>

<div class="well" id="firstTimeDiv">
    <center><h4>Welcome to zeroforums version 2! Since this is your first time launching your very own forums, we will just need to get a few details from you first.</h4></center><br><br>
    <form id="firstTimeForm">
        <div class="form-group">
            <label>MySQL database IP</label>
            <input type="text" class="form-control" id="mysqlIP" required>
        </div>
        <div class="form-group">
            <label>MySQL database username</label>
            <input type="text" class="form-control" id="mysqlUsername" required>
        </div>
        <div class="form-group">
            <label>MySQL database password</label>
            <input type="password" class="form-control" id="mysqlPassword" required>
        </div>
        <div class="form-group">
            <label>MySQL database name</label>
            <input type="text" class="form-control" id="mysqlDBName" required>
        </div>
        <br><br>
        <div class="form-group">
            <label>Default account username</label>
            <input type="text" class="form-control" id="defaultUsername" required>
        </div>
        <div class="form-group">
            <label>Default account password</label>
            <input type="password" class="form-control" id="defaultPassword" required>
        </div>
        <button type="submit" style="float:right;" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
    $("#generatePasswordPepper").click(function(){
        var text = ""
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"
        for( var i=0; i < 32; i++ ){
            text += possible.charAt(Math.floor(Math.random() * possible.length))
        }

        $("#passPepper").val(text)
    })

    $("#firstTimeForm").submit(function(){
        var inputArray = {
            mysqlIP: $("#mysqlIP").val(),
            mysqlUsername: $("#mysqlUsername").val(),
            mysqlPassword: $("#mysqlPassword").val(),
            mysqlDBName: $("#mysqlDBName").val(),
            defaultUsername: $("#defaultUsername").val(),
            defaultPassword: $("#defaultPassword").val(),
        }

        $.post("/phpscripts/requests/generatefirsttime.php", inputArray, function(html){
            console.log(html)
            //window.location = "/"
        })
        return false
    })
</script>

<?php
    }
?>
