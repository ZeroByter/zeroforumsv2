<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
    start_session();

	if(isset($_POST["id"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "createforums")){
			$forum = forums::get_by_id($_POST["id"]);
			if($forum->type == "forum"){
				forums::forumdelete($_POST["id"]);
				logs::add_log("forums", "$1 deleted [forum:$forum->title] and all it's contents", 2);
			}else{
				forums::subforumdelete($_POST["id"]);
				logs::add_log("forums", "$1 deleted [subforum:$forum->title] and all it's contents", 2);
			}
            echo "success";
		}else{
			echo "No permission!";
		}
	}else{
        echo "Missing inputs!";
    }
?>
