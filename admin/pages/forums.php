<style>
    .noUnderline:hover, .noUnderline:active{
        text-decoration: none;
    }

    .forumsMainDiv{
        width: 100%;
        border: 1px solid grey;
        border-radius: 1px;
    }

    .forumDiv{
        background: grey;
        font-size: 16px;
        padding: 6px;
        color: black;
    }
    .forumDiv:hover{
        background: #949494;
    }

    .subforumDiv{
        background: grey;
        font-size: 16px;
        padding: 6px 30px;
        color: black;
    }
    .subforumDiv:hover{
        background: #949494;
    }
</style>

<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
			<?php if(usertags::user_has_permission($currUsertags, "createforums")){ ?>
					<a href="/admin/forums/new" class="noUnderline"><div class="forumDiv">
						<span class="fa fa-plus"></span> Create new forum
					</div></a>
            <?php } foreach(forums::get_all_forums() as $value){ ?>
                <div class="forumsMainDiv">
                    <a href="/admin/forums/<?php echo $value->id ?>" class="noUnderline"><div class="forumDiv">
                        <?php echo $value->title ?>
                    </div></a>
					<?php if(usertags::user_has_permission($currUsertags, "createforums")){ ?>
						<a href="/admin/forums/<?php echo $value->id ?>/new" class="noUnderline"><div class="subforumDiv">
							<span class="fa fa-plus"></span> Create new subforum
						</div></a>
                    <?php } foreach(forums::get_all_subforums($value->id) as $value){ ?>
                        <a href="/admin/forums/<?php echo $value->id ?>" class="noUnderline"><div class="subforumDiv">
                            <span class="glyphicon glyphicon-chevron-right"></span> <?php echo $value->title ?>
                        </div></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
