<?php
	include("phpscripts/fillin/head.php");
	include("phpscripts/fillin/scripts.php");

	start_session();

	//var_dump(usertags::can_tag_do(2, "[2]"));
	//var_dump(usertags::user_has_permission(2, "forums_createpost"));

	//$conn = mysql_get_connect();

	$pageBase = 0;
	$url = (parse_url($_SERVER['REQUEST_URI']));
    $url['path'] = str_replace('.php', '', $url['path']);
    $url['path'] = explode('/', $url['path']);

	$url['path'][$pageBase] = strtolower($url['path'][$pageBase]);
	if(count($url['path']) > $pageBase + 1 && $url['path'][$pageBase + 1] <> ''){
        $url['path'][$pageBase + 1] = str_replace("%20", " ", $url['path'][$pageBase + 1]);
    }
	array_shift($url["path"]);
	$url["path"] = array_map("strtolower", $url["path"]);

	//Begin main page routing
	if(file_exists("config.php")){
		$page = "/pages/errors/notfound.php";
		if($url["path"][0] == "" || $url["path"][0] == "index"){
			//$page = "/pages/index.php";
			$page = "/pages/forums.php"; //really we should be sending user to index.php, but since were still starting, straight to forums!
		}
		if($url["path"][0] == "forums"){
			$page = "/pages/forums.php";
		}
		if($url["path"][0] == "subforum"){
			if(isset($url["path"][1]) && $url["path"][1] != ""){
				$subforumID = $url["path"][1];
				$page = "/pages/subforum.php";
			}else{
				header("Location:/");
			}
		}
		if($url["path"][0] == "thread"){
			if(isset($url["path"][1]) && $url["path"][1] != ""){
				$threadID = $url["path"][1];
				$page = "/pages/thread.php";
			}else{
				header("Location:/");
			}
		}

		if($url["path"][0] == "user"){
			if(accounts::is_logged_in()){
				if($url["path"][1] == "login"){
					$page = "/pages/errors/nopermission.php";
				}
				if($url["path"][1] == "register"){
					$page = "/pages/errors/nopermission.php";
				}
			}else{
				if($url["path"][1] == "login"){
					$page = "/pages/login.php";
				}
				if($url["path"][1] == "register"){
					$page = "/pages/register.php";
				}
			}
			/*if($url["path"][1] == "login"){ //if the second argument is a number, check to see if it is a valid proflile id!
				$page = "/pages/errors/nopermission.php";
			}*/
		}

		/*if($page == "/pages/errors/notfound.php"){
			$result = mysqli_query($conn, "SELECT * FROM links WHERE shortlink='".$url["path"][0]."'");
			if(mysqli_num_rows($result) == 1){
				$link = mysqli_fetch_object($result);
				mysqli_query($conn, "UPDATE links SET clicks = clicks + 1 WHERE id='$link->id'");
				echo "<script>window.location = '$link->longlink'</script>";
			}
		}*/
	}else{
		$page = "/pages/firstTime.php";
	}
?>

<div id="body_div">
	<?php include($page); ?>
</div>

<?php
	if(session_status() == 2 && isset($_SESSION["pageMessage"]) && $_SESSION["pageMessage"] !== ""){
		$pageMessageType = "success";
		if(isset($_SESSION["pageMessageType"])){
			$pageMessageType = $_SESSION["pageMessageType"];
		}
?>
<script>
	$.notify({
		message: "<?php echo $_SESSION["pageMessage"] ?>",
	},{
		type: "<?php echo $pageMessageType ?>",
		z_index: 103001,
		placement: {
			from: "top",
			align: "left"
		},
	})
</script>
<?php }
	unset($_SESSION["pageMessage"]);
	if(isset($_SESSION["pageMessageType"])){
		unset($_SESSION["pageMessageType"]);
	}
 ?>
