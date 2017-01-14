<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

	if(isset($_POST["userid"]) && isset($_POST["usertagid"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "addusertag")){
			if(count(accounts::get_user_tags($_POST["userid"])) <= 1){
				echo "You can't take off their last and only usertag!";
			}else{
				accounts::take_usertag($_POST["userid"], $_POST["usertagid"]);
				logs::add_log("usertags", "$1 took off [user:{$_POST["userid"]}] [usertag:{$_POST["usertagid"]}]", 3);
				echo "success";
			}
		}else{
			echo "No permission!";
		}
	}
?>
