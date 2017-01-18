<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();
	
	if(isset($_GET["id"]) && isset($_GET["type"])){
		echo json_encode(forums::get_forum_perms($_GET["id"], $_GET["type"]));
	}
?>
