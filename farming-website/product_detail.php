<?php 
$page_title = "Product Details";
include('includes/header.php'); 

if(isset($_GET['id'])) {
    include('config/db.php');
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        ?>
        <div class="product-detail">
    <div class="product-image">
        <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
    </div>
    <div class="product-info">
        <h1><?php echo $product['name']; ?></h1>
        <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
        <p class="description"><?php echo $product['description']; ?></p>
        <p class="stock">Available: <?php echo $product['quantity']; ?> in stock</p>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <form action="add_to_cart.php" method="post" class="cart-form">
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>">
                </div>
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-primary btn-large">Add to Cart</button>
                <a href="cart.php" class="btn btn-secondary">View Cart</a>
            </form>
        <?php else: ?>
            <div class="login-prompt">
                <p>You need to <a href="login.php">login</a> to add items to your cart.</p>
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>
        <?php
    } else {
        echo '<p>Product not found</p>';
    }
    $conn->close();
} else {
    echo '<p>No product selected</p>';
}
?>

<?php include('includes/footer.php'); ?>