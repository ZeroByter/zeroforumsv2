<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
    start_session();

	if(isset($_POST["userid"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "banuser")){
			accounts::unban($_POST["userid"]);
			logs::add_log("bans", "$1 unbanned [user:{$_POST["userid"]}]", 2);
            echo "success";
		}else{
			echo "No permission!";
		}
	}else{
        echo "Missing inputs!";
    }
?>
