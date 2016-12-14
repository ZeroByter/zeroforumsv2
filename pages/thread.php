<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/navbar.php");

    $thread = forums::get_by_id($threadID);
    $parent = forums::get_by_id($thread->parent);
    $parent2 = forums::get_by_id($parent->parent);
?>
<link rel="stylesheet" type="text/css" href="/stylesheets/thread.css"></link>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="/">Forums</a></li>
        <li><a href="javascript:"><?php echo $parent2->title ?></a></li>
        <li><a href="/subforum/<?php echo $parent->id ?>"><?php echo $parent->title ?></a></li>
        <li class="active"><?php echo $thread->title ?></li>
    </ol>
    <div class="row">
        <div class="col-xl-1 threadBody">
            <div class="threadBodyInfo">
                <a href="/user/0"><?php echo accounts::get_display_name($thread->poster) ?></a><br>
            </div>
            <div class="threadBodyText">
                <?php echo filterXSS($thread->text) ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(".threadBodyText").each(function(i, v){
        $(v).html(filter_bbcode($(v).html()))
    })
</script>
