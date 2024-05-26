<?php
include 'includes/session.php';
include 'includes/conn.php';
include 'includes/header_code.php';
?>

<style>
    /* Style for position name */
    .position-name {
        margin-top: 20px;
        font-size: 20px;
        font-weight: bold;
    }

    /* Style for the bar container */
    .bar-container {
        margin: 10px 0;
        background-color: lightgrey;
        width: 100%;
        height: 30px;
    }

    /* Style for the bar */
    .bar {
        height: 100%;
        text-align: center;
        line-height: 30px;
        color: white;
        font-weight: bold;
    }

    /* Blue bar */
    .bar.blue {
        background-color: blue;
    }

    /* Red bar */
    .bar.red {
        background-color: red;
    }
</style>

<!-- Display the live poll results -->
<h2 class="text-center">Live Poll Results</h2>
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
    
    // Generate bar graph
    $is_blue = true; // Initialize color
    while($row = $result->fetch_assoc()) {
        // Display position name only once
        static $prev_position = '';
        if ($row['position_name'] != $prev_position) {
            echo "<div class='position-name'>{$row['position_name']}</div>";
            $prev_position = $row['position_name'];
        }

        // Calculate percentage based on total votes for the position
        $vote_percentage = number_format(($row['total_votes'] / $total_votes) * 100, 2);
        
        // Determine bar color
        $color_class = $is_blue ? 'blue' : 'red';

        // Display candidate result with percentage rounded to 2 decimal places
        echo "<div class='bar-container'>
                <div class='bar $color_class' style='width: {$vote_percentage}%;'>
                    {$vote_percentage}%
                </div>
              </div>";
        $is_blue = !$is_blue; // Toggle color
    }
    ?>
</div>
