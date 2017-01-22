<?php
    include("../fillin/scripts.php");

    start_session();

    if(isset($_POST["reply"]) && isset($_POST["text"])){
        $reply = forums::get_by_id($_POST["reply"]);
        if($reply->poster == accounts::get_current_account()->id && usertags::user_has_permission(accounts::get_current_usertags(), "editownpost") || usertags::user_has_permission(accounts::get_current_usertags(), "editposts")){
            forums::edit($_POST["reply"], "", $_POST["text"]);
            logs::add_log("forums", "$1 editted [reply:$reply->text]", 2);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
