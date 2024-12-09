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
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-solid" id="' . $row['id'] . '">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>' . $row['name'] . '</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="candidate_list">
                                <ul>' . $candidate_list . '</ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }

    // Get the total number of JPCS voters
    $total_voters_sql = "SELECT COUNT(*) AS total_voters FROM voters WHERE election_id = '$election_id' AND organization = 'JPCS'";
    $total_voters_query = $conn->query($total_voters_sql);
    $total_voters_row = $total_voters_query->fetch_assoc();
    $total_voters = $total_voters_row['total_voters'];

    // Get the number of JPCS voters who have voted
    $voted_voters_sql = "SELECT COUNT(DISTINCT voter_id) AS voted_voters FROM votes WHERE election_id = '$election_id' AND organization = 'JPCS'";
    $voted_voters_query = $conn->query($voted_voters_sql);
    $voted_voters_row = $voted_voters_query->fetch_assoc();
    $voted_voters = $voted_voters_row['voted_voters'];

    // Calculate remaining voters
    $remaining_voters = $total_voters - $voted_voters;

    // Include the election title, academic year, total voters, voted voters, remaining voters, and content in the response
    $response = [
        'title' => $election_title,
        'academic_yr' => $academic_yr,
        'total_voters' => $total_voters,
        'voted_voters' => $voted_voters,
        'remaining_voters' => $remaining_voters,
        'content' => $output
    ];

    echo json_encode($response);
} else {
    echo json_encode('Invalid Request');
}
?>