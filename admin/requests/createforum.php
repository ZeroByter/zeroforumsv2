<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["title"]) && isset($_POST["text"]) && isset($_POST["listorder"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "createforums")){
			$forum = forums::create_forum($_POST["title"], $_POST["text"], $_POST["listorder"]);
			logs::add_log("forums", "$1 created [forum:{$_POST["title"]}]", 2);
			echo "success:$forum";
		}else{
			echo "No permission!";
		}
	}
	
	//(69*420*1337) dollars to dank memes foundation and all of the money left in the world to Dor :)
?>
