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
    $winner_label = ($is_winner_marked < $max_vote) ? '<span class="badge badge-success" style="font-size: 10px; padding: 2px 5px;">Winner</span>' : '';
    if ($is_winner_marked < $max_vote) {
        $is_winner_marked++;  // Increment winner count for each winner marked
    }

    $candidate_list .= '
        <div style="display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #ddd;">
            <img src="' . $candidate['photo'] . '" height="50px" width="50px" style="border-radius: 50%; object-fit: cover; margin-right: 10px;">
            <div style="flex: 1;">
                <div style="font-size: 14px; font-weight: bold; color: #333;">' . $candidate['name'] . '</div>
                <div style="font-size: 12px; color: #666;">Votes: ' . $candidate['votes'] . '</div>
            </div>
            ' . $winner_label . '
        </div>
    ';
}

$output .= '
<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12">
        <div class="card" id="' . $row['id'] . '" style="border: 1px solid #ccc; border-radius: 5px; overflow: hidden;">
            <div class="card-header" style="background-color: #007bff; color: #fff; padding: 10px; text-align: center; font-size: 14px; font-weight: bold;">
                ' . $row['name'] . '
            </div>
            <div class="card-body" style="padding: 10px; background-color: #f9f9f9; height: 300px; overflow-y: auto;">
                <div id="candidate_list">
                    ' . $candidate_list . '
                </div>
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