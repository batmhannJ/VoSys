<?php
include 'includes/session.php';
include 'includes/slugify.php';

if (isset($_POST['election_id'])) {
    $election_id = $_POST['election_id'];
    
    $sql = "SELECT * FROM categories WHERE election_id = '$election_id' ORDER BY priority ASC";
    $query = $conn->query($sql);

    $output = '';
    $candidate = '';

    while ($row = $query->fetch_assoc()) {
        $input = ($row['max_vote'] > 1) 
            ? '<input type="checkbox" class="flat-red '.slugify($row['name']).'" name="'.slugify($row['name'])."[]".'">' 
            : '<input type="radio" class="flat-red '.slugify($row['name']).'" name="'.slugify($row['name']).'">';
        
        $sql = "SELECT * FROM candidates WHERE category_id='".$row['id']."'";
        $cquery = $conn->query($sql);
        while ($crow = $cquery->fetch_assoc()) {
            $image = (!empty($crow['photo'])) ? '../images/'.$crow['photo'] : '../images/profile.jpg';
            $candidate .= '
                <li>
                    '.$input.'<button class="btn btn-primary btn-sm btn-flat clist"><i class="fa fa-search"></i> Platform</button><img src="'.$image.'" height="100px" width="100px" class="clist"><span class="cname clist">'.$crow['firstname'].' '.$crow['lastname'].'</span>
                </li>
            ';
        }

        $instruct = ($row['max_vote'] > 1) 
            ? 'You may select up to '.$row['max_vote'].' candidates' 
            : 'Select only one candidate';

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
                        </div>
                    </div>
                </div>
            </div>
        ';

        $candidate = '';
    }

    echo json_encode($output);
} else {
    echo json_encode('Invalid Request');
}
?>
