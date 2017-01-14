<?php
    class forums{
        public function create_default_db($generate_setup=false){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS forums(
                id int(6) NOT NULL auto_increment,
                deletestatus int(6) NOT NULL,
                deletedby int(6) NOT NULL,
                icon text NOT NULL,
                views int(6) NOT NULL,
                replies int(6) NOT NULL,
                firstposted int(8) NOT NULL,
                lastactive int(8) NOT NULL,
                lastedited int(8) NOT NULL,
                lastediteduser int(6) NOT NULL,
                type varchar(32) NOT NULL,
                title varchar(64) NOT NULL,
                text text NOT NULL,
                poster int(6) NOT NULL,
                parent int(6) NOT NULL,
                listorder int(6) NOT NULL,
                locked boolean NOT NULL,
                hidden boolean NOT NULL,
                pinned boolean NOT NULL,
                PRIMARY KEY(id), UNIQUE id (id), KEY id_2 (id))");

            if($generate_setup){
                mysqli_query($conn, "INSERT INTO forums(firstposted, lastactive, type, title, text, poster) VALUES ('".time()."', '".time()."', 'forum', 'Welcome to zeroforumsv2', '', '-1')");
                mysqli_query($conn, "INSERT INTO forums(firstposted, lastactive, type, parent, title, text, poster) VALUES ('".time()."', '".time()."', 'subforum', 1, 'Your very first subforum!', 'Click me for more info.', '-1')");
                mysqli_query($conn, "INSERT INTO forums(firstposted, lastactive, type, parent, title, text, poster) VALUES ('".time()."', '".time()."', 'thread', 2, 'Open me!', '[center][b]Congratulations on settings up your very first forum website![/b][/center]

Now that your website is setup, you might be interesting in modifying the existent usertags, or possibly creating new ones of your own.
You probably don\'t want to keep just one forum and subforum in your website. We [b]highly[/b] recommend creating more forums and subforums to suit your needs.

To do all this, click the \'[u]Admin Panel[/u]\' button on the top-left corner of your screen, a new window will appear with different tabs for different tasks, click on any of them to view what they are and what they do!

[b][i]More explained soon...[/i][/b]', -1)");
                self::toggle_thread_lock(3);
                self::toggle_thread_pin(3);
            }
            mysqli_close($conn);
        }

        public function get_all_forums(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM forums WHERE type='forum' ORDER BY listorder ASC");
    		mysqli_close($conn);

            if(mysqli_num_rows($result) === 0){
                return array();
            }else{
                while($array[] = mysqli_fetch_object($result));
                return array_filter($array);
            }
        }

        public function get_all_subforums($parent){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM forums WHERE type='subforum' && parent='$parent' ORDER BY listorder ASC");
    		mysqli_close($conn);

            if(mysqli_num_rows($result) === 0){
                return array();
            }else{
                while($array[] = mysqli_fetch_object($result));
                return array_filter($array);
            }
        }

        public function get_all_threads($parent){
            $conn = get_mysql_conn();
            if($parent == 0){
                $result = mysqli_query($conn, "SELECT * FROM forums WHERE type='thread' ORDER BY lastactive DESC");
            }else{
                $result = mysqli_query($conn, "SELECT * FROM forums WHERE type='thread' && pinned='0' && parent='$parent' ORDER BY lastactive DESC");
            }
    		mysqli_close($conn);

            if(mysqli_num_rows($result) === 0){
                return array();
            }else{
                while($array[] = mysqli_fetch_object($result));
                return $array;
            }
        }

        public function get_all_pinned_threads($parent){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM forums WHERE type='thread' && pinned='1' && parent='$parent' ORDER BY lastactive DESC");
    		mysqli_close($conn);

            if(mysqli_num_rows($result) === 0){
                return array();
            }else{
                while($array[] = mysqli_fetch_object($result));
                return $array;
            }
        }

        public function get_all_replies($parent){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM forums WHERE type='reply' && parent='$parent' ORDER BY firstposted ASC");
    		mysqli_close($conn);

            if(mysqli_num_rows($result) === 0){
                return array();
            }else{
                while($array[] = mysqli_fetch_object($result));
                return $array;
            }
        }

        public function get_last_thread($parent=0){
            $conn = get_mysql_conn();
            $parent = mysqli_real_escape_string($conn, $parent);
            if($parent == 0){
                $result = mysqli_query($conn, "SELECT * FROM forums WHERE type='thread' ORDER BY lastactive DESC");
            }else{
                //if(tag_has_permission(get_current_usertag(), "forums_threadhideunhide")){
                    $result = mysqli_query($conn, "SELECT * FROM forums WHERE type='thread' && parent='$parent' ORDER BY lastactive DESC");
                //}else{
                //    $result = mysqli_query($conn, "SELECT * FROM forums WHERE type='thread' && hidden='0' && parent='$parent' ORDER BY lastactive DESC");
                //}
            }
    		mysqli_close($conn);

            return mysqli_fetch_object($result);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM forums WHERE id='$id'");
    		mysqli_close($conn);

            return mysqli_fetch_object($result);
        }

        public function get_all_posts_by_poster($poster){
            $conn = get_mysql_conn();
            $poster = mysqli_real_escape_string($conn, $poster);
    		$result = mysqli_query($conn, "SELECT * FROM forums WHERE poster='$poster' && type='thread' || poster='$poster' && type='reply' ORDER BY firstposted DESC");
    		mysqli_close($conn);

            while($array[] = mysqli_fetch_object($result));

    		return $array;
        }

        public function create_forum($title, $text, $listorder){
            $conn = get_mysql_conn();
    		$title = mysqli_real_escape_string($conn, $title);
    		$text = mysqli_real_escape_string($conn, $text);
    		$listorder = mysqli_real_escape_string($conn, $listorder);
            $poster = accounts::get_current_account()->id;
    		$time = time();
    		mysqli_query($conn, "INSERT INTO forums(firstposted, lastactive, type, title, text, listorder, poster) VALUES ('$time', '$time', 'forum', '$title', '$text', '$listorder', '$poster')");
    		mysqli_close($conn);
        }

        public function create_subforum($parent, $title, $text, $listorder){
            $conn = get_mysql_conn();
    		$parent = mysqli_real_escape_string($conn, $parent);
    		$title = mysqli_real_escape_string($conn, $title);
    		$text = mysqli_real_escape_string($conn, $text);
    		$listorder = mysqli_real_escape_string($conn, $listorder);
            $poster = accounts::get_current_account()->id;
    		$time = time();
    		mysqli_query($conn, "INSERT INTO forums(parent, firstposted, lastactive, type, title, text, listorder, poster) VALUES ('$parent', '$time', '$time', 'subforum', '$title', '$text', '$listorder', '$poster')");
    		mysqli_close($conn);
        }

        public function update_lastactive($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $time = time();
    		$result = mysqli_query($conn, "UPDATE forums SET lastactive='$time' WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function create_thread($subforum, $title, $text){
            $conn = get_mysql_conn();
            $subforum = mysqli_real_escape_string($conn, $subforum);
            $title = mysqli_real_escape_string($conn, $title);
            $text = mysqli_real_escape_string($conn, $text);
            $poster = accounts::get_current_account()->id;;
            $time = time();
    		$result = mysqli_query($conn, "INSERT INTO forums(firstposted, lastactive, type, title, text, poster, parent) VALUES ('$time', '$time', 'thread', '$title', '$text', '$poster', '$subforum')");
            $lastcreatedid = mysqli_insert_id($conn);
    		mysqli_close($conn);
            return $lastcreatedid;
        }

        public function create_reply($thread, $text){
            $conn = get_mysql_conn();
            $thread = mysqli_real_escape_string($conn, $thread);
            $text = mysqli_real_escape_string($conn, $text);
            $poster = accounts::get_current_account()->id;
            $time = time();
    		$result = mysqli_query($conn, "INSERT INTO forums(firstposted, lastactive, type, text, poster, parent) VALUES ('$time' ,'$time' , 'reply', '$text', '$poster', '$thread')");
            mysqli_query($conn, "UPDATE forums SET lastactive='$time', replies = replies + 1 WHERE id='$thread'");
    		mysqli_close($conn);
        }

        public function toggle_thread_lock($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $result = mysqli_query($conn, "UPDATE forums SET locked = NOT locked WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function toggle_thread_pin($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $result = mysqli_query($conn, "UPDATE forums SET pinned = NOT pinned WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function toggle_thread_hidden($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $result = mysqli_query($conn, "UPDATE forums SET hidden = NOT hidden WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function edit($id, $title, $text){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $title = mysqli_real_escape_string($conn, $title);
            $text = mysqli_real_escape_string($conn, $text);
            $currAccount = accounts::get_current_account()->id;
    		$result = mysqli_query($conn, "UPDATE forums SET title='$title',text='$text',lastedited='".time()."',lastediteduser='".$currAccount."' WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function delete($id, $status){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $status = mysqli_real_escape_string($conn, $status);
            $currAccount = accounts::get_current_account()->id;
            $result = mysqli_query($conn, "UPDATE forums SET deletestatus='$status',deletedby='$currAccount' WHERE id='$id'");
            mysqli_close($conn);
        }
    }
?>
