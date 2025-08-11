<?php
session_start();
include('includes/auth_check.php');
include('config/db.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = $conn->real_escape_string($_POST['cart_id']);
    $quantity = (int)$_POST['quantity'];
    
    if($quantity > 0) {
        $update_query = "UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = {$_SESSION['user_id']}";
        $conn->query($update_query);
    }
    
    $conn->close();
}

header("Location: cart.php");
exit();
?>