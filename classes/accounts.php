<?php
    class accounts{
        public function create_default_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS accounts(
                id int(11) NOT NULL auto_increment,
                sessionid varchar(64) NOT NULL,
                lastactive int(11) NOT NULL,
                joined int(11) NOT NULL,
                username varchar(64) NOT NULL,
                displayname varchar(64) NOT NULL,
                password varchar(64) NOT NULL,
                salt varchar(64) NOT NULL,
                tags text NOT NULL,
                email varchar(128) NOT NULL,
                bio varchar(82) NOT NULL,
                posts int(11) NOT NULL,
                bannedtime int(11) NOT NULL,
                unbantime int(11) NOT NULL,
                bannedmsg varchar(128) NOT NULL,
                bannedby int(11) NOT NULL,
                warnings text NOT NULL,
                iplist text NOT NULL,
                privacy text NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            mysqli_close($conn);
        }

        private function generate_salt(){
            return hash("sha256", bin2hex(openssl_random_pseudo_bytes(32)));
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM accounts WHERE id='$id'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

        public function get_by_sessionid($sessionid){
            $conn = get_mysql_conn();
    		$sessionid = mysqli_real_escape_string($conn, $sessionid);
    		$result = mysqli_query($conn, "SELECT * FROM accounts WHERE sessionid='$sessionid'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

        public function get_by_username($username, $caseSensitive=true){
            $conn = get_mysql_conn();
    		$username = mysqli_real_escape_string($conn, $username);
            if($caseSensitive){
                $result = mysqli_query($conn, "SELECT * FROM accounts WHERE username='$username'");
            }else{
                $username = strtolower($username);
                $result = mysqli_query($conn, "SELECT * FROM accounts WHERE LOWER(username)='$username'");
            }
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

        public function get_by_email($email){
            $conn = get_mysql_conn();
    		$email = mysqli_real_escape_string($conn, $email);
    		$result = mysqli_query($conn, "SELECT * FROM accounts WHERE email='$email'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

        public function create_user($username, $password, $displayname="", $email=""){
            $conn = get_mysql_conn();
            $time = time();
            $sessionid = self::generate_salt();
            $salt = self::generate_salt();
            $username = mysqli_real_escape_string($conn, $username);
            $password = mysqli_real_escape_string($conn, hash("sha256", "$password:$salt"));
            $displayname = mysqli_real_escape_string($conn, $displayname);
            $email = mysqli_real_escape_string($conn, $email);
            mysqli_query($conn, "INSERT INTO accounts(
                sessionid,
                lastactive,
                joined,
                username,
                displayname,
                password,
                salt,
                tags,
                bio
            ) VALUES (
                '$sessionid',
                '$time',
                '$time',
                '$username',
                '$displayname',
                '$password',
                '$salt',
                '[".usertags::get_default_usertag()->id."]',
                'Welcome to my profile!'
            )");
            $createdID = mysqli_insert_id($conn);
            mysqli_close($conn);
            return $createdID;
        }

        public function is_logged_in(){
            if(isset($_SESSION["sessionid"])){
                $is_correct = isset(self::get_by_sessionid($_SESSION["sessionid"])->id);
                if(!$is_correct){
                    unset($_SESSION["sessionid"]);
                }
                return $is_correct;
            }else{
                return false;
            }
        }

        public function get_current_account(){
            if(self::is_logged_in()){
                return self::get_by_sessionid($_SESSION["sessionid"]);
            }
        }

        public function get_display_name($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT username,displayname FROM accounts WHERE id='$id'");
    		mysqli_close($conn);
    		$object = mysqli_fetch_object($result);

			if($object->displayname != ""){
				return filterXSS($object->displayname);
			}else{
				return filterXSS($object->username);
			}
        }

        public function login($username, $password){
            $conn = get_mysql_conn();
            $username = mysqli_real_escape_string($conn, $username);
            $password = mysqli_real_escape_string($conn, $password);

            $account = null;
            if(self::get_by_username($username)){
                $account = self::get_by_username($username);
            }elseif($username !== "" && self::get_by_email($username)){
                $account = self::get_by_email($username);
            }else{
                mysqli_close($conn);
                return "error:No accout found by that username!";
            }

            if($account != null){
                if(hash("sha256", "$password:$account->salt") == $account->password){
                    start_session();

                    $newSessionID = hash("sha256", self::generate_salt());
                    mysqli_query($conn, "UPDATE accounts SET sessionid='$newSessionID' WHERE id='$account->id'");
                    $_SESSION["sessionid"] = $newSessionID;
                    mysqli_close($conn);
                    return "success";
                }else{
                    //wrong password
                    mysqli_close($conn);
                    return "error:Wrong password!";
                }
            }else{
                //no account exists?!
                mysqli_close($conn);
                return "error:No account exists by that username/password!";
            }
        }

        public function get_user_tags($id){
            if(self::is_logged_in()){
                $conn = get_mysql_conn();
                $id = mysqli_real_escape_string($conn, $id);

        		$result = mysqli_query($conn, "SELECT tags FROM accounts WHERE id='$id'");
        		$array = json_decode(mysqli_fetch_object($result)->tags);
                mysqli_close($conn);

        		if(gettype($array) == "NULL"){
        			return array();
        		}else{
        			return $array;
        		}

            }else{
                return usertags::get_default_usertag();
            }
        }

        public function add_usertag($id, $tagID){
            $tagID = intval($tagID);
            $tagsArray = self::get_usertags($id);
    		$isAlreadyStored = false;

    		foreach($tagsArray as $value){
    			if($value == $tagID){
    				$isAlreadyStored = true;
    			}
    		}

    		if($isAlreadyStored == false){
    			array_push($tagsArray, $tagID);
    		}

    		$tagsArray = json_encode($tagsArray);

    		$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$tagsArray = mysqli_real_escape_string($conn, $tagsArray);
    		mysqli_query($conn, "UPDATE accounts SET tags='$tagsArray' WHERE id='$id'");
    		mysqli_close($conn);
    	}
    }
?>
