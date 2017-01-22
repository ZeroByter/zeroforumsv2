<?php
    include("../fillin/scripts.php");

    start_session();

	if(accounts::is_logged_in()){
		if(isset($_POST["displayName"])){
			if(strlen($_POST["displayName"]) >= 12){
				echo "Display name is too long! The maximum is 12 charachters.";
				return;
			}
            $oldDisplayName = accounts::get_current_account()->displayname;
			accounts::changeDisplayName(accounts::get_current_account()->id, $_POST["displayName"]);
            logs::add_log("account", "$1 changed his displayname from [olddisplayname:$oldDisplayName] to [newdisplayname:{$_POST["displayName"]}]", 3);
			echo "success";
		}else{
			echo "Missing inputs!";
		}
	}
?>
