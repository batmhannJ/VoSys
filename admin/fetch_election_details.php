<?php
include 'includes/session.php';
include 'includes/slugify.php';

if (isset($_POST['election_id'])) {
    $election_id = $_POST['election_id'];
    
    $sql = "SELECT * FROM categories WHERE election_id = '$election_id' ORDER BY priority ASC";
    $query = $conn->query($sql);

    $output = '';

    while ($row = $query->fetch_assoc()) {
        $sql = "SELECT * FROM candidates WHERE category_id='" . $row['id'] . "'";
        $cquery = $conn->query($sql);
        
        $candidates = [];
        while ($crow = $cquery->fetch_assoc()) {
            $total_votes = $crow['votes']; // Assuming you have a 'votes' column
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
        $is_winner_marked = false; // Track if the winner has been marked
        foreach ($candidates as $candidate) {
            $winner_label = (!$is_winner_marked) ? '<span class="label label-success">Winner</span>' : '';
            $is_winner_marked = true; // Only the first candidate is marked as the winner

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
                            <p>Select only one candidate</p>
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