<?php 
$page_title = "Blog";
include('includes/header.php'); 
?>

<h1>Our Blog</h1>

<div class="blog-posts">
    <?php
    include('config/db.php');
    $query = "SELECT blog.*, users.name as author_name FROM blog JOIN users ON blog.author_id = users.id ORDER BY created_at DESC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="blog-card">';
            echo '<h2>' . $row['title'] . '</h2>';
            echo '<p class="meta">Posted by ' . $row['author_name'] . ' on ' . date('F j, Y', strtotime($row['created_at'])) . '</p>';
            echo '<p>' . substr($row['content'], 0, 200) . '...</p>';
            echo '<a href="blog_post.php?id=' . $row['id'] . '" class="btn">Read More</a>';
            echo '</div>';
        }
    } else {
        echo '<p>No blog posts found</p>';
    }
    $conn->close();
    ?>
</div>

<?php include('includes/footer.php'); ?>