<?php
    include("../fillin/scripts.php");

    start_session();

	if(accounts::is_logged_in()){
		if(isset($_POST["username"])){
            $oldUsername = accounts::get_current_account()->username;
			accounts::changeUsername($_POST["username"]);
            logs::add_log("account", "$1 changed his username from [oldusername:$oldUsername] to [newusername:{$_POST["username"]}]", 2);
			echo "success";
		}else{
			echo "Missing inputs!";
		}
	}
?>
