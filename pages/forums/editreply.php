<?php
    $reply = forums::get_by_id($replyID);
    $subforum = forums::get_by_id($thread->parent);
    $forum = forums::get_by_id($subforum->parent);

    if(!isset($reply)){
        redirectWindow("/thread/$thread->id");
    }
?>

<div id="threadText" data-text="<?php echo $reply->text ?>"></div>
<div class="container">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="/forums">Forums</a></li>
            <li><a href="javascript:"><?php echo $forum->title ?></a></li>
            <li><a href="/subforum/<?php echo $subforum->id ?>"><?php echo $subforum->title ?></a></li>
            <li><a href="/thread/<?php echo $thread->id ?>"><?php echo $thread->title ?></a></li>
        </ol>
        <div class="col-xl-1">
            <div class="well">
                <h4>Edit reply:</h4>
                <form id="submit_form" onsubmit="void(0)">
                    <div class="input-group">
                        <span class="input-group-addon">Body</span>
                        <div class="form-control" style="height:inherit;" id="zeroeditor_div">
                            <?php
                                include("phpscripts/fillin/zeroeditor.php");
                            ?>
                        </div>
                    </div><br>
                    <center>
                        <button type="submit" class="btn btn-primary">Edit reply</button>
                        <a href="/thread/<?php echo $thread->id ?>"><button type="button" class="btn btn-default">Cancel</button></a>
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $("#zeroeditor_div").find("#textarea").attr("required", "")
    setEditorString($("#threadText").data("text"))

    $("#submit_form").submit(function(){
        $.post("/phpscripts/requests/editreply.php", {reply: "<?php echo $replyID ?>", text: getEditorString()}, function(html){
            console.log(html)
            if(html == "success"){
                window.location = "/thread/<?php echo $thread->id ?>"
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
