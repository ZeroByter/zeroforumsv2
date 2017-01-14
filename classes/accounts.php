<?php
    class accounts{
        public function create_default_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS accounts(
                id int(11) NOT NULL auto_increment,
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

        public function get_all(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT id FROM accounts");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_all_banned(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT id FROM accounts WHERE bannedtime - unbantime > 0");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));
			foreach(array_filter($array) as $value){
				self::confirm_ban($value->id);
			}

            return array_filter($array);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM accounts WHERE id='$id'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

        public function get_by_sessionid($sessionid){
            return @self::get_by_id(sessions::get_session($sessionid)->accountid);
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

		public function get_by_usertag($usertag){
            $conn = get_mysql_conn();
    		$usertag = mysqli_real_escape_string($conn, $usertag);
    		$result = mysqli_query($conn, "SELECT * FROM accounts WHERE tags REGEXP '$usertag'");
    		mysqli_close($conn);
			while($array[] = mysqli_fetch_object($result));
			foreach(array_filter($array) as $value){
				self::confirm_ban($value->id);
			}

            return $array;
		}

        public function create_user($username, $password, $displayname="", $email="", $assigndefaulttag=true){
            $conn = get_mysql_conn();
            $time = time();
            $salt = self::generate_salt();
            $username = mysqli_real_escape_string($conn, $username);
            $password = mysqli_real_escape_string($conn, hash("sha256", "$password:$salt"));
            $displayname = mysqli_real_escape_string($conn, $displayname);
            $email = mysqli_real_escape_string($conn, $email);
            $defaulttag = "";
            if($assigndefaulttag){
                $defaulttag = usertags::get_default_usertag()->id;
            }
            mysqli_query($conn, "INSERT INTO accounts(
                lastactive,
                joined,
                username,
                displayname,
                password,
                salt,
                tags,
                bio
            ) VALUES (
                '$time',
                '$time',
                '$username',
                '$displayname',
                '$password',
                '$salt',
                '[$defaulttag]',
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
            if($id <= -1){
                return "ZeroForums";
            }else{
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
                logs::add_log("login", "$1 tried to login with non-existant information: [username:$username], [password:$password]");
                return "No account found by that username!";
            }

            if($account != null){
                if(hash("sha256", "$password:$account->salt") == $account->password){
                    start_session();

                    $sessionID = sessions::get_by_id(sessions::add_session($account->id))->sessionid;
                    $_SESSION["sessionid"] = $sessionID;
                    $_SESSION["forumsCooldown"] = 0;
					mysqli_query($conn, "UPDATE accounts SET lastactive='" . time() . "' WHERE id='$account->id'");
                    mysqli_close($conn);
                    self::add_user_iplist($account->id);
                    logs::add_log("login", "$1 logged in with [username:$username] and [password:*****]");
                    return "success";
                }else{
                    //wrong password
                    mysqli_close($conn);
                    logs::add_log("login", "$1 tried to login to [username:$username] with the wrong [password:*****]", 1, $account->id);
                    return "Wrong password!";
                }
            }else{
                //no account exists?!
                mysqli_close($conn);
                logs::add_log("login", "$1 tried to login with non-existant information: [username:$username], [password:$password]");
                return "No account exists by that username/password!";
            }
        }

        public function get_warnings($id){
    		$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT warnings FROM accounts WHERE id='$id'");
    		mysqli_close($conn);
    		$warningsArray = json_decode(mysqli_fetch_object($result)->warnings);

    		if(gettype($warningsArray) == "NULL"){
    			return array();
    		}else{
    			return $warningsArray;
    		}
    	}

        public function add_warning($id, $reason){
    		$warningsArray = self::get_warnings($id);

    		array_push($warningsArray, array(
    			"warnedby" => self::get_current_account()->id,
    			"time" => time(),
    			"message" => $reason,
    		));

    		$warningsArray = json_encode($warningsArray);

    		$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$reason = mysqli_real_escape_string($conn, $reason);
    		mysqli_query($conn, "UPDATE accounts SET warnings='$warningsArray' WHERE id='$id'");
    		mysqli_close($conn);
    	}

        public function clear_warnings($id){
    		$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		mysqli_query($conn, "UPDATE accounts SET warnings='' WHERE id='$id'");
    		mysqli_close($conn);
    	}

        public function get_user_tags($id){
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
        }

        public function get_current_usertags(){
            if(self::is_logged_in()){
                return self::get_user_tags(self::get_current_account()->id);
            }else{
                return null;
            }
        }

        public function is_staff($id){
            foreach(self::get_user_tags($id) as $value){
                if($value){
                    if(usertags::get_by_id($value)->isstaff){
                        return true;
                    }
                }
            }
            return false;
        }

        public function get_current_usertags_or_default(){
            if(self::is_logged_in()){
                return self::get_user_tags(self::get_current_account()->id);
            }else{
                $returnArray = [];
                $returnArray[] = intval(usertags::get_default_usertag()->id);
                return $returnArray;
            }
        }

        public function add_usertag($id, $tagID){
            $tagID = intval($tagID);
            $tagsArray = self::get_user_tags($id);
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

        public function take_usertag($id, $tagID){
            $tagID = intval($tagID);
            $tagsArray = self::get_user_tags($id);
            $newArray = [];

            foreach($tagsArray as $value){
    			if($value != $tagID){
                    array_push($newArray, $value);
    			}
    		}

            if(count($newArray) <= 0){
                array_push($newArray, usertags::get_default_usertag()->id);
            }

            $newArray = json_encode($newArray);

            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$newArray = mysqli_real_escape_string($conn, $newArray);
    		mysqli_query($conn, "UPDATE accounts SET tags='$newArray' WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function add_post_count($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "UPDATE accounts SET posts=posts+1 WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function set_post_count($id, $count){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$count = mysqli_real_escape_string($conn, $count);
    		$result = mysqli_query($conn, "UPDATE accounts SET posts=$count WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function get_iplist($id){
    		$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT iplist FROM accounts WHERE id='$id'");
    		mysqli_close($conn);
    		$iplistArray = json_decode(mysqli_fetch_object($result)->iplist);

    		if(gettype($iplistArray) == "NULL"){
    			return array();
    		}else{
    			return $iplistArray;
    		}
    	}

        public function add_user_iplist($id){
    		$iplistArray = self::get_iplist($id);
    		$isAlreadyStored = false;

    		foreach($iplistArray as $key=>$value){
    			if($value->ip == $_SERVER['REMOTE_ADDR']){
    				$isAlreadyStored = true;
    				$value->lastseen = time();
    			}
    		}

    		if($isAlreadyStored == false){
    			array_push($iplistArray, array(
    				"ip" => $_SERVER['REMOTE_ADDR'],
    				"firstseen" => time(),
    				"lastseen" => time(),
    			));
    		}

    		$iplistArray = json_encode($iplistArray);

    		$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		mysqli_query($conn, "UPDATE accounts SET iplist='$iplistArray' WHERE id='$id'");
    		mysqli_close($conn);
    	}

        public function does_list_contain_ip($list, $ip){
    		foreach($ip as $value){
    			foreach($list as $value2){
    				if($value2->ip == $value->ip){
    					return true;
    				}
    			}
    		}
    		return false;
    	}

        public function get_all_with_ip($id, $exempid=0){
            $returnlist = array();
            $iplist = self::get_iplist($id);
            foreach(self::get_all() as $value){
    			if($value){
    				if($exempid > 0){
    					if($exempid == $value->id){
    						continue;
    					}
    				}
    				if(self::does_list_contain_ip(self::get_iplist($value->id), $iplist)){
    					$returnlist[] = $value;
    				}
    			}
    		}
    		return $returnlist;
        }

		public function unban($id){
			$conn = get_mysql_conn();
			$id = mysqli_real_escape_string($conn, $id);
			mysqli_query($conn, "UPDATE accounts SET bannedtime='0',unbantime='0',bannedmsg='',bannedby='0' WHERE id='$id'");
			mysqli_close($conn);
		}

		public function issue_ban($id, $reason, $time){
			$conn = get_mysql_conn();
			$id = mysqli_real_escape_string($conn, $id);
			$reason = mysqli_real_escape_string($conn, $reason);
			$time = mysqli_real_escape_string($conn, $time);
			$currTime = time();
			$unbanTime = $currTime + $time;
			$currAccount = self::get_current_account()->id;
			mysqli_query($conn, "UPDATE accounts SET bannedtime='$currTime',unbantime='$unbanTime',bannedmsg='$reason',bannedby='$currAccount' WHERE id='$id'");
			mysqli_close($conn);
		}

		public function confirm_ban($id){
			$account = self::get_by_id($id);
			if($account->bannedby != 0){
				if($account->unbantime < time()){
					self::unban($id);
					return false;
				}else{
					return true;
				}
			}else{
				return false;
			}
		}

		public function changeUsername($username){ //Not asking for ID because we don't want someone editing someone else's username
			$currAccount = self::get_current_account();

			$conn = get_mysql_conn();
    		$username = mysqli_real_escape_string($conn, $username);
    		mysqli_query($conn, "UPDATE accounts SET username='$username' WHERE id='$currAccount->id'");
    		mysqli_close($conn);
		}

		public function changeDisplayName($id, $displayName){
			$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$displayName = mysqli_real_escape_string($conn, $displayName);
    		mysqli_query($conn, "UPDATE accounts SET displayname='$displayName' WHERE id='$id'");
    		mysqli_close($conn);
		}

		public function changePassword($password){ //Not asking for ID because we don't want someone editing someone else's password
			$currAccount = self::get_current_account();

			$newPassword = hash("sha256", "$password:$currAccount->salt");
			$conn = get_mysql_conn();
    		$newPassword = mysqli_real_escape_string($conn, $newPassword);
    		mysqli_query($conn, "UPDATE accounts SET password='$newPassword' WHERE id='$currAccount->id'");
    		mysqli_close($conn);
		}
    }
?>
