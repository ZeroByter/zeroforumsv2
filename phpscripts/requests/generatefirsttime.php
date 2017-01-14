<?php
    include("../fillin/scripts.php");

    function rand_sha1($length=32){
        $max = ceil(32 / 40);
        $random = '';
        for ($i = 0; $i < $max; $i++) {
            $random .= sha1(microtime(true) . mt_rand(10000, 90000));
        }
        return substr($random, 0, $length);
    }

    if(!file_exists($_SERVER['DOCUMENT_ROOT'] . "/config.php")){
        $conn = get_mysql_conn();
        $result = mysqli_query($conn, "SHOW TABLES");
        if($result->num_rows <= 0){
            $config = array();
            $config["cookie_id"] = rand_sha1(6);
            $config["key"] = rand_sha1();
            $config["mysql"] = array();
            $config["mysql"]["ip"] = encrypt_text($_POST["mysqlIP"], $config["key"]);
            $config["mysql"]["username"] = encrypt_text($_POST["mysqlUsername"], $config["key"]);
            $config["mysql"]["password"] = encrypt_text($_POST["mysqlPassword"], $config["key"]);
            $config["mysql"]["dbname"] = encrypt_text($_POST["mysqlDBName"], $config["key"]);
            $config["forumsCooldown"] = 30;

            file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/config.php", "<?php return " . var_export($config, true) . ";");

            logs::create_default_db();
            sessions::create_default_db();
            accounts::create_default_db();
            usertags::create_default_db();
            usertags::add_usertag("User", ["createthread", "editownpost"], 1, true, false);
            $superAdmin = usertags::add_usertag("Super Admin", ["*"], 2, false, true);
            $createdUser = accounts::create_user($_POST["defaultUsername"], $_POST["defaultPassword"], "", "", false);
            accounts::add_usertag($createdUser, $superAdmin);
            accounts::login($_POST["defaultUsername"], $_POST["defaultPassword"]);
            forums::create_default_db(true);
        }
        mysqli_close($conn);
    }
?>
