<?php 
//session_start();
include('includes/auth_check.php');
$page_title = "Shopping Cart";
include('includes/header.php'); 

include('config/db.php');
$user_id = $_SESSION['user_id'];
$query = "SELECT cart.*, products.name, products.price, products.image 
          FROM cart JOIN products ON cart.product_id = products.id 
          WHERE cart.user_id = $user_id";
$result = $conn->query($query);
?>

<h1>Your Shopping Cart</h1>

<?php if($result->num_rows > 0): ?>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $grand_total = 0;
            while($row = $result->fetch_assoc()): 
                $total = $row['price'] * $row['quantity'];
                $grand_total += $total;
            ?>
                <tr>
                    <td>
                        <?php 
$image_path = str_replace('.jpg', '.jpeg', $row['image']);
?>
<img src="images/<?php echo $image_path; ?>" alt="<?php echo $row['name']; ?>" width="50">
                        <?php echo $row['name']; ?>
                    </td>
                    <td>$<?php echo $row['price']; ?></td>
                    <td>
                        <form action="update_cart.php" method="post" class="quantity-form">
                            <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
                            <button type="submit" class="btn-small">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($total, 2); ?></td>
                    <td>
                        <a href="remove_from_cart.php?id=<?php echo $row['id']; ?>" class="btn-small btn-danger">Remove</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right">Grand Total:</td>
                <td>$<?php echo number_format($grand_total, 2); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="cart-actions">
        <a href="products.php" class="btn">Continue Shopping</a>
        <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    </div>
<?php else: ?>
    <p>Your cart is empty. <a href="products.php">Start shopping</a></p>
<?php endif; ?>

<?php 
$conn->close();
include('includes/footer.php'); 
?>