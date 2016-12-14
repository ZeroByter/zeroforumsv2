<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/navbar.php");
    //$subforumID;
    //confirm subforum exists and if we are allowed to see it

    $subforum = forums::get_by_id($subforumID);
    $parent = forums::get_by_id($subforum->parent);
?>
<link rel="stylesheet" type="text/css" href="/stylesheets/subforum.css"></link>

<div class="container" id="main_threads_div">
    <ol class="breadcrumb">
        <li><a href="/">Forums</a></li>
        <li><a href="javascript:"><?php echo $parent->title ?></a></li>
        <li class="active"><?php echo $subforum->title ?></li>
    </ol>
    <?php $pinnedThreads = forums::get_all_pinned_threads($subforumID);
    foreach($pinnedThreads as $value){
        if($value){
            $lockedString = "";
            if($value->locked){
                $lockedString = " <i class='fa fa-lock' data-toggle='tooltip' title='This thread is locked.'></i> ";
            }
            ?>
            <div class="row">
                <div class="col-xl-1">
                    <a href="/thread/<?php echo $value->id ?>" class="thread sticky-thread">
                        <span class="threadTitle"><?php echo $value->title . $lockedString ?> <i class='fa fa-thumb-tack' data-toggle='tooltip' title='This thread is pinned.'></i></span>
                        <span class="threadDate">6 days ago</span>
                        <span class="threadAuthor">ZeroByter</span>
                        <span class="threadReplies"><?php echo $value->replies ?> <span class="fa fa-commenting-o"></span></span>
                    </a>
                </div>
            </div>
        <?php }
    }; //if(count($pinnedThreads)){ echo "<br><br><br><br>"; }?>
    <?php foreach(forums::get_all_threads($subforumID) as $value){
        if($value){
            $lockedString = "";
            if($value->locked){
                $lockedString = " <i class='fa fa-lock' data-toggle='tooltip' title='This thread is locked.'></i> ";
            }
            ?>
            <div class="row">
                <div class="col-xl-1">
                    <a href="/thread/<?php echo $value->id ?>" class="thread">
                        <span class="threadTitle"><?php echo $value->title . $lockedString ?></span>
                        <span class="threadDate">6 days ago</span>
                        <span class="threadAuthor">ZeroByter</span>
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
