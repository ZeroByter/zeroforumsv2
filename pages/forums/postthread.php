<?php
    $forum = forums::get_by_id($subforum->parent);
?>

<div class="container">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="/forums">Forums</a></li>
            <li><a href="javascript:"><?php echo $forum->title ?></a></li>
            <li><a href="/subforum/<?php echo $subforum->id ?>"><?php echo $subforum->title ?></a></li>
        </ol>
        <div class="col-xl-1">
            <div class="well">
                <h4>New thread:</h4>
                <form id="submit_form" onsubmit="void(0)">
                    <div class="input-group">
                        <span class="input-group-addon">Subject</span>
                        <input class="form-control" id="subject" required></input>
                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon">Body</span>
                        <div class="form-control" style="height:inherit;" id="zeroeditor_div">
                            <?php
                                include("phpscripts/fillin/zeroeditor.php");
                            ?>
                        </div>
                    </div><br>
                    <center>
                        <button type="submit" class="btn btn-primary">Post thread</button>
                        <a href="/subforum/<?php echo $subforum->id ?>"><button type="button" class="btn btn-default">Cancel</button></a>
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $("#zeroeditor_div").find("#textarea").attr("required", "")

    $("#submit_form").submit(function(){
        $.post("/phpscripts/requests/postthread.php", {subforum: "<?php echo $subforum->id ?>", subject: $("#subject").val(), text: getEditorString()}, function(html){
            console.log(html)
            if(html.split(":")[0] == "success"){
                window.location = "/thread/" + html.split(":")[1]
            }else{
                $.notify({
                    message: html,
                },{
                    type: "danger",
                    placement: {
                		from: "bottom",
                		align: "center"
                	},
                })
            }
        })
        return false
    })
</script>
