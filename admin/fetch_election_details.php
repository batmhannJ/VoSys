<?php
include 'includes/session.php';
include 'includes/slugify.php';

if (isset($_POST['election_id'])) {
    $election_id = $_POST['election_id'];
    
    // Fetch the election title and academic year
    $sql = "SELECT title, academic_yr FROM election WHERE id = '$election_id'";
    $result = $conn->query($sql);
    
    // Check if election exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $election_title = $row['title']; // Store election title
        $academic_yr = $row['academic_yr']; // Store academic year
    } else {
        echo json_encode('Election not found');
        exit;
    }

    // Fetch the total voters for the election (assuming the organization is JPCS)
    $total_voters_sql = "SELECT COUNT(*) AS total_voters FROM voters WHERE archived = FALSE AND organization = 'JPCS'";
    $total_voters_result = $conn->query($total_voters_sql);
    $total_voters_row = $total_voters_result->fetch_assoc();
    $total_voters = $total_voters_row['total_voters'];

    // Fetch the voters who have voted
    $voters_voted_sql = "SELECT COUNT(DISTINCT v.voters_id) AS voters_voted FROM votes vt INNER JOIN voters v ON v.id = vt.voters_id";
    $voters_voted_result = $conn->query($voters_voted_sql);
    $voters_voted_row = $voters_voted_result->fetch_assoc();
    $voters_voted = $voters_voted_row['voters_voted'];

    // Calculate remaining voters
    $remaining_voters = $total_voters - $voters_voted;

    // Fetch categories associated with the election
    $sql = "SELECT * FROM categories WHERE election_id = '$election_id' ORDER BY priority ASC";
    $query = $conn->query($sql);

    $output = '';

    while ($row = $query->fetch_assoc()) {
        $max_vote = $row['max_vote'];  // Fetch max_vote for the category

        $sql = "SELECT * FROM candidates WHERE category_id='" . $row['id'] . "'";
        $cquery = $conn->query($sql);
        
        $candidates = [];
        while ($crow = $cquery->fetch_assoc()) {
            // Count total votes for each candidate
            $votes_sql = "SELECT COUNT(*) AS total_votes FROM votes WHERE candidate_id='" . $crow['id'] . "'";
            $votes_query = $conn->query($votes_sql);
            $votes_row = $votes_query->fetch_assoc();
            $total_votes = $votes_row['total_votes'];

            $image = (!empty($crow['photo'])) ? '../images/' . $crow['photo'] : '../images/profile.jpg';

            // Store candidate data for sorting
            $candidates[] = [
                'id' => $crow['id'],
                'name' => $crow['firstname'] . ' ' . $crow['lastname'],
                'photo' => $image,
                'votes' => $total_votes
            ];
        }

        // Sort candidates by total votes in descending order
        usort($candidates, function ($a, $b) {
            return $b['votes'] - $a['votes'];
        });

        $candidate_list = '';
        $is_winner_marked = 0;  // Track the number of winners marked
        foreach ($candidates as $candidate) {
            // Mark the top `max_vote` candidates as winners
            $winner_label = ($is_winner_marked < $max_vote) ? '<span class="label label-success">Winner</span>' : '';
            if ($is_winner_marked < $max_vote) {
                $is_winner_marked++;  // Increment winner count for each winner marked
            }

            $candidate_list .= '
                <li>
                    <img src="' . $candidate['photo'] . '" height="100px" width="100px" class="clist">
                    <span class="cname clist">' . $candidate['name'] . '</span>
                    <span class="votes clist">Votes: ' . $candidate['votes'] . '</span>
                    ' . $winner_label . '
                </li>
            ';
        }

        $output .= '
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-xs-12">
            <div class="box box-solid" style="border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                <div class="box-header with-border" style="background-color: #f4f4f4; padding: 15px;">
                    <h3 class="box-title" style="font-size: 18px; font-weight: bold; color: #333;">Candidates List</h3>
                </div>
                <div class="box-body" style="padding: 15px; background-color: #fff; max-height: 400px; overflow-y: auto;">
                    <div id="candidate_list">
                        <ul style="list-style-type: none; padding-left: 0; margin: 0;">
';

foreach ($rows as $row) { // Assuming $rows contains all candidates
    $output .= '
                            <li style="padding: 10px; border-bottom: 1px solid #ddd;">
                                <strong style="font-size: 16px;">' . $row['name'] . '</strong>
                                <p style="margin: 5px 0; font-size: 14px; color: #666;">' . $row['description'] . '</p>
                            </li>
    ';
}

$output .= '
                        </ul>
                    </div>
                </div>
                <div class="box-footer" style="padding: 10px; background-color: #f9f9f9; text-align: center;">
                    <button class="btn btn-primary btn-sm" onclick="navigatePage(\'prev\')" style="margin-right: 5px;">Previous</button>
                    <button class="btn btn-primary btn-sm" onclick="navigatePage(\'next\')">Next</button>
                </div>
            </div>
        </div>
    </div>
';
    }
    // Include the election title, academic year, voter statistics, and content in the response
    $response = [
        'title' => $election_title,
        'academic_yr' => $academic_yr,
        'total_voters' => $total_voters,
        'voters_voted' => $voters_voted,
        'remaining_voters' => $remaining_voters,
        'content' => $output
    ];

    echo json_encode($response);
} else {
    echo json_encode('Invalid Request');
}
?>