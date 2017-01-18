<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();

    if(isset($_POST["ip"]) && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["dbname"])){
        if(usertags::user_has_permission(accounts::get_current_usertags(), "settingsTab")){
            $settings = include($_SERVER['DOCUMENT_ROOT'] . "/config.php");

            $key = $settings["key"];
            $settings["mysql"]["ip"] = encrypt_text($_POST["ip"], $key);
            $settings["mysql"]["username"] = encrypt_text($_POST["username"], $key);
            $settings["mysql"]["password"] = encrypt_text($_POST["password"], $key);
            $settings["mysql"]["dbname"] = encrypt_text($_POST["dbname"], $key);

            logs::add_log("settings", "$1 set the MySQL configurations", 100);
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/config.php", "<?php return " . var_export($settings, true) . ";");
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
