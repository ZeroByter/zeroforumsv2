<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["name"]) && isset($_POST["listorder"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "addusertag")){
			$newtag = usertags::add_usertag($_POST["name"], $_POST["listorder"]);
			logs::add_log("usertags", "$1 created a usertag with the [name:{$_POST["name"]}] and [listorder:{$_POST["listorder"]}]", 1);
			echo "success:$newtag";
		}else{
			echo "No permission!";
		}
	}
?>
