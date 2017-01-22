<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["tagid"]) && isset($_POST["name"]) && isset($_POST["listorder"]) && isset($_POST["textcolor"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "updateusertag")){
			logs::add_log("usertags", "$1 updated [usertag:{$_POST["tagid"]}] to [name:{$_POST["name"]}] and [listorder:{$_POST["listorder"]}]", 1);
			usertags::edit_usertag($_POST["tagid"], $_POST["name"], $_POST["listorder"], $_POST["textcolor"]);
			echo "success";
		}else{
			echo "No permission!";
		}
	}
?>
