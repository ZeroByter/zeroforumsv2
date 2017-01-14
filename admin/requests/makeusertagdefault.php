<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["tagid"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "makeusertagdefault")){
			usertags::set_default($_POST["tagid"]);
			logs::add_log("usertags", "$1 set [usertag:{$_POST["tagid"]}] as the default usertag", 2);
			echo "success";
		}else{
			echo "No permission!";
		}
	}
?>
