<?php
    include("../fillin/scripts.php");

    start_session();

    if(isset($_POST["parent"]) && isset($_POST["text"])){
        $currAccount = accounts::get_current_account();
        if(usertags::user_has_permission(accounts::get_current_usertags(), "createthread")){
            if(forums::get_by_id($_POST["parent"])->locked && !usertags::user_has_permission(accounts::get_current_usertags(), "lockunlock")){
                echo "No permission!";
                return;
            }

            $settings = include($_SERVER['DOCUMENT_ROOT'] . "/config.php");
            if($_SESSION["forumsCooldown"] + $settings["forumsCooldown"] - time() < 0){
                forums::create_reply($_POST["parent"], $_POST["text"]);
                accounts::add_post_count($currAccount->id);
                $_SESSION["forumsCooldown"] = time();
                echo "success";
				$thread = forums::get_by_id($_POST["parent"]);
				logs::add_log("forums", "$1 posted a reply under [thread:$thread->title, $thread->id]");
            }else{
                echo "Forums cooldown! Please wait " . ($_SESSION["forumsCooldown"] + $settings["forumsCooldown"] - time()) . " seconds before posting on the forums again!";
            }
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
