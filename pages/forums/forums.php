<link rel="stylesheet" type="text/css" href="/stylesheets/forums.css"></link>

<table id="main_body_table">
    <tr>
		<td id="body_left_space" style="display:none;">

        </td>
        <td id="body_center_space">
            <div class="container">
                <div class="row">
                    <div class="col-xl-1">
                        <?php
                            foreach(forums::get_all_forums() as $value){
                                if($value){ echo "<h4>$value->title</h4><h5 class='text-muted'>$value->text</h5>" ?>
                                    <div class="subforum_main_div">
                                        <div class="container">
                                            <div class="row">
                                            <?php foreach(forums::get_all_subforums($value->id) as $value){
                                                if(!usertags::can_tag_do($currUsertags, $value->canview)){ continue; } ?>
                                                    <div class="col-md-4">
                                                        <a href="/subforum/<?php echo $value->id ?>" class="subforum_div">
                                                            <img src="<?php echo $value->icon ?>" class="subforum_image"></img>
                                                            <div class="subforum_text">
                                                                <span style="font-size: 20px;"><?php echo $value->title ?></span><br>
                                                                <span class="text-muted" style="font-size: 12px;"><?php echo $value->text ?></span>
                                                            </div>
                                                        </a>
                                                    </div>
											<?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </td>
        <td id="body_right_space" style="display:none;">

        </td>
    </tr>
</table>
