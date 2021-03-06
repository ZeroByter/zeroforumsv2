<?php
    include("../fillin/scripts.php");

    start_session();

    if(isset($_POST["id"])){
        if(usertags::user_has_permission(accounts::get_current_usertags(), "pinunpin")){
            forums::toggle_thread_pin($_POST["id"]);
            $thread = forums::get_by_id($_POST["id"]);
            if($thread->pinned){
                logs::add_log("forums", "$1 toggled off the pin for [thread:$thread->title]", 2);
            }else{
                logs::add_log("forums", "$1 toggled on the pin for [thread:$thread->title]", 2);
            }
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
