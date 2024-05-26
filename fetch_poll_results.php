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
    // Fetch live poll results
    $sql_results = "SELECT 
                        categories.name AS position_name, 
                        COUNT(votes_csc.id) AS total_votes,
                        candidates.firstname, 
                        candidates.lastname
                    FROM 
                        votes_csc
                    LEFT JOIN 
                        candidates ON votes_csc.candidate_id = candidates.id
                    LEFT JOIN 
                        categories ON votes_csc.category_id = categories.id
                    WHERE 
                        votes_csc.election_id = 20
                    GROUP BY 
                        categories.name, candidates.id
                    ORDER BY 
                        categories.priority ASC, total_votes DESC";
    $result = $conn->query($sql_results);

    // Get total number of votes for all positions
    $total_votes = 0;
    while($row = $result->fetch_assoc()) {
        $total_votes += $row['total_votes'];
    }
    $result->data_seek(0); // Reset result pointer
    
    // Initialize color flag
    $is_blue = true;

    // Loop through poll results
    while($row = $result->fetch_assoc()) {
        // Display position name
        echo "<div class='position-name'>{$row['position_name']}</div>";

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
