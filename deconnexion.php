<?php 
    session_start();
    session_unset();
    session_destroy();
    header('location: compte_deja.php');
    exit;
?>








