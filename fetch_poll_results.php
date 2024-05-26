<?php
include 'includes/session.php';
include 'includes/conn.php';
include 'includes/header_code.php';
?>

<style>
    /* Container for the live poll results */
    #live-poll-results {
        margin-top: 30px;
    }

    /* Position name */
    .position-name {
        margin-bottom: 10px;
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }

    /* Bar container */
    .bar-container {
        margin-bottom: 10px;
        background-color: #f0f0f0;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Bar */
    .bar {
        height: 30px;
        line-height: 30px;
        color: white;
        font-weight: bold;
        text-align: center;
        background-color: #3498db;
    }

    /* Alternate bar color */
    .bar.alt {
        background-color: #e74c3c;
    }
</style>

<!-- Display the live poll results -->
<div id="live-poll-results">
    <?php
    // Initialize last position name variable
    $last_position_name = '';

    // Loop through poll results
    while($row = $result->fetch_assoc()) {
        // Display position name if it's different from the last one
        if ($last_position_name != $row['position_name']) {
            echo "<div class='position-name'>{$row['position_name']}</div>";
            $last_position_name = $row['position_name'];
        }

        // Calculate percentage based on total votes for the position
        $vote_percentage = number_format(($row['total_votes'] / $total_votes) * 100, 2);
        
        // Determine bar color class
        $color_class = $is_blue ? 'bar' : 'bar alt';

        // Display bar with percentage
        echo "<div class='bar-container'>
                <div class='$color_class' style='width: {$vote_percentage}%;'>
                    {$vote_percentage}%
                </div>
              </div>";

        // Toggle color flag
        $is_blue = !$is_blue;
    }
    ?>
</div>

