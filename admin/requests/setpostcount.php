<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
    start_session();

	if(isset($_POST["userid"]) && isset($_POST["postcount"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "setpostcount")){
			accounts::set_post_count($_POST["userid"], $_POST["postcount"]);
			logs::add_log("accounts", "$1 set [account:{$_POST["userid"]}] [postcount:{$_POST["postcount"]}]", 1);
            echo "success";
		}else{
			echo "No permission!";
		}
	}
?>
