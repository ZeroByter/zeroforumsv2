<?php
    include("../fillin/scripts.php");

    start_session();

    if(isset($_POST["id"])){
        if(usertags::user_has_permission(accounts::get_current_usertags(), "lockunlock")){
            forums::toggle_thread_lock($_POST["id"]);
            $thread = forums::get_by_id($_POST["id"]);
            if($thread->locked){
                logs::add_log("forums", "$1 toggled off the lock for [thread:$thread->title]", 2);
            }else{
                logs::add_log("forums", "$1 toggled on the lock for [thread:$thread->title]", 2);
            }
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
