<?php
    include("../fillin/scripts.php");

    start_session();

	if(accounts::is_logged_in()){
		if(isset($_POST["displayName"])){
			if(strlen($_POST["displayName"]) >= 12){
				echo "Display name is too long! The maximum is 12 charachters.";
				return;
			}
			accounts::changeDisplayName(accounts::get_current_account()->id, $_POST["displayName"]);
			echo "success";
		}else{
			echo "Missing inputs!";
		}
	}
?>
