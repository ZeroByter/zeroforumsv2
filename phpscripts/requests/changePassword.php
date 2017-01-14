<?php
    include("../fillin/scripts.php");

    start_session();
	
	if(accounts::is_logged_in()){
		if(isset($_POST["password"])){
			accounts::changePassword($_POST["password"]);
			echo "success";
		}else{
			echo "Missing inputs!";
		}
	}
?>