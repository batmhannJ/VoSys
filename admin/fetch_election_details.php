<?php
include 'includes/session.php';
include 'includes/slugify.php';

if (isset($_POST['election_id'])) {
    $election_id = $_POST['election_id'];
    
    // Kunin ang mga kategorya ng election
    $sql = "SELECT * FROM categories WHERE election_id = '$election_id' ORDER BY priority ASC";
    $query = $conn->query($sql);

    $output = '';
    $candidate = '';

    while ($row = $query->fetch_assoc()) {
        // Kunin ang mga kandidato sa kasalukuyang kategorya
        $sql = "SELECT * FROM candidates WHERE category_id='".$row['id']."' ORDER BY votes DESC";
        $cquery = $conn->query($sql);
        
        $highestVotes = 0; // Panatilihin ang pinakamataas na boto
        $winner = '';      // Pangalan ng nanalo
        
        while ($crow = $cquery->fetch_assoc()) {
            $image = (!empty($crow['photo'])) ? '../images/'.$crow['photo'] : '../images/profile.jpg';
            $votes = $crow['votes']; // Kabuuang boto ng kandidato
            
            // Tukuyin ang nanalo
            if ($votes > $highestVotes) {
                $highestVotes = $votes;
                $winner = $crow['firstname'].' '.$crow['lastname'];
            }

            $candidate .= '
                <li style="list-style: none; margin-bottom: 15px;">
                    <img src="'.$image.'" height="100px" width="100px" style="margin-right: 10px;">
                    <span class="cname">'.$crow['firstname'].' '.$crow['lastname'].'</span>
                    <span class="badge badge-info" style="margin-left: 10px;">Total Votes: '.$votes.'</span>
                </li>
            ';
        }

        $instruct = 'Highest votes will determine the winner.';

        // Ipakita ang kategorya at ang nanalo
        $output .= '
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-solid" id="'.$row['id'].'">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>'.$row['name'].'</b></h3>
                        </div>
                        <div class="box-body">
                            <p>'.$instruct.'</p>
                            <div id="candidate_list">
                                <ul>'.$candidate.'</ul>
                            </div>
                            <p><b>Winner: '.$winner.'</b></p>
                        </div>
                    </div>
                </div>
            </div>
        ';

        $candidate = ''; // I-reset ang listahan ng kandidato para sa susunod na kategorya
    }

    echo json_encode($output);
} else {
    echo json_encode('Invalid Request');
}
?>