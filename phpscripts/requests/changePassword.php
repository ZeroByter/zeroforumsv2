<?php
    include("../fillin/scripts.php");

    start_session();

	if(accounts::is_logged_in()){
		if(isset($_POST["password"])){
			accounts::changePassword($_POST["password"]);
            logs::add_log("account", "$1 changed his password", 10);
			echo "success";
		}else{
			echo "Missing inputs!";
		}
	}
?>
