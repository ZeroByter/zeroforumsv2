<?php
    $thread = forums::get_by_id($threadID);
    $parent = forums::get_by_id($thread->parent);
    $parent2 = forums::get_by_id($parent->parent);

    if($thread->hidden && !usertags::user_has_permission($currUsertags, "hideunhide")){
        redirectWindow("/subforum/$parent->id");
    }
?>
<link rel="stylesheet" type="text/css" href="/stylesheets/thread.css"></link>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="/">Forums</a></li>
        <li><a href="javascript:"><?php echo $parent2->title ?></a></li>
        <li><a href="/subforum/<?php echo $parent->id ?>"><?php echo $parent->title ?></a></li>
        <li class="active"><?php echo $thread->title ?></li>
    </ol>
    <?php if($thread->locked){ ?>
        <div class="label label-info threadLabel"><span class="fa fa-lock"></span> This thread is locked.</div><br>
    <?php } ?>
    <?php if($thread->pinned){ ?>
        <div class="label label-info threadLabel"><span class="fa fa-thumb-tack"></span> This thread is pinned.</div><br>
    <?php } ?>
    <?php if($thread->hidden){ ?>
        <div class="label label-info threadLabel"><span class="fa fa-eye-slash"></span> This thread is hidden, but you have permission to view it.</div><br>
    <?php } ?>
    <?php if($thread->locked || $thread->hidden || $thread->pinned){ echo "<br>"; } ?>
    <span class="row">
        <div class="col-xl-1">
            <span style="float:right"><?php echo $thread->replies; echo ($thread->replies == 1) ? " reply" : " replies" ?></span>
            <?php if(accounts::is_logged_in() && $thread->deletestatus == 0){ ?>
                <div class="btn-group" id="threadControls">
                    <?php if(usertags::user_has_permission($currUsertags, "createthread")){ ?>
                        <a id="postreply" href="/thread/<?php echo $thread->id ?>/postReply"><button class="btn btn-primary">Post reply</button></a>
                    <?php }; if($thread->locked && !usertags::user_has_permission($currUsertags, "lockunlock")){ removeHTMLElement("#postreply"); } ?>
                    <?php if($thread->poster == $currAccount->id && usertags::user_has_permission($currUsertags, "editownpost") || usertags::user_has_permission($currUsertags, "editposts")){ ?>
                        <a href="/thread/<?php echo $thread->id ?>/editThread"><button class="btn btn-info">Edit thread</button></a>
                    <?php } ?>
                    <?php if($thread->poster == $currAccount->id && usertags::user_has_permission($currUsertags, "deleteownpost") || usertags::user_has_permission($currUsertags, "deleteposts")){ ?>
                        <a><button class="btn btn-default threadActionBtn" data-type="deletethread" data-id="<?php echo $thread->id ?>">Delete thread</button></a>
                    <?php } ?>
                    <?php if(usertags::user_has_permission($currUsertags, "lockunlock")){ ?>
                        <a><button class="btn btn-default threadActionBtn" data-type="togglethreadlock" data-id="<?php echo $thread->id ?>"><?php echo ($thread->locked) ? "Unlock" : "Lock" ?></button></a>
                    <?php } ?>
                    <?php if(usertags::user_has_permission($currUsertags, "pinunpin")){ ?>
                        <a><button class="btn btn-default threadActionBtn" data-type="togglethreadpin" data-id="<?php echo $thread->id ?>"><?php echo ($thread->pinned) ? "Unpin" : "Pin" ?></button></a>
                    <?php } ?>
                    <?php if(usertags::user_has_permission($currUsertags, "hideunhide")){ ?>
                        <a><button class="btn btn-default threadActionBtn" data-type="togglethreadhidden" data-id="<?php echo $thread->id ?>"><?php echo ($thread->hidden) ? "Unhide" : "Hide" ?></button></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </span>
    <div class="row">
		<h1 style="margin:12px;margin-top:0px;"><?php echo $thread->title ?></h1>
        <div class="col-xl-1 threadBody">
            <div class="threadBodyInfo">
                <?php if($thread->deletestatus == 0 || $thread->deletestatus == 2){ ?>
                    <b><a href="/user/profile/<?php echo $thread->poster ?>"><?php echo accounts::get_display_name($thread->poster) ?></a></b><br>
                <?php }if($thread->deletestatus == 1){ ?>
                    <b><font color='red'>Thread deleted by original poster.</font></b>
                <?php } ?>
            </div>
            <div class="threadBodyText">
                <?php
                    if($thread->deletestatus == 0){
                        echo filterXSS($thread->text);
                    }elseif($thread->deletestatus == 1) {
                        echo filterXSS($thread->text);
                    }elseif($thread->deletestatus == 2){
                        echo "<b><font color='red'>This thread was deleted by ". accounts::get_display_name($thread->deletedby).".</font></b>";
                    }
                 ?>
                <span class="text-muted postedDate">Posted at <?php echo timestamp_to_date($thread->firstposted, true); if($thread->lastedited > 0){ echo ". Last edited by ".accounts::get_display_name($thread->lastediteduser)." at ".timestamp_to_date($thread->lastedited, true); } ?></span>
            </div>
        </div>
    </div>
    <?php
        foreach(forums::get_all_replies($thread->id) as $value){ if($value){
        if($value->hidden && !usertags::user_has_permission($currUsertags, "hideunhide")){}else{
    ?>
        <div class="row threadRow">
            <div class="col-xl-1 threadBody">
                <div class="threadBodyInfo">
                    <?php if($value->deletestatus == 0 || $value->deletestatus == 2){ ?>
                        <b><a href="/user/<?php echo $value->poster ?>"><?php echo accounts::get_display_name($value->poster) ?></a></b><br>
                    <?php }if($value->deletestatus == 1){ ?>
                        <b><font color='red'>Reply deleted by original poster.</font></b>
                    <?php } ?>
                    <?php if($value->deletestatus == 0){ ?>
                        <div class="threadBodyControls">
                            <?php if(isset($currAccount) && $value->poster == $currAccount->id && usertags::user_has_permission($currUsertags, "editownpost") || usertags::user_has_permission($currUsertags, "editposts")){ ?>
                                <a href="/thread/<?php echo $thread->id ?>/editReply/<?php echo $value->id ?>">Edit reply</a><br>
                            <?php }if(isset($currAccount) && $value->poster == $currAccount->id && usertags::user_has_permission($currUsertags, "deleteownpost") || usertags::user_has_permission($currUsertags, "deleteposts")){ ?>
                                <a href="javascript:" class="threadActionBtn" data-type="deletethread" data-id="<?php echo $value->id ?>">Delete reply</a><br>
                            <?php }if(isset($currAccount) && usertags::user_has_permission($currUsertags, "hideunhide")){ ?>
                                <a href="javascript:" class="threadActionBtn" data-type="togglethreadhidden" data-id="<?php echo $value->id ?>"><?php echo ($value->hidden) ? "Unhide" : "Hide" ?> reply</a><br>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="threadBodyText">
                    <?php
                        if($value->deletestatus == 0 || $value->deletestatus == 1){
                            if($value->hidden){
                                echo "<font color='red'><i><b>* This reply is hidden *</b></i></font><br><br>";
                            }
                            echo filterXSS($value->text);
                        }elseif($value->deletestatus == 2){
                            echo "<b><font color='red'>This thread was deleted by ". accounts::get_display_name($value->deletedby).".</font></b>";
                        }
                    ?>
                    <span class="text-muted postedDate">Posted at <?php echo timestamp_to_date($value->firstposted, true); if($value->lastedited > 0){ echo ". Last edited by ".accounts::get_display_name($value->lastediteduser)." at ".timestamp_to_date($value->lastedited, true); } ?></span>
                </div>
            </div>
        </div>
            <?php }
            }
        } ?>
</div>

<script>
    $('[data-toggle="tooltip"]').tooltip()

    $(".threadBodyText").each(function(i, v){
        $(v).html(filter_bbcode($(v).html()))
    })

    $(".threadActionBtn").click(function(){
        $.post("/phpscripts/requests/"+$(this).data("type")+".php", {id: $(this).data("id")}, function(html){
            if(html == "success"){
                location.reload()
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
    })
</script>
