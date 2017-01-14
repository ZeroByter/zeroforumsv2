<?php
    include("../fillin/scripts.php");

    start_session();

    if(isset($_POST["id"])){
        $currAccount = accounts::get_current_account();
        $thread = forums::get_by_id($_POST["id"]);
        if($thread->deletestatus == 0){
            if($thread->poster == accounts::get_current_account()->id && usertags::user_has_permission(accounts::get_current_usertags(), "deleteownpost") || usertags::user_has_permission(accounts::get_current_usertags(), "deleteposts")){
                $status = 0;
                if($thread->poster == $currAccount->id){
                    $status = 1;
                }else{
                    $status = 2;
                }
                if($status == 0){
                    echo "Error! Delete status is 0!";
                }else{
                    forums::delete($_POST["id"], $status);
                    echo "success";
                }
            }else{
                echo "No permission!";
            }
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
