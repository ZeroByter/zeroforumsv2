<?php
    include("../fillin/scripts.php");

    start_session();

    if(isset($_POST["id"])){
        if(usertags::user_has_permission(accounts::get_current_usertags(), "lockunlock")){
            forums::toggle_thread_lock($_POST["id"]);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
