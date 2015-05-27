<?php
    session_start();
    //Destroy All Sessions
    session_destroy();
    header('Location: index.php');
?>
