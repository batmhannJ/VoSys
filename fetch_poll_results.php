<?php
include 'includes/session.php';
include 'includes/conn.php';

// Fetch live poll results
$sql_results = "SELECT 
                    categories.name AS position_name, 
                    COUNT(votes_csc.id) AS total_votes
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
$position_votes = []; // Track votes per position
while ($row = $result->fetch_assoc()) {
    $total_votes += $row['total_votes'];
    $position_votes[$row['position_name']] = ($position_votes[$row['position_name']] ?? 0) + $row['total_votes'];
}
$result->data_seek(0); // Reset result pointer

// Generate output
echo '<h3>Live Poll Results</h3>';
echo '<div class="poll-results">';
$prev_position = '';
$color_index = 0;
$colors = ['#333333', '#4A4A4A', '#666666', '#808080']; // Dark gray shades

while ($row = $result->fetch_assoc()) {
    // Display position name only once
    if ($row['position_name'] != $prev_position) {
        if ($prev_position != '') {
            echo '</div>'; // Close previous position div
        }
        echo "<div class='position-group'>";
        echo "<h4 class='position-title'>{$row['position_name']}</h4>";
        $prev_position = $row['position_name'];
        $color_index = 0; // Reset color index for new position
    }

    // Calculate percentage based on total votes for the position
    $position_total = $position_votes[$row['position_name']] ?? 1; // Avoid division by zero
    $vote_percentage = number_format(($row['total_votes'] / $position_total) * 100, 2);
    
    // Select color
    $color = $colors[$color_index % count($colors)];
    $color_index++;

    // Display anonymous result with avatar and percentage only
    echo "<div class='poll-item'>";
    echo "<img class='avatar' src='images/default_avatar.png' alt='Avatar'>";
    echo "<div class='poll-bar-container'>";
    echo "<div class='poll-bar' style='width: {$vote_percentage}%; background-color: {$color};'>";
    echo "<span class='poll-percentage'>{$vote_percentage}%</span>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

if ($prev_position != '') {
    echo '</div>'; // Close last position div
}
echo '</div>';

if ($total_votes == 0) {
    echo '<p class="poll-empty">No votes recorded yet.</p>';
}
?>

<style>
.poll-results {
    font-family: Arial, sans-serif;
}

.position-group {
    margin-bottom: 20px;
}

.position-title {
    font-size: 1.2rem;
    color: #333333;
    margin: 10px 0;
    font-weight: bold;
}

.poll-item {
    margin: 10px 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.poll-bar-container {
    flex: 2;
    background-color: #d3d3d3;
    border-radius: 5px;
    height: 30px;
    overflow: hidden;
}

.poll-bar {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    border-radius: 5px;
    transition: width 0.5s ease;
}

.poll-percentage {
    color: white;
    font-size: 0.9rem;
    padding-right: 5px;
    font-weight: bold;
}

.poll-empty {
    color: #666;
    text-align: center;
    font-style: italic;
    margin-top: 20px;
}

/* Responsive design */
@media (max-width: 768px) {
    .poll-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .poll-bar-container {
        width: 100%;
    }

    .avatar {
        margin-bottom: 10px;
    }
}
</style>