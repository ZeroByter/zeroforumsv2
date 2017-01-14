<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["userid"]) && isset($_POST["usertagid"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "addusertag")){
			accounts::add_usertag($_POST["userid"], $_POST["usertagid"]);
			logs::add_log("usertags", "$1 added [user:{$_POST["userid"]}] [usertag:{$_POST["usertagid"]}]", 3);
		}else{
			echo "No permission!";
		}
	}
?>
