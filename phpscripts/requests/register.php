<?php
    include("../fillin/scripts.php");

    if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["displayname"]) && isset($_POST["email"])){
        if(accounts::get_by_username($_POST["username"], false) !== null){
            echo "error:Username is already taken!";
        }else{
            $login = accounts::create_user($_POST["username"], $_POST["password"], $_POST["displayname"]);
            accounts::login($_POST["username"], $_POST["password"]);
            echo "success";
        }
    }else{
        echo "error:Missing inputs!";
    }
?>
