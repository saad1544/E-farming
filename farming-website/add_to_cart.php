<?php
session_start();
include('includes/auth_check.php');
include('config/db.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = $conn->real_escape_string($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Check if product already in cart
    $check_query = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = $conn->query($check_query);
    
    if($check_result->num_rows > 0) {
        // Update quantity
        $update_query = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = $user_id AND product_id = $product_id";
        $conn->query($update_query);
    } else {
        // Add new item
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
        $conn->query($insert_query);
    }
    
    $conn->close();
    header("Location: cart.php");
    exit();
} else {
    header("Location: products.php");
    exit();
}
?>