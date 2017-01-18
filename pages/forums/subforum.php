<?php
    //$subforumID;
    //confirm subforum exists and if we are allowed to see it
	
    $parent = forums::get_by_id($subforum->parent);
?>
<link rel="stylesheet" type="text/css" href="/stylesheets/subforum.css"></link>

<div class="container" id="main_threads_div">
    <ol class="breadcrumb">
        <li><a href="/forums">Forums</a></li>
        <li><a href="javascript:"><?php echo $parent->title ?></a></li>
        <li class="active"><?php echo $subforum->title ?></li>
    </ol>
    <?php if(usertags::user_has_permission($currUsertags, "createthread") && usertags::can_tag_do($currUsertags, $subforum->canpost)){ ?>
        <a href="/subforum/<?php echo $subforum->id ?>/postThread"><button class="btn btn-primary">Post thread</button></a><br><br>
    <?php } $pinnedThreads = forums::get_all_pinned_threads($subforum->id);
    foreach($pinnedThreads as $value){
        if($value){
            $iconString = "";
            if($value->locked){
                $iconString .= " <i class='fa fa-lock' data-toggle='tooltip' title='This thread is locked.'></i> ";
            }
			$previewString = $value->text;
            if(strlen($previewString) > 40){
                $previewString = substr($previewString, 0, 40);
                $previewString .= "...";
            }
			
            ?>
            <div class="row">
                <div class="col-xl-1">
                    <a href="/thread/<?php echo $value->id ?>" class="thread sticky-thread">
                        <span class="threadTitle" data-toggle="tooltip" title="<?php echo $previewString ?>"><?php echo $value->title ?></span> <span><i class='fa fa-thumb-tack' data-toggle='tooltip' title='This thread is pinned.'></i>  <?php echo $iconString ?></span>
                        <span class="threadDate"><?php echo get_human_time($value->lastactive) ?> ago</span>
                        <span class="threadAuthor"><?php echo accounts::get_display_name($value->poster) ?></span>
                        <span class="threadReplies"><?php echo $value->replies ?> <span class="fa fa-commenting-o"></span></span>
                    </a>
                </div>
            </div>
        <?php }
    }; //if(count($pinnedThreads)){ echo "<br><br><br><br>"; }?>
    <?php foreach(forums::get_all_threads($subforum->id) as $value){
        if($value){
            $iconString = "";
            if($value->locked){
                $iconString .= " <i class='fa fa-lock' data-toggle='tooltip' title='This thread is locked.'></i> ";
            }
            if($value->hidden){
                $iconString .= " <i class='fa fa-eye-slash' data-toggle='tooltip' title='This thread is hidden.'></i> ";
            }
            if($value->hidden && !usertags::user_has_permission($currUsertags, "hideunhide")){
                continue;
            }
            $previewString = $value->text;
            if(strlen($previewString) > 40){
                $previewString = substr($previewString, 0, 40);
                $previewString .= "...";
            }

            ?>
            <div class="row">
                <div class="col-xl-1">
                    <a href="/thread/<?php echo $value->id ?>" class="thread">
                        <span class="threadTitle" data-toggle="tooltip" title="<?php echo $previewString ?>"><?php echo $value->title ?></span><span><?php echo $iconString ?></span>
                        <span class="threadDate"><?php echo get_human_time($value->lastactive) ?> ago</span>
                        <span class="threadAuthor"><?php echo accounts::get_display_name($value->poster) ?></span>
                        <span class="threadReplies"><?php echo $value->replies ?> <span class="fa fa-commenting-o"></span></span>
                    </a>
                </div>
            </div>
        <?php }
    } ?>
</div>

<script>
    $('[data-toggle="tooltip"]').tooltip()
</script>
