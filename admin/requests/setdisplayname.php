<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["userid"]) && isset($_POST["newname"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "setdisplayname")){
			accounts::changeDisplayName($_POST["userid"], $_POST["newname"]);
			logs::add_log("accounts", "$1 set [user:{$_POST["userid"]}] display name to [displayname:{$_POST["newname"]}]", 1);
			echo "success";
		}else{
			echo "No permission!";
		}
	}
?>
