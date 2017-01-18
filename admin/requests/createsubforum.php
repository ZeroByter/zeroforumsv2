<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["parent"]) && isset($_POST["title"]) && isset($_POST["text"]) && isset($_POST["listorder"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "createforums")){
			$subforum = forums::create_subforum($_POST["parent"], $_POST["title"], $_POST["text"], $_POST["listorder"]);
			logs::add_log("forums", "$1 created [subforum:{$_POST["title"]}] under [forum:{$_POST["parent"]}]", 2);
			echo "success:$subforum";
		}else{
			echo "No permission!";
		}
	}
?>
