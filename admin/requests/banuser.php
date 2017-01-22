<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
    start_session();

	if(isset($_POST["userid"]) && isset($_POST["reason"]) && isset($_POST["time"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "banuser")){
			accounts::issue_ban($_POST["userid"], $_POST["reason"], $_POST["time"]);
			logs::add_log("accounts", "$1 banned [user:{$_POST["userid"]}] for [reason:{$_POST["reason"]}] for [time:{$_POST["time"]}]", 3);
            echo "success";
		}else{
			echo "No permission!";
		}
	}
?>
