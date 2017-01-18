<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
    start_session();

	if(isset($_POST["id"]) && isset($_POST["title"]) && isset($_POST["text"]) && isset($_POST["listorder"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "updateforums")){
			forums::editforum($_POST["id"], $_POST["title"], $_POST["text"], $_POST["listorder"]);
			//logs::add_log("bans", "$1 unbanned [user:{$_POST["userid"]}]", 2);
            echo "success";
		}else{
			echo "No permission!";
		}
	}else{
        echo "Missing inputs!";
    }
?>
