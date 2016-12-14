<?php
    include("../fillin/scripts.php");

    if(isset($_POST["username"]) && isset($_POST["password"])){
        $login = accounts::login($_POST["username"], $_POST["password"]);
        echo $login;
    }else{
        echo "error:Missing inputs!";
    }
?>
