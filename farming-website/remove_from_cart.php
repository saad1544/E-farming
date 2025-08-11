<?php
session_start();
include('includes/auth_check.php');
include('config/db.php');

if(isset($_GET['id'])) {
    $cart_id = $conn->real_escape_string($_GET['id']);
    $delete_query = "DELETE FROM cart WHERE id = $cart_id AND user_id = {$_SESSION['user_id']}";
    $conn->query($delete_query);
    $conn->close();
}

header("Location: cart.php");
exit();
?>