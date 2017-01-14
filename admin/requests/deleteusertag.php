<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["tagid"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "addusertag")){
			usertags::delete_usertag($_POST["tagid"]);
			logs::add_log("usertags", "$1 deleted [usertag:{$_POST["tagid"]}]", 2);
			echo "success";
		}else{
			echo "No permission!";
		}
	}
?>
