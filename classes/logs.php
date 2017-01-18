<?php
    class logs{
        public function create_default_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS logs(
                id int(11) NOT NULL auto_increment,
                owner int(11) NOT NULL,
                ip varchar(64) NOT NULL,
                time int(11) NOT NULL,
                title varchar(128) NOT NULL,
                description text NOT NULL,
                level int(11) NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            mysqli_close($conn);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM logs WHERE id='$id'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

		public function get_all(){
			$conn = get_mysql_conn();
            $result = mysqli_query($conn, "SELECT * FROM logs ORDER BY time DESC");
            mysqli_close($conn);
            $array = array();

            while($array[] = mysqli_fetch_object($result));
            return array_filter($array);
		}

        public function get_all_by_date($date){
			$conn = get_mysql_conn();
            $result = mysqli_query($conn, "SELECT * FROM `logs` WHERE DATE_FORMAT(FROM_UNIXTIME(`time`), '%e/%c/%Y')='$date' ORDER BY `time` DESC");
            mysqli_close($conn);
            $array = array();

            while($array[] = mysqli_fetch_object($result));
            return $array;
		}

        public function get_all_alt(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT id,time FROM logs ORDER BY time DESC");
    		mysqli_close($conn);
    		while($array[] = mysqli_fetch_object($result));
            $finalArray = [];

            foreach($array as $value){
                if($value){
                    $finalArray[$value->id] = timestamp_to_date($value->time);
                }
            }
            $finalArray = array_unique($finalArray);

            return $finalArray;
        }

        public function add_log($title, $description, $level=1, $overrideOwner=null){
            $ip = $_SERVER["REMOTE_ADDR"];

            if(accounts::is_logged_in()){
                $currAccount = accounts::get_current_account();
                $owner = $currAccount->id;
                $username = $currAccount->username;
            }else{
                $owner = 0;
                $username = $ip;
            }
			if(isset($overrideOwner)){
				$owner = $overrideOwner;
			}

            $conn = get_mysql_conn();
            $title = mysqli_real_escape_string($conn, $title);
            $description = mysqli_real_escape_string($conn, $description);
            $description = json_encode(preg_replace("/\\$1/", $username, $description));
            $description = substr($description, 1, -1);
            $time = time();
            $result = mysqli_query($conn, "INSERT INTO logs(owner, ip, time, title, description, level) VALUES ('$owner', '$ip', '$time', '$title', '$description', '$level')");
            mysqli_close($conn);
        }
    }
?>
