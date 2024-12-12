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

       $candidate_list .= '
    <div style="display: flex; align-items: center; padding: 20px; border-bottom: 1px solid #ddd; opacity: ' . ($candidate['is_winner'] ? '1' : '0.5') . ';">
        <div style="flex: 0 0 90px; text-align: center;">
            <img src="' . $candidate['photo'] . '" height="90px" width="90px" style="border-radius: 50%; object-fit: cover; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        </div>
        <div style="flex: 1; padding-left: 20px;">
            <div style="font-size: 18px; font-weight: bold; color: #333;">' . $candidate['name'] . '</div>
            <div style="font-size: 16px; color: #666; margin-top: 5px;">Votes: ' . $candidate['votes'] . '</div>
        </div>
        <div style="flex: 0 0 auto; text-align: center; padding-left: 10px;">
            ' . ($candidate['is_winner'] ? '<div style="font-size: 16px; color: #fff; background-color: #28a745; padding: 5px 10px; border-radius: 20px; font-weight: bold;">Winner</div>' : '') . '
        </div>
    </div>
';

// Build the complete output
$output .= '
<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12">
        <div class="card" id="' . $row['id'] . '" style="border: 1px solid #ccc; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.15); overflow: hidden;">
            <div class="card-header" style="background-color: #006400; color: #fff; padding: 20px; font-size: 20px; font-weight: bold; text-align: center;">
                ' . $row['name'] . '
            </div>
            <div class="card-body" style="padding: 0; max-height: 400px; overflow-y: auto; background-color: #f9f9f9;">
                <div id="candidate_list" style="padding: 10px;">
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