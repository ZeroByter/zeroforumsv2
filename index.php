<?php
	include("phpscripts/fillin/scripts.php");

	$conn = get_mysql_conn();

	if(gettype($conn) !== "object"){
		echo "<b><br>A MySQL connection error occured!<br><br>If you a guest of the server. Report the above error message to the server owner and wait for it to be fixed, there isn't much you can do.<br>If you are the server owner, clean any and all tables from your MySQL database, then delete the '/config.php' file in the main index folder of your server and refresh the page to able to setup the MySQL connection.</b>";
		return;
	}

	//Main performance cause when loading pages is due to lots of user_has_permission functions. Adding onto the slower times is because user_has_permission checks if users are banned (which requires a MySQL query which takes EVEN longer).
	//A solution must be found at once, but for now it is sort of tolerable. Just make sure ALL requests check if user has permission to do the requested action first.
	// ### SECURITY COMES FIRST ###

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
	$adminNavbar = false; //Never use this unless were serving the admin panel
	$showNavbar = true;
	$includeHead = true;
	$informBans = true;
	if(file_exists("config.php")){
		$settings = include("config.php");
		start_session();
		
		$currUsertags = [];
		if(accounts::is_logged_in()){
			$currAccount = accounts::get_current_account();
			if(!isset($_SESSION["usertags"])){
				$_SESSION["usertags"] = [];
			}
			if(!isset($_SESSION["permissions"])){
				$_SESSION["permissions"] = [];
			}
			if(isset($_SESSION["lastUsertagsUpdate"])){
				if(time() - $_SESSION["lastUsertagsUpdate"] > $settings || $_SESSION["usertags"] == []){ //update session usertags and permissions every xx seconds
					$_SESSION["lastUsertagsUpdate"] = time();
					$_SESSION["usertags"] = accounts::get_current_usertags();
					$_SESSION["permissions"] = accounts::get_all_permissions();
					//echo "updated usertags and permissions!";
				}
			}
			$currUsertags = $_SESSION["usertags"];
		}
		
		$page = "/pages/errors/notfound.php"; //if we didn't assign a page dir by the end of the code, display 404
		if($url["path"][0] == "" || $url["path"][0] == "index"){ //display main index page
			//$page = "/pages/index.php";
			$page = "/pages/forums/forums.php"; //really we should be sending user to index.php, but since were still starting, straight to forums!
		}
		if($url["path"][0] == "index"){ //display index page
			$page = "/pages/index.php";
		}
		if($url["path"][0] == "forums"){ //display forums page
			$page = "/pages/forums/forums.php";
		}
		if($url["path"][0] == "subforum"){ //display subforums page
			if(isset($url["path"][1]) && $url["path"][1] != ""){
				$subforum = forums::get_by_id($url["path"][1]);
				if(usertags::can_tag_do($currUsertags, $subforum->canview) && $subforum->type == "subforum"){
					$page = "/pages/forums/subforum.php";
					if(isset($url["path"][2]) && $url["path"][2] == "postthread"){
						$page = "/pages/forums/postthread.php";
					}
				}
			}else{
				header("Location:/");
			}
		}
		if($url["path"][0] == "thread"){ //start thread section
			if(isset($url["path"][1]) && $url["path"][1] != ""){ //main thread
				$thread = forums::get_by_id($url["path"][1]);
				$parent = forums::get_by_id($thread->parent);
				if(isset($thread->id) && $thread->type == "thread" && usertags::can_tag_do($currUsertags, $parent->canview)){
					$page = "/pages/forums/thread.php";
				}
			}else{
				header("Location:/");
			}
			if(isset($url["path"][2]) && $url["path"][2] == "postreply"){ //post thread reply
				if(isset($currAccount)){
					$page = "/pages/forums/postreply.php";
				}else{
					$page = "/pages/errors/nopermission.php";
				}
			}
			if(isset($url["path"][2]) && $url["path"][2] == "editthread"){ //edit thread
				if(isset($currAccount)){
					$thread = forums::get_by_id($url["path"][1]);
					$page = "/pages/forums/editthread.php";
				}else{
					$page = "/pages/errors/nopermission.php";
				}
			}
			if(isset($url["path"][2]) && $url["path"][2] == "editreply"){ //edit reply
				if(isset($url["path"][3]) && $url["path"][3] != ""){
					if(isset($currAccount)){
						$replyID = $url["path"][3];
						$page = "/pages/forums/editreply.php";
					}else{
						$page = "/pages/errors/nopermission.php";
					}
				}
			}
		}
		if($url["path"][0] == "admin"){ //start admin panel
			if(isset($currAccount) && accounts::is_staff($currAccount->id)){
				$informBans = false;
				$showNavbar = false;
				$adminNavbar = true;
				if($url["path"][1] == ""){ //dashboard
					$page = "/admin/pages/dashboard.php";
				}
				if($url["path"][1] == "settings"){ //website settings
					$page = "/admin/pages/settings.php";
				}
				if($url["path"][1] == "forums"){ //forums
					$page = "/admin/pages/forums.php";
					if(isset($url["path"][2]) && $url["path"][2] != ""){
						$forum = forums::get_by_id($url["path"][2]);
						if(isset($forum->id)){
							if($forum->type == "subforum" || $forum->type == "forum"){
								$page = "/admin/pages/updateforum.php";
							}
						}
						if($url["path"][2] == "new"){
							$page = "/admin/pages/createforum.php";
						}
						if(isset($url["path"][3]) && $url["path"][3] != "" && $forum->type == "forum"){
							$page = "/admin/pages/createsubforum.php";
						}
					}
				}
				if($url["path"][1] == "users"){ //users
					$page = "/admin/pages/users.php";
				}
				if($url["path"][1] == "usertags"){ //usertags
					$page = "/admin/pages/usertags.php";
				}
				if($url["path"][1] == "permissions"){ //permissions
					$page = "/admin/pages/permissions.php";
				}
				if($url["path"][1] == "sessions"){ //sessions
					$page = "/admin/pages/sessions.php";
				}
				if($url["path"][1] == "logs"){ //logs
					$page = "/admin/pages/logs.php";
					if(count($url["path"]) > 2){
						$logDate = $url["path"][2]."/".$url["path"][3]."/".$url["path"][4];
						$page = "/admin/pages/viewLog.php";
					}
				}
			}else{
				logs::add_log("admin access", "$1 tried to enter the admin panel, but he is not staff");
				$page = "/pages/errors/nopermission.php";
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
			if($url["path"][1] == "profile"){
				if(isset($url["path"][2]) && $url["path"][2] != ""){
					$profile = accounts::get_by_id($url["path"][2]);
					$page = "/pages/profile.php";
				}
			}
			if($url["path"][1] == "settings"){
				if(isset($currAccount)){
					$page = "/pages/settings.php";
				}else{
					$page = "/pages/errors/nopermission.php";
				}
			}
		}

		if($url["path"][0] == "403"){
			$page = "/pages/errors/nopermission.php";
		}
		if($url["path"][0] == "404"){
			$page = "/pages/errors/nopermission.php";
		}
	}else{
		$showNavbar = false;
		$page = "/pages/firstTime.php";
	}
?>

<head>
	<?php if($includeHead){ ?>
		<title>some forums test idk</title>
		<link rel="stylesheet" type="text/css" href="/stylesheets/bootstrap.css"></link>
		<link rel="stylesheet" type="text/css" href="/stylesheets/animate.css"></link>
		<link rel="stylesheet" type="text/css" href="/stylesheets/bootstrap.override.css"></link>
		<link rel="stylesheet" type="text/css" href="/stylesheets/base.css"></link>
		<link rel="stylesheet" type="text/css" href="/stylesheets/font-awesome.css"></link>
		<link rel="stylesheet" type="text/css" href="/stylesheets/zeroeditor.css"></link>
	    <script src="/jsscripts/jquery.js"></script>
	    <script src="/jsscripts/bootstrap.js"></script>
	    <script src="/jsscripts/bootstrap-notify.js"></script>
	    <script expires="0" src="/jsscripts/zeroeditor.js"></script>
	<?php } ?>
</head>
<div id="body_div">
	<?php
		if($adminNavbar){
			include("/admin/fillin/navbar.php");
		}
		if($showNavbar){
			include("/phpscripts/fillin/navbar.php");
		}
		if(file_exists($_SERVER["DOCUMENT_ROOT"] . $page)){
			include($page);
		}else{
			include("/pages/errors/fileerror.php");
		}
	?>
</div>

<?php
	if(session_status() == 2 && isset($currAccount) && $informBans){
		$warnings = accounts::get_warnings($currAccount->id);
		$banned = accounts::confirm_ban($currAccount->id);
		if(count($warnings) > 0 || $banned){
			?>
			<div class="modal fade" id="viewWarnings" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-body">
							<?php
								foreach($warnings as $key=>$value){
									echo "
										<div class='informWarningsDiv'>
											Warning issued on ".timestamp_to_date($value->time, true)." by <b>".accounts::get_display_name($value->warnedby)."</b><br>
											Reason: <i>$value->message</i>
										</div>
									";
									if($key < count($warnings)-1){
										echo "<br>";
									}
								}
								if($banned){
									echo "
										<div class='informBansDiv'>
											Banned by ".accounts::get_display_name($currAccount->bannedby)."<br>
											Banned on the '".timestamp_to_date($currAccount->bannedtime, true)."' for '".$currAccount->bannedmsg."'<br>
											Ban expires on the '".timestamp_to_date($currAccount->unbantime, true)."'
										</div>
									";
								}
							?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal" disabled id="viewWarningsAcceptBtn">Accept (4)</button>
						</div>
					</div>
				</div>
			</div>
			<script>
				$("#viewWarnings").modal("show")
				setTimeout(function(){
					$("#viewWarningsAcceptBtn").html("Accept (3)")
					setTimeout(function(){
						$("#viewWarningsAcceptBtn").html("Accept (2)")
						setTimeout(function(){
							$("#viewWarningsAcceptBtn").html("Accept (1)")
							setTimeout(function(){
								$("#viewWarningsAcceptBtn").html("Accept")
								$("#viewWarningsAcceptBtn").removeAttr("disabled")
							}, 1000)
						}, 1000)
					}, 1000)
				}, 1000)
				$("#viewWarningsAcceptBtn").click(function(){
					$.post("phpscripts/requests/confirmwarnings.php", function(html){
						console.log(html)
					})
				})
			</script>
			<?php
		}
	}
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
	if(isset($_SESSION)){
		unset($_SESSION["pageMessage"]);
		unset($_SESSION["pageMessageType"]);
	}

	mysqli_close($conn);
 ?>
