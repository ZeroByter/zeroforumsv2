<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
    start_session();
    $currUsertags = [];
    if(accounts::is_logged_in()){
        $currAccount = accounts::get_current_account();
        $currUsertags = accounts::get_current_usertags();
    }

    $bannedAccount = accounts::get_by_id($_GET["id"]);
    echo "
        Banned by ".accounts::get_display_name($bannedAccount->bannedby)."<br>
        Banned on the '".timestamp_to_date($bannedAccount->bannedtime, true)."' for '".$bannedAccount->bannedmsg."'<br>
        Ban expires on the '".timestamp_to_date($bannedAccount->unbantime, true)."'
    ";
?>
