<?php
    function encrypt_text($text, $salt){
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    function decrypt_text($text, $salt){
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    function start_session(){
        $settings = array();
    	$cookie_id = "";
    	if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/config.php")){
    		$settings = include($_SERVER['DOCUMENT_ROOT'] . "/config.php");
    		$cookie_id = $settings["cookie_id"];
    		session_name("zeroforumsv2_$cookie_id");
            session_set_cookie_params(1209600);
            session_start();
    	}
        return $cookie_id;
    }

    function get_mysql_conn(){
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/config.php")){
            $settings = include($_SERVER['DOCUMENT_ROOT'] . "/config.php");
            $conn = mysqli_connect(decrypt_text($settings["mysql"]["ip"], $settings["key"]), decrypt_text($settings["mysql"]["username"], $settings["key"]), decrypt_text($settings["mysql"]["password"], $settings["key"]), decrypt_text($settings["mysql"]["dbname"], $settings["key"]));
            return $conn;
        }else{
            return false;
        }
    }

    function redirectWindow($link){
        echo "<script id='remove_script'>
            window.location = '$link';
            $('#remove_script').remove()
        </script>";
    }

    function filterXSS($string){
        $string = htmlspecialchars($string);
        $string = str_replace("javascript:", "javascript : ", $string);
        return $string;
    }
?>
