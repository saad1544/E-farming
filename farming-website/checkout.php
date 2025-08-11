<?php 
//session_start();
include('includes/auth_check.php');
$page_title = "Checkout";
include('includes/header.php'); 

include('config/db.php');
$user_id = $_SESSION['user_id'];
$cart_query = "SELECT cart.*, products.price 
               FROM cart JOIN products ON cart.product_id = products.id 
               WHERE cart.user_id = $user_id";
$cart_result = $conn->query($cart_query);

if($cart_result->num_rows == 0) {
    header("Location: cart.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process checkout
    $total = 0;
    $cart_result->data_seek(0); // Reset pointer
    
    while($row = $cart_result->fetch_assoc()) {
        $total += $row['price'] * $row['quantity'];
    }
    
    $order_query = "INSERT INTO orders (user_id, total_price, status) VALUES ($user_id, $total, 'pending')";
    
    if($conn->query($order_query)) {
        $order_id = $conn->insert_id;
        $cart_result->data_seek(0); // Reset pointer again
        
        while($row = $cart_result->fetch_assoc()) {
            $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                           VALUES ($order_id, {$row['product_id']}, {$row['quantity']}, {$row['price']})";
            $conn->query($item_query);
            
            // Update product quantity
            $update_query = "UPDATE products SET quantity = quantity - {$row['quantity']} WHERE id = {$row['product_id']}";
            $conn->query($update_query);
        }
        
        // Clear cart
        $clear_query = "DELETE FROM cart WHERE user_id = $user_id";
        $conn->query($clear_query);
        
        $success = "Order placed successfully! Your order ID is #$order_id";
    } else {
        $error = "Checkout failed: " . $conn->error;
    }
}
?>

<h1>Checkout</h1>

<?php 
if(isset($success)) {
    echo '<div class="alert alert-success">' . $success . '</div>';
    include('includes/footer.php');
    exit();
}

if(isset($error)) {
    echo '<div class="alert alert-error">' . $error . '</div>';
}
?>

<div class="checkout-grid">
    <div class="order-summary">
        <h2>Order Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grand_total = 0;
                $cart_result->data_seek(0); // Reset pointer
                while($row = $cart_result->fetch_assoc()): 
                    $total = $row['price'] * $row['quantity'];
                    $grand_total += $total;
                ?>
                    <tr>
                        <td><?php 
                            $product_query = "SELECT name FROM products WHERE id = {$row['product_id']}";
                            $product_result = $conn->query($product_query);
                            echo $product_result->fetch_assoc()['name'];
                        ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>$<?php echo number_format($total, 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Grand Total:</td>
                    <td>$<?php echo number_format($grand_total, 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="payment-form">
        <h2>Payment Information</h2>
        <form action="checkout.php" method="post">
            <div class="form-group">
                <label for="card_name">Name on Card:</label>
                <input type="text" id="card_name" name="card_name" required>
            </div>
            <div class="form-group">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="expiry">Expiry Date:</label>
                    <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    </div>
</div>

<?php 
$conn->close();
include('includes/footer.php'); 
?>