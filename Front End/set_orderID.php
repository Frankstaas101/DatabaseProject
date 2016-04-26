<?php 

include('session.php');
session_start();

$_SESSION['current_Order'] = mysqli_real_escape_string($connection, $_POST['orderID']);
 
 
?>