<?php 
//session_start();
include('includes/auth_check.php');
$page_title = "Dashboard";
include('includes/header.php'); 
?>

<h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>

<div class="dashboard-grid">
    <div class="dashboard-card">
        <h2>Your Profile</h2>
        <p>Email: <?php 
            include('config/db.php');
            $id = $_SESSION['user_id'];
            $query = "SELECT email FROM users WHERE id = $id";
            $result = $conn->query($query);
            if($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                echo $user['email'];
            }
            $conn->close();
        ?></p>
        <p>Role: <?php echo $_SESSION['user_role']; ?></p>
        <a href="logout.php" class="btn">Logout</a>
    </div>
    
    <?php if($_SESSION['user_role'] == 'farmer'): ?>
    <div class="dashboard-card">
        <h2>Your Products</h2>
        <?php
        include('config/db.php');
        $query = "SELECT * FROM products WHERE farmer_id = $id";
        $result = $conn->query($query);
        
        if($result->num_rows > 0) {
            echo '<ul>';
            while($row = $result->fetch_assoc()) {
                echo '<li>' . $row['name'] . ' - $' . $row['price'] . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No products found</p>';
        }
        $conn->close();
        ?>
        <a href="add_product.php" class="btn">Add Product</a>
    </div>
    <?php endif; ?>
    
    <div class="dashboard-card">
        <h2>Your Orders</h2>
        <?php
        include('config/db.php');
        $query = "SELECT * FROM orders WHERE user_id = $id ORDER BY created_at DESC LIMIT 3";
        $result = $conn->query($query);
        
        if($result->num_rows > 0) {
            echo '<ul>';
            while($row = $result->fetch_assoc()) {
                echo '<li>Order #' . $row['id'] . ' - $' . $row['total_price'] . ' - ' . ucfirst($row['status']) . '</li>';
            }
            echo '</ul>';
            echo '<a href="orders.php" class="btn">View All Orders</a>';
        } else {
            echo '<p>No orders found</p>';
        }
        $conn->close();
        ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>