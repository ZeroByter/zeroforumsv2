<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["tagid"])){
		//if(usertags::user_has_permission(accounts::get_current_usertags(), "makeusertagdefault")){
		usertags::toggle_staff($_POST["tagid"]);
		logs::add_log("usertags", "$1 toggled 'staff' status for [usertag:{$_POST["tagid"]}]", 1);
		echo "success";
		//}else{
		//	echo "No permission!";
		//}
	}
?>
