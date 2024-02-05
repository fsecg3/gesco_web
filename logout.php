<?php
include 'init.php';
$users->saveLogoutSession();
session_start();
session_destroy();
header('Location: login.php');
die();
?>