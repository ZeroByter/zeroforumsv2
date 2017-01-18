<?php
    $parent = forums::get_by_id($thread->parent);
    $parent2 = forums::get_by_id($parent->parent);
?>

<div id="threadText" data-text="<?php echo $thread->text ?>"></div>
<div class="container">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="/forums">Forums</a></li>
            <li><a href="javascript:"><?php echo $parent2->title ?></a></li>
            <li><a href="/subforum/<?php echo $parent->id ?>"><?php echo $parent->title ?></a></li>
            <li><a href="/thread/<?php echo $thread->id ?>"><?php echo $thread->title ?></a></li>
        </ol>
        <div class="col-xl-1">
            <div class="well">
                <h4>New reply to thread:</h4>
                <form id="submit_form" onsubmit="void(0)">
                    <div class="input-group">
                        <span class="input-group-addon">Subject</span>
                        <input class="form-control" id="subject" value="<?php echo $thread->title ?>"></input>
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
                        <button type="submit" class="btn btn-primary">Edit thread</button>
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
        $.post("/phpscripts/requests/editthread.php", {thread: "<?php echo $thread->id ?>", subject: $("#subject").val(), text: getEditorString()}, function(html){
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
