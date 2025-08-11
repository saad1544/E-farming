<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="events.php">Events</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li class="cart-link">
                        <a href="cart.php">
                            <span class="cart-icon">🛒</span>             
                            <?php
                            // Show cart item count
                            include('config/db.php');
                            $user_id = $_SESSION['user_id'];
                            $count_query = "SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id";
                            $count_result = $conn->query($count_query);
                            $count = $count_result->fetch_assoc()['total'] ?? 0;
                            $conn->close();
                            if($count > 0) {
                                echo '<span class="cart-count">' . $count . '</span>';
                            }
                            ?>
                        </a>
                    </li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>