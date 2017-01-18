<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

    if(isset($_POST["cooldown"])){
        if(usertags::user_has_permission(accounts::get_current_usertags(), "settingsTab")){
            $settings = include($_SERVER['DOCUMENT_ROOT'] . "/config.php");

            $settings["forumsCooldown"] = intval($_POST["cooldown"]);

            logs::add_log("settings", "$1 set the [forumsCooldown] to [{$_POST["cooldown"]}]", 10);
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/config.php", "<?php return " . var_export($settings, true) . ";");
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
