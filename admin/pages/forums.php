<style>
    .row{
        padding: 0px 19px;
    }
	.well{
		border-color: #c1c1c1;
		padding: 0px;
	}
	.divLink{
		padding: 10px;
		display: block;
	}
	.divLink:hover{
		background: #ccc;
		color: #0089ff;
	}
    .subforum{
        padding-left: 30px;
    }
</style>

<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 well">
    		<?php
                if(usertags::user_has_permission($currUsertags, "createforums")){
                    echo "<a href='/admin/forums/new' class='divLink createNew'><span class='fa fa-plus'></span> Create new forum</a>";
                }
            ?>
            <?php foreach(forums::get_all_forums() as $value){ ?>
                <a href="/admin/forums/<?php echo $value->id ?>" class="divLink"><span class="fa fa-chevron-right"></span> <?php echo $value->title ?></a>
                <?php if(usertags::user_has_permission($currUsertags, "createforums")){ ?>
                    <a href="/admin/forums/<?php echo $value->id ?>/new" class="divLink subforum"><span class="fa fa-plus"></span> Create new subforum</a>
                <?php } foreach(forums::get_all_subforums($value->id) as $value){ ?>
                    <a href="/admin/forums/<?php echo $value->id ?>" class="divLink subforum"><span class="fa fa-chevron-right"></span> <?php echo $value->title ?></a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
