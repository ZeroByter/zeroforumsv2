<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();
	
	if(isset($_POST["id"]) && isset($_POST["type"]) && isset($_POST["permissions"])){
		forums::set_forum_perms($_POST["id"], $_POST["type"], $_POST["permissions"]);
		logs::add_log("forums", "$1 set [forums:{$_POST["id"]}] permissions", 10);
		echo "success";
	}
?>
