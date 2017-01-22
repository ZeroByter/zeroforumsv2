<?php
    $versionStatus = compareVersions();
    $currentVersion = getCurrentVersion();
    $githubVersion = getGitHubVersion();
?>

<center>
    <h4>Current version of zeroforumsv2 is: <b><i><?php echo $currentVersion ?></i></b></h4>
    <a href="https://github.com/ZeroByter/zeroforumsv2" target="_blank"><h4>Latest version of zeroforumsv2 is: <b><i><?php echo $githubVersion ?></i></b></h4></a>
    <br><br>
    <?php
        if($versionStatus == 0){
            echo "<h3><font color='red'>Error! We couldn't get the current version!</font></h3>";
        }
        if($versionStatus == 1){
            echo "<h3><font color='#ff4c4c'>Woah, somehow, this version of zeroforumsv2 is <i>newer</i> than the supposedly 'latest' version!</font></h3>";
        }
        if($versionStatus == 2){
            echo "<h3><font color='green'>All good to go! We have the latest version of zeroforumsv2!</font></h3>";
        }
        if($versionStatus == 3){
            echo "<h3><font color='red'>Oh no! We have an outdated version of zeroforumsv2!<br>If you are the server owner consider updating!</font></h3>";
        }
    ?>
</center>
