<?php
    include("../fillin/scripts.php");
    start_session();

    if(accounts::is_logged_in()){
        $currAccount = accounts::get_current_account();
        if(count(accounts::get_warnings($currAccount->id)) > 0){
            accounts::clear_warnings($currAccount->id);
            logs::add_log("warnings", "$1 cleared his warnings", 1);
        }
    }
?>
