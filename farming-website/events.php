<?php 
$page_title = "Events";
include('includes/header.php'); 
?>

<h1>Upcoming Events</h1>

<div class="events-list">
    <?php
    include('config/db.php');
    $query = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="event-card">';
            echo '<h2>' . $row['title'] . '</h2>';
            echo '<p class="date">' . date('F j, Y', strtotime($row['event_date'])) . '</p>';
            echo '<p>' . $row['description'] . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No upcoming events found</p>';
    }
    $conn->close();
    ?>
</div>

<?php include('includes/footer.php'); ?>