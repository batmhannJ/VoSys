<?php
include 'includes/session.php';
include 'includes/slugify.php';

if (isset($_POST['election_id'])) {
    $election_id = $_POST['election_id'];
    
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

    echo json_encode($output);
} else {
    echo json_encode('Invalid Request');
}
?>