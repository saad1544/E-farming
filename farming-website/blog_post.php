<?php 
$page_title = "Blog Post";
include('includes/header.php'); 

if(isset($_GET['id'])) {
    include('config/db.php');
    $id = $_GET['id'];
    $query = "SELECT blog.*, users.name as author_name FROM blog JOIN users ON blog.author_id = users.id WHERE blog.id = $id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        ?>
        <article class="blog-post">
            <h1><?php echo $post['title']; ?></h1>
            <p class="meta">Posted by <?php echo $post['author_name']; ?> on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></p>
            <div class="content"><?php echo nl2br($post['content']); ?></div>
        </article>
        <?php
    } else {
        echo '<p>Post not found</p>';
    }
    $conn->close();
} else {
    echo '<p>No post selected</p>';
}
?>

<?php include('includes/footer.php'); ?>