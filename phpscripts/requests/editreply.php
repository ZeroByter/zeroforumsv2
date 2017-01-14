<?php
    include("../fillin/scripts.php");

    start_session();

    if(isset($_POST["reply"]) && isset($_POST["text"])){
        if(forums::get_by_id($_POST["reply"])->poster == accounts::get_current_account()->id && usertags::user_has_permission(accounts::get_current_usertags(), "editownpost") || usertags::user_has_permission(accounts::get_current_usertags(), "editposts")){
            forums::edit($_POST["reply"], "", $_POST["text"]);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
