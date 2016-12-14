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

        public function get_all_usertags(){
            $conn = get_mysql_conn();
            $result = mysqli_query($conn, "SELECT * FROM usertags ORDER BY listorder ASC");
            mysqli_close($conn);
            $array = array();

            while($array[] = mysqli_fetch_object($result));
            return $array;
        }

        function get_all_usertags_limited(){
            $currentlistorder = self::get_usertag_by_id(accounts::get_current_usertag())->listorder;
            if(self::tag_has_permission(accounts::get_current_usertag(), "adminpnl_ignore_usertag_limit")){
                return self::get_all_usertags();
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
            $id = mysqli_real_escape_string($conn, $sort);
            $result = mysqli_query($conn, "SELECT * FROM usertags WHERE isstaff = 1 ORDER BY listorder $sort");
            mysqli_close($conn);
            $array = array();
            while($array[] = mysqli_fetch_object($result));
            return $array;
        }

        public function add_usertag($name, $permissions, $listorder, $isdefault=false, $isstaff=false){
            $conn = get_mysql_conn();
            $name = mysqli_real_escape_string($conn, $name);
            $permissions = mysqli_real_escape_string($conn, $permissions);
            $listorder = mysqli_real_escape_string($conn, $listorder);
            $isdefault = mysqli_real_escape_string($conn, $isdefault);
            $isstaff = mysqli_real_escape_string($conn, $isstaff);
            $result = mysqli_query($conn, "INSERT INTO usertags(name, permissions, listorder, isdefault, isstaff) VALUES ('$name', '$permissions', '$listorder', '$isdefault', '$isstaff')");
            mysqli_close($conn);
        }

        public function edit_usertag($id, $name, $listorder, $isstaff){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $name = mysqli_real_escape_string($conn, $name);
            $listorder = mysqli_real_escape_string($conn, $listorder);
            $isstaff = mysqli_real_escape_string($conn, $isstaff);
            $result = mysqli_query($conn, "UPDATE usertags SET name='$name',listorder='$listorder',isstaff='$isstaff' WHERE id='$id'");
            mysqli_close($conn);
        }

        public function user_has_permission($userid, $permission){ //can a user with tihs usertag do something based on the tags allowed permissions?
        	$taglist = accounts::get_user_tags($userid);

        	foreach($taglist as $tag){
        	    $tag = self::get_by_id($tag);
        		$tag_name = $tag->name;
        		if(accounts::get_current_account()->unbantime > 0){
        			return false;
        		}
        		$conn = get_mysql_conn();
        		$permissions_query = mysqli_query($conn, "SELECT permissions FROM usertags WHERE listorder <= '$tag->listorder'");
        		while($permissions_q_array[] = mysqli_fetch_object($permissions_query));
        		foreach($permissions_q_array as $value_perm){
                    if($value_perm){
                        foreach(json_decode($value_perm->permissions) as $value){
                            $value = str_replace(" ", "", $value);
                            if($value == "*"){
                                mysqli_close($conn);
                                return true;
                            }elseif($value == $permission){
                                mysqli_close($conn);
                                return true;
                            }
                        }
                    }
        		}
        		mysqli_close($conn);
        		return false;
        	}
        	return false;
        }

        public function can_tag_do($userid, $canDoString){ //can the user with this usertag do something simply because of his tag?
            $taglist = accounts::get_user_tags($userid);

        	$currentAccount = accounts::get_current_account();
        	foreach($taglist as $tag){
        	    $tag = self::get_by_id($tag);
        		$tag_name = $tag->name;
        		foreach(json_decode($canDoString) as $value){
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

        			if(is_numeric($value) && isset($currentAccount->id)){
        				if(intval($value) == $tag->id){
        					return true; //still have to work out the whole mulitple tags business
        				}
        			}
        		}
        		return false;
        	}
        	return false;
        }

        //I couldn't really think of a shorter way to do this... Brace for the long cancerous functions!
        public function permissions(){
            $permissions = [];
            $permissionsInfo = [];

            $permissions[] = "forums_createpost";
            $permissions[] = "forums_deleteownpost";
            $permissions[] = "forums_deleteposts";

            return [$permissions, $permissionsInfo];
        }
    }
?>
