<?php 
$page_title = "Products";
include('includes/header.php'); 
?>

<h1>Our Products</h1>

<div class="products-grid">
    <?php
    include('config/db.php');
    $query = "SELECT * FROM products";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="product-card">';
           $image_path = str_replace('.jpg', '.jpeg', $row['image']);
                echo '<img src="images/' . $image_path . '" alt="' . $row['name'] . '">';
            echo '<h3>' . $row['name'] . '</h3>';
            echo '<p class="price">$' . number_format($row['price'], 2) . '</p>';
            echo '<p class="description">' . substr($row['description'], 0, 100) . '...</p>';
            echo '<div class="product-actions">';
            echo '<a href="product_detail.php?id=' . $row['id'] . '" class="btn">View Details</a>';
            if(isset($_SESSION['user_id'])) {
                echo '<form action="add_to_cart.php" method="post" class="add-to-cart-form">';
                echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                echo '<input type="number" name="quantity" value="1" min="1" max="' . $row['quantity'] . '" class="quantity-input">';
                echo '<button type="submit" class="btn btn-cart">Add to Cart</button>';
                echo '</form>';
            } else {
                echo '<p class="login-prompt"><a href="login.php">Login</a> to purchase</p>';
            }
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No products found</p>';
    }
    $conn->close();
    ?>
</div>

<?php include('includes/footer.php'); ?>