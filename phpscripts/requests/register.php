<?php
    include("../fillin/scripts.php");

    if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["displayname"]) && isset($_POST["email"])){
		$errors = [];
		if(preg_match("/\s/", $_POST["username"])){
			array_push($errors, "You can't have any spaces in your username!");
		}
		if(strlen($_POST["password"]) < 5){
			array_push($errors, "Password is less than 5 charachters!");
		}
		if(strlen($_POST["displayname"]) >= 12){
			array_push($errors, "Display name is too long! The maximum is 12 charachters.");
		}
		if(accounts::get_by_username($_POST["username"], false) !== null){
			array_push($errors, "Username is already taken!");
		}
		foreach($errors as $value){
			echo $value;
			break;
		}

		if(count($errors) == 0){
			$login = accounts::create_user($_POST["username"], $_POST["password"], $_POST["displayname"]);
			accounts::login($_POST["username"], $_POST["password"]);
			logs::add_log("register", "$1 registered an account");
			echo "success";
		}
    }else{
        echo "Missing inputs!";
    }
?>
