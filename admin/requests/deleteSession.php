<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["id"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "deletesession")){
            $session = sessions::get_by_id($_POST["id"]);
			sessions::delete_session_by_id($_POST["id"]);
			logs::add_log("sessions", "$1 deleted a session that belonged to [account:$session->accountid]", 2);
			echo "success";
		}else{
			echo "No permission!";
		}
	}
?>
