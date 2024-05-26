<?php

include 'includes/session.php';

include 'includes/conn.php';

include 'includes/header_code.php';
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
                    echo "<div style='margin-top: 20px; font-size: 20px;'><strong>{$row['position_name']}</strong></div>";
                    $prev_position = $row['position_name'];
                }

                // Calculate percentage based on total votes for the position
                $vote_percentage = number_format(($row['total_votes'] / $total_votes) * 100, 2);
                
                // Alternate color between blue and red
                $color = $is_blue ? 'blue' : 'red';

                // Display candidate result without names and with percentage rounded to 2 decimal places
                echo "<div style='margin: 10px 0;'>
                        <div style='background-color: lightgrey; width: 100%; height: 30px; border: 1px solid #ccc; border-color: #f0f0f0; border-radius: 5px;'>
                            <div style='width: {$vote_percentage}%; background-color: $color; color: white; height: 100%; text-align: center; line-height: 30px; border-radius: 5px;'>
                                {$vote_percentage}%
                            </div>
                        </div>
                      </div>";
                $is_blue = !$is_blue; // Toggle color
            }
            ?>
