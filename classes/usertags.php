<?php
    class usertags{
        public function create_default_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS usertags(
                id int(6) NOT NULL auto_increment,
                name varchar(64) NOT NULL,
                permissions text NOT NULL,
                listorder int(6) NOT NULL,
                isstaff boolean NOT NULL,
                isdefault boolean NOT NULL,
                textcolor varchar(32) NOT NULL,
                PRIMARY KEY(id), UNIQUE id (id), KEY id_2 (id))");
            mysqli_close($conn);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id); //get real
            $result = mysqli_query($conn, "SELECT * FROM usertags WHERE id='$id'");
            mysqli_close($conn);

            return mysqli_fetch_object($result);
        }

        public function get_default_usertag(){
            $conn = get_mysql_conn();
            $result = mysqli_query($conn, "SELECT * FROM usertags WHERE isdefault='1'");
            mysqli_close($conn);
            return mysqli_fetch_object($result);
        }

        public function set_default($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $result = mysqli_query($conn, "UPDATE usertags SET isdefault=0");
            $result = mysqli_query($conn, "UPDATE usertags SET isdefault=1 WHERE id='$id'");
            mysqli_close($conn);
        }

        public function toggle_staff($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $result = mysqli_query($conn, "UPDATE usertags SET isstaff=!isstaff WHERE id='$id'");
            mysqli_close($conn);
        }

        public function get_all(){
            $conn = get_mysql_conn();
            $result = mysqli_query($conn, "SELECT * FROM usertags ORDER BY listorder ASC");
            mysqli_close($conn);
            $array = array();

            while($array[] = mysqli_fetch_object($result));
            return array_filter($array);
        }

		public function get_highest_listorder($usertags){
			$listorder = -1;
			foreach($usertags as $value){
				$usertag = self::get_by_id($value);
				if($usertag->listorder >= $listorder){
					$listorder = $usertag->listorder;
				}
			}
			return $listorder;
		}

        public function get_all_limited(){
			$currentlistorder = self::get_highest_listorder(accounts::get_current_usertags());

            if(self::user_has_permission(accounts::get_current_usertags(), "ignoreusertagslistorder")){
                return self::get_all();
            }

            $conn = get_mysql_conn();
            $result = mysqli_query($conn, "SELECT * FROM usertags WHERE listorder<='$currentlistorder' ORDER BY listorder ASC");
            mysqli_close($conn);
            $array = array();
            while($array[] = mysqli_fetch_object($result));
            return $array;
        }

        public function get_all_staff_usertags($sort = "ASC"){
            $conn = get_mysql_conn();
            $sort = mysqli_real_escape_string($conn, $sort);
            $result = mysqli_query($conn, "SELECT * FROM usertags WHERE isstaff = 1 ORDER BY listorder $sort");
            mysqli_close($conn);
            $array = array();
            while($array[] = mysqli_fetch_object($result));
            return $array;
        }

        public function add_usertag($name, $listorder, $isdefault=false, $isstaff=false){
            $conn = get_mysql_conn();
            $name = mysqli_real_escape_string($conn, $name);
            $listorder = mysqli_real_escape_string($conn, $listorder);
            $isdefault = mysqli_real_escape_string($conn, $isdefault);
            $isstaff = mysqli_real_escape_string($conn, $isstaff);
            $result = mysqli_query($conn, "INSERT INTO usertags(name, permissions, listorder, isdefault, isstaff, textcolor) VALUES ('$name', '[]', '$listorder', '$isdefault', '$isstaff', '#000000')");
			$createdID = mysqli_insert_id($conn);
            mysqli_close($conn);
			return $createdID;
        }

        public function delete_usertag($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $result = mysqli_query($conn, "DELETE FROM usertags WHERE id='$id'");
            mysqli_close($conn);

            foreach(accounts::get_all() as $value){
                accounts::take_usertag($value->id, $id);
            }
        }

        public function edit_usertag($id, $name, $listorder, $textcolor="#000000"){
            if(strlen($textcolor) != 7){
                return false;
            }
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $name = mysqli_real_escape_string($conn, $name);
            $listorder = mysqli_real_escape_string($conn, $listorder);
            $result = mysqli_query($conn, "UPDATE usertags SET name='$name',listorder='$listorder',textcolor='$textcolor' WHERE id='$id'");
            mysqli_close($conn);
        }

        public function edit_permissions($id, $permissions){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $result = mysqli_query($conn, "UPDATE usertags SET permissions='$permissions' WHERE id='$id'");
            mysqli_close($conn);
        }

        public function user_has_permission($taglist, $permission, $checkBanned=true){ //can a user with tihs usertag do something based on the tags allowed permissions?
			$permissions = [];
			if(isset($_SESSION["permissions"])){ $permissions = $_SESSION["permissions"]; }
			foreach($permissions as $value){
				$value = str_replace(" ", "", $value);
				if($value == "*"){
					return true;
				}elseif($value == $permission){
					return true;
				}
			}
        	return false;
        }

		public function usertag_has_permission($tagid, $permission, $checkBanned=true){
			$tag = self::get_by_id($tagid);
			$tag_name = $tag->name;
			if($checkBanned){
				if(accounts::is_logged_in() && accounts::get_current_account()->unbantime > 0){
					return false;
				}
			}else{
				if(!accounts::is_logged_in()){
					return false;
				}
			}
			//foreach(json_decode($tag->permissions) as $value){
			foreach($_SESSION["permissions"] as $value){ //based off session
				$value = str_replace(" ", "", $value);
				if($value == "*"){
					return true;
				}elseif($value == $permission){
					return true;
				}
			}
		}

        public function can_tag_do($taglist, $canDoString){ //can the user with this usertag do something simply because of his tag?
        	$currentAccount = accounts::get_current_account();
			$canDoString = json_decode($canDoString);
			if(gettype($canDoString) == "NULL"){
    			$canDoString = array();
    		}else{
    			$canDoString = $canDoString;
    		}
			foreach($canDoString as $value){
				if($value == "all"){
					return true; //if the permissoin string allows all, well, we are a part of 'everyone'... right?
				}
			}
			$usertags = [];
			if(isset($_SESSION["usertags"])){ $usertags = $_SESSION["usertags"]; }
			foreach($usertags as $tag){
        	    $tag = self::get_by_id($tag);
        		$tag_name = $tag->name;
        		foreach($canDoString as $value){
        			$value = str_replace(" ", "", $value);
        			if($value == "all"){
        				return true; //if the permissoin string allows all, well, we are a part of 'everyone'... right?
        			}
        			if($value == "staff" && isset($currentAccount->id)){
        				if($tag->isstaff){
        					return true; //if the permission string only allows staff and our tag is a staff tag, hooray!
        				}
        			}
        			if($value == "registered" && isset($currentAccount->id)){
        				return true; //if the permission string only allows registered people and we are registered, hooray!
        			}
        			/*if($value == "unregistered" && !isset($currentAccount->id)){
        				return true; //disabled due to compability purposes
        			}*/
					if(self::usertag_has_permission($tag->id, "ignorecando")){
						return true;
					}

        			if(is_numeric($value) && isset($currentAccount->id)){
        				if(intval($value) == $tag->id){
        					return true;
        				}
        			}
        		}
        		return false;
        	}
        	return false;
        }

        //I couldn't really think of a shorter way to do this... Brace for the long cancerous functions!
        public function getpermissions(){
            $permissions = [];

			$permissions["other"] = ["Other"];
			$permissions["other"][] = ["ignorecando", "Ignore all 'can do' restrictions"];

            $permissions["forums"] = ["Forums"];
            $permissions["forums"][] = ["createthread", "Create a thread"];
            $permissions["forums"][] = ["deleteownpost", "Delete own posts"];
            $permissions["forums"][] = ["deleteposts", "Delete other's posts"];
            $permissions["forums"][] = ["editownpost", "Edit own posts"];
            $permissions["forums"][] = ["editposts", "Edit other's posts"];
            $permissions["forums"][] = ["lockunlock", "Lock/unlock threads"];
            $permissions["forums"][] = ["pinunpin", "Pin/unpin threads"];
            $permissions["forums"][] = ["hideunhide", "Hide/unhide threads, as well as see hidden threads"];

            $permissions["adminPanel"] = ["Admin Panel"];
            $permissions["adminPanel"][] = ["settingstab", "View the settings tab"];
            $permissions["adminPanel"][] = ["forumstab", "View the forums tab"];
            $permissions["adminPanel"][] = ["userstab", "View the users tab"];
            $permissions["adminPanel"][] = ["usertagstab", "View the usertags tab"];
            $permissions["adminPanel"][] = ["permissionstab", "View the permissions tab"];
            $permissions["adminPanel"][] = ["sessionstab", "View the sessions tab"];
            $permissions["adminPanel"][] = ["logstab", "View the logs tab"];

            $permissions["forumsPanel"] = ["Forums Panel"];
            $permissions["forumsPanel"][] = ["createforums", "Create/delete forums"];
            $permissions["forumsPanel"][] = ["updateforums", "Update forums"];

            $permissions["usersPanel"] = ["Users Panel"];
            $permissions["usersPanel"][] = ["manageusertags", "Manage usertags"];
            $permissions["usersPanel"][] = ["viewwarnings", "View warnings"];
            $permissions["usersPanel"][] = ["viewiphistory", "View IP history"];
            $permissions["usersPanel"][] = ["warnuser", "Warn user"];
            $permissions["usersPanel"][] = ["banuser", "Ban user"];
            $permissions["usersPanel"][] = ["setdisplayname", "Set display name"];
            $permissions["usersPanel"][] = ["setpostcount", "Set posts count"];
            $permissions["usersPanel"][] = ["deleteallposts", "Delete all posts"];

			$permissions["usertagsPanel"] = ["Usertags Panel"];
			$permissions["usertagsPanel"][] = ["ignoreusertagslistorder", "View and edit all usertags regardless of their listorder"];
    		$permissions["usertagsPanel"][] = ["updateusertag", "Update usertags"];
    		$permissions["usertagsPanel"][] = ["makeusertagdefault", "Make a usertag the default usertag"];
    		$permissions["usertagsPanel"][] = ["addusertag", "Create/delete usertags"];

            $permissions["permissionsPanel"] = ["Permissions Panel"];
            $permissions["permissionsPanel"][] = ["updatepermissions", "Update permissions"];

            $permissions["sessionsPanel"] = ["Sessions Panel"];
            $permissions["sessionsPanel"][] = ["deletesession", "Delete session"];

            return $permissions;
        }
    }
?>
