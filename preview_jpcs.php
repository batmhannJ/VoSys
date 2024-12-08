<?php
    include 'includes/session.php';
    include 'includes/slugify.php';

    $output = array('error'=>false, 'list'=>'');

    // Query to fetch categories (positions)
    $sql = "SELECT * FROM categories WHERE election_id = 1";
    $query = $conn->query($sql);

    while($row = $query->fetch_assoc()){
        $position = slugify($row['name']);
        $pos_id = $row['id'];
        
        if(isset($_POST[$position])){

            // Handling multi-vote positions (more than 1 vote allowed)
            if($row['max_vote'] > 1){
                if(count($_POST[$position]) > $row['max_vote']){
                    $output['error'] = true;
                    $output['message'][] = '<li>You can only choose '.$row['max_vote'].' candidates for '.$row['description'].'</li>';
                }
                else{
                    foreach($_POST[$position] as $key => $values){
                        $sql = "SELECT * FROM candidates WHERE id = '$values'";
                        $cmquery = $conn->query($sql);
                        $cmrow = $cmquery->fetch_assoc();
                        $output['list'] .= "
                            <div class='row votelist'>
                                <span class='col-sm-5'><span class='pull-right'><b>".$row['name']." :</b></span></span> 
                                <span class='col-sm-7'>".$cmrow['firstname']." ".$cmrow['lastname']."</span>
                            </div>
                        ";
                    }
                }
            }
            // Handling single-vote positions (only 1 vote allowed)
            else{
                $candidate = $_POST[$position]; // Since max_vote = 1, it will be a single candidate ID
                $sql = "SELECT * FROM candidates WHERE id = '$candidate'";
                $csquery = $conn->query($sql);
                $csrow = $csquery->fetch_assoc();
                
                // Display the candidate name in the preview list for single vote
                $output['list'] .= "
                    <div class='row votelist'>
                        <span class='col-sm-5'><span class='pull-right'><b>".$row['name']." :</b></span></span> 
                        <span class='col-sm-7'>".$csrow['firstname']." ".$csrow['lastname']."</span>
                    </div>
                ";
            }
        }
    }

    echo json_encode($output);
?>
