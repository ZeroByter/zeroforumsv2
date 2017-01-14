<?php
    include("../fillin/scripts.php");
    start_session();

    logs::add_log("logout", "$1 logged out");

    sessions::delete_session($_SESSION["sessionid"]);

    unset($_SESSION["sessionid"]);
    session_destroy();

    if(isset($_GET["next"])){
?>
<script>
    window.location = "<?php echo $_GET["next"] ?>"
</script>
<?php }else{ ?>
    <script>
        window.location = "/"
    </script>
<?php } ?>
