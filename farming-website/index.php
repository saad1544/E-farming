<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Farming</title>
</head>
<body>
    
<?php 
session_start();
$page_title = "Home";
include('includes/header.php'); 

?>

<section class="hero">
    <h2>Welcome to Organic Farming</h2>
    <p>Fresh, organic produce straight from our farm to your table</p>
    <a href="products.php" class="btn">Shop Now</a>
</section>

<section class="featured-products">
    <h2>Featured Products</h2>
    <div class="products-grid">
        <?php
        include('config/db.php');
        $query = "SELECT * FROM products LIMIT 3";
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
    <div class="view-all">
        <a href="products.php" class="btn">View All Products</a>
    </div>
</section>

<?php if(isset($_SESSION['user_id'])): ?>
<section class="cart-preview">
    <h2>Your Cart</h2>
    <?php
    include('config/db.php');
    $user_id = $_SESSION['user_id'];
    $query = "SELECT cart.*, products.name, products.price 
              FROM cart JOIN products ON cart.product_id = products.id 
              WHERE cart.user_id = $user_id 
              LIMIT 3";
    $result = $conn->query($query);
    
    if($result->num_rows > 0): ?>
        <div class="cart-items">
            <?php 
            $total = 0;
            while($row = $result->fetch_assoc()): 
                $item_total = $row['price'] * $row['quantity'];
                $total += $item_total;
            ?>
                <div class="cart-item">
                    <span class="item-name"><?php echo $row['name']; ?></span>
                    <span class="item-quantity"><?php echo $row['quantity']; ?> × $<?php echo number_format($row['price'], 2); ?></span>
                    <span class="item-total">$<?php echo number_format($item_total, 2); ?></span>
                </div>
            <?php endwhile; ?>
            <div class="cart-total">
                <span>Subtotal:</span>
                <span>$<?php echo number_format($total, 2); ?></span>
            </div>
        </div>
        <div class="cart-preview-actions">
            <a href="cart.php" class="btn">View Full Cart</a>
            <a href="checkout.php" class="btn btn-primary">Checkout Now</a>
        </div>
    <?php else: ?>
        <p>Your cart is empty. <a href="products.php" class="btn">Start shopping</a></p>
    <?php endif; 
    $conn->close();
    ?>
</section>
<?php endif; ?>

<section class="latest-blog">
    <h2>Latest Blog Posts</h2>
    <div class="blog-posts">
        <?php
        include('config/db.php');
        $query = "SELECT blog.*, users.name as author_name FROM blog JOIN users ON blog.author_id = users.id ORDER BY created_at DESC LIMIT 2";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="blog-card">';
                echo '<h3>' . $row['title'] . '</h3>';
                echo '<p class="meta">By ' . $row['author_name'] . ' | ' . date('F j, Y', strtotime($row['created_at'])) . '</p>';
                echo '<p>' . substr(strip_tags($row['content']), 0, 100) . '...</p>';
                echo '<a href="blog_post.php?id=' . $row['id'] . '" class="btn">Read More</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No blog posts found</p>';
        }
        $conn->close();
        ?>
    </div>
    <div class="view-all">
        <a href="blog.php" class="btn">View All Posts</a>
    </div>
</section>

<section class="upcoming-events">
    <h2>Upcoming Events</h2>
    <div class="events-list">
        <?php
        include('config/db.php');
        $query = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 2";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="event-card">';
                echo '<h3>' . $row['title'] . '</h3>';
                echo '<p class="date">' . date('F j, Y', strtotime($row['event_date'])) . '</p>';
                echo '<p>' . substr($row['description'], 0, 150) . '...</p>';
                echo '<a href="events.php" class="btn">Learn More</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No upcoming events found</p>';
        }
        $conn->close();
        ?>
    </div>
    <div class="view-all">
        <a href="events.php" class="btn">View All Events</a>
    </div>
</section>

<?php include('includes/footer.php'); ?>
</body>
</html>