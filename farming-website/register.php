<?php 
$page_title = "Register";
include('includes/header.php'); 

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('config/db.php');
    
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'customer'; // Default role
    
    // Check if email already exists
    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $check_result = $conn->query($check_query);
    
    if($check_result->num_rows > 0) {
        $error = "Email already registered";
    } else {
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        
        if($conn->query($query)) {
            $success = "Registration successful! Please login.";
        } else {
            $error = "Registration failed: " . $conn->error;
        }
    }
    
    $conn->close();
}
?>

<div class="register-form">
    <h2>Register</h2>
    <?php 
    if(isset($error)) echo '<p class="error">' . $error . '</p>';
    if(isset($success)) echo '<p class="success">' . $success . '</p>';
    ?>
    <form action="register.php" method="post">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<?php include('includes/footer.php'); ?>