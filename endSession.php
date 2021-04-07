<?php

session_start();

if(isset($_SESSION['userID'])) {
    session_unset();
    header('location: index.php');
}

?>