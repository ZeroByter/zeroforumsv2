<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
    start_session();

	if(isset($_POST["userid"]) && isset($_POST["reason"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "warnuser")){
			accounts::add_warning($_POST["userid"], $_POST["reason"]);
			logs::add_log("accounts", "$1 issued [user:{$_POST["userid"]}] a warning with [reason:{$_POST["reason"]}]", 1);
            echo "success";
		}else{
			echo "No permission!";
		}
	}
?>
