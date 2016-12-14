<?php
    include("../fillin/scripts.php");
    start_session();

    unset($_SESSION["sessionid"]);

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
