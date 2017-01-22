<?php
    include("../fillin/scripts.php");

    start_session();

    if(isset($_POST["thread"]) && isset($_POST["subject"]) && isset($_POST["text"])){
        $thread = forums::get_by_id($_POST["thread"]);
        if($thread->poster == accounts::get_current_account()->id && usertags::user_has_permission(accounts::get_current_usertags(), "editownpost") || usertags::user_has_permission(accounts::get_current_usertags(), "editposts")){
            forums::edit($_POST["thread"], $_POST["subject"], $_POST["text"]);
            logs::add_log("forums", "$1 editted [thread:$thread->title]", 2);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
