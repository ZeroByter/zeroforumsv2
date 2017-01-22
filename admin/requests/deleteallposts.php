<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["userid"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "deleteallposts")){
			forums::delete_all_by_poster($_POST["userid"]);
			logs::add_log("forums", "$1 deleted all of [account:{$_POST["userid"]}] forum posts", 50);
            echo "success";
		}else{
			echo "No permission!";
		}
	}
?>
