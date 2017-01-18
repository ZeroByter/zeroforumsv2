<?php
    include("../fillin/scripts.php");

    start_session();

    if(isset($_POST["subforum"]) && isset($_POST["subject"]) && isset($_POST["text"])){
        $currAccount = accounts::get_current_account();
		$currUsertags = accounts::get_current_usertags();
		$subforum = forums::get_by_id($_POST["subforum"]);
        if(usertags::user_has_permission($currUsertags, "createthread") && usertags::can_tag_do($currUsertags, $subforum->canpost)){
            $settings = include($_SERVER['DOCUMENT_ROOT'] . "/config.php");

            if($_SESSION["forumsCooldown"] + $settings["forumsCooldown"] - time() < 0){
                $threadID = forums::create_thread($_POST["subforum"], $_POST["subject"], $_POST["text"]);
                echo "success:$threadID";
                accounts::add_post_count($currAccount->id);
				logs::add_log("forums", "$1 posted a thread under [subforum:$subforum->title, $subforum->id]");
            }else{
                echo "Forums cooldown! Please wait " . ($_SESSION["forumsCooldown"] + $settings["forumsCooldown"] - time()) . " seconds before posting on the forums again!";
            }
        }else{
            echo "No permission!";
        }
    }else{
        echo "error:Missing inputs!";
    }
?>
