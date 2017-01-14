<?php
    include("../fillin/scripts.php");

    start_session();
	
	if(accounts::is_logged_in()){
		if(isset($_POST["username"])){
			accounts::changeUsername($_POST["username"]);
			echo "success";
		}else{
			echo "Missing inputs!";
		}
	}
?>