<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

    if(isset($_POST["time"])){
        if(usertags::user_has_permission(accounts::get_current_usertags(), "settingsTab")){
            $settings = include($_SERVER['DOCUMENT_ROOT'] . "/config.php");

            $settings["updateUsertagsInterval"] = intval($_POST["time"]);

            logs::add_log("settings", "$1 set the [updateUsertagsInterval] to [{$_POST["time"]}]", 10);
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/config.php", "<?php return " . var_export($settings, true) . ";");
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
