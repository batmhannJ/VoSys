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
    
    // Output the design structure
    $output .= '
    <div class="container mt-4">
        <!-- Election Header Section -->
        <div class="text-center mb-4">
            <h2><strong>' . $election_title . '</strong></h2>
            <p class="text-muted">' . $academic_yr . ' Election</p>
        </div>

        <!-- Voter Stats Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Total Voters</h5>
                    </div>
                    <div class="card-body">
                        <h3>' . $total_voters . '</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5>Voters Voted</h5>
                    </div>
                    <div class="card-body">
                        <h3>' . $voters_voted . '</h3>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: ' . (($voters_voted / $total_voters) * 100) . '%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5>Remaining Voters</h5>
                    </div>
                    <div class="card-body">
                        <h3>' . $remaining_voters . '</h3>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: ' . (($remaining_voters / $total_voters) * 100) . '%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories and Candidates Section -->
        <div class="row">
    ';

    while ($row = $query->fetch_assoc()) {
        $max_vote = $row['max_vote'];  // Fetch max_vote for the category

        // Fetch candidates for the category
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
            $winner_label = ($is_winner_marked < $max_vote) ? '<span class="badge badge-success">Winner</span>' : '';
            if ($is_winner_marked < $max_vote) {
                $is_winner_marked++;  // Increment winner count for each winner marked
            }

            $candidate_list .= '
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <img src="' . $candidate['photo'] . '" height="100px" width="100px" class="rounded-circle mr-3">
                    <div>
                        <span class="font-weight-bold">' . $candidate['name'] . '</span><br>
                        <small>Votes: ' . $candidate['votes'] . '</small>
                    </div>
                    ' . $winner_label . '
                </li>
            ';
        }

        $output .= '
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5>' . $row['name'] . '</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">' . $candidate_list . '</ul>
                    </div>
                </div>
            </div>
        ';
    }

    $output .= '
        </div>
    </div>
    ';

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

