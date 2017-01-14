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
            //session_set_cookie_params(1209600);
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

    function get_human_time($time){
        $time = time() - $time;
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }

    function timestamp_to_date($timestamp, $withtime=false){
        if($withtime){
            return getdate($timestamp)["mday"] . "/" . getdate($timestamp)["mon"] . "/" . getdate($timestamp)["year"] . " " . getdate($timestamp)["hours"] . ":" . getdate($timestamp)["minutes"] . ":" . getdate($timestamp)["seconds"];
        }else{
            return getdate($timestamp)["mday"] . "/" . getdate($timestamp)["mon"] . "/" . getdate($timestamp)["year"];
        }
    }

    function removeHTMLElement($identifier){
        echo "<script id='remove_script'>
            $('$identifier').remove()
            $('#remove_script').remove()
        </script>";
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
