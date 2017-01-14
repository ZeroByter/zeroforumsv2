<?php
    include("../fillin/scripts.php");
    start_session();

    if(accounts::is_logged_in()){
        $currAccount = accounts::get_current_account();
        accounts::clear_warnings($currAccount->id);
    }
?>
