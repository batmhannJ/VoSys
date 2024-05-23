<?php
include 'includes/session.php';
include 'includes/slugify.php';

// Assume user's organization is stored in session
$user_organization = $_SESSION['organization'];

// Define the mapping of organizations to category names
$organization_to_category = [
    'JPCS' => 'BSIT Representative',
    'HMSO' => 'BSHM Representative',
    'PASOA' => 'BSOAD Representative',
    'CODE-TG' => 'BSCRIM Representative',
    'YMF' => ['BSED Representative', 'BEED Representative']  // Assuming YMF can have both categories
];

// Check if the user's organization is in the mapping
if (isset($organization_to_category[$user_organization])) {
    $categories = $organization_to_category[$user_organization];
    if (is_array($categories)) {
        $categories = "'" . implode("', '", $categories) . "'";
    } else {
        $categories = "'$categories'";
    }
} else {
    // If the organization is not mapped, return an empty result or handle as needed
    echo json_encode([]);
    exit;
}

$output = '';
$candidate = '';

// Debug: Output the categories variable
// Remove or comment out these lines after debugging
echo "User Organization: $user_organization<br>";
echo "Categories to fetch: $categories<br>";

// Fetch categories based on the user's organization
$sql = "SELECT * FROM categories WHERE election_id = 20 AND name IN ($categories) ORDER BY priority ASC";
$query = $conn->query($sql);

// Debug: Check if the query is executed correctly
if (!$query) {
    echo "SQL Error: " . $conn->error;
    exit;
}

$num = 1;
while ($row = $query->fetch_assoc()) {
    $input = ($row['max_vote'] > 1) ? '<input type="checkbox" class="flat-red ' . slugify($row['name']) . '" name="' . slugify($row['name']) . "[]\">" : '<input type="radio" class="flat-red ' . slugify($row['name']) . '" name="' . slugify($row['name']) . '">';

    // Update SQL query to include organization condition
    $sql = "SELECT * FROM candidates WHERE category_id='" . $row['id'] . "' AND archived = 0 AND organization = '" . $conn->real_escape_string($user_organization) . "'";
    $cquery = $conn->query($sql);

    // Debug: Check if the query is executed correctly
    if (!$cquery) {
        echo "SQL Error: " . $conn->error;
        exit;
    }

    while ($crow = $cquery->fetch_assoc()) {
        $image = (!empty($crow['photo'])) ? '../images/' . $crow['photo'] : '../images/profile.jpg';
        $candidate .= '
            <li>
                ' . $input . '<button class="btn btn-primary btn-sm btn-flat clist"><i class="fa fa-search"></i> Platform</button><img src="' . $image . '" height="100px" width="100px" class="clist"><span class="cname clist">' . $crow['firstname'] . ' ' . $crow['lastname'] . '</span>
            </li>
        ';
    }

    $instruct = ($row['max_vote'] > 1) ? 'You may select up to ' . $row['max_vote'] . ' candidates' : 'Select only one candidate';

    $updisable = ($row['priority'] == 1) ? 'disabled' : '';
    $downdisable = ($row['priority'] == $pquery->num_rows) ? 'disabled' : '';

    $output .= '
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-solid" id="' . $row['id'] . '">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>' . $row['name'] . '</b></h3>
                        <div class="pull-right box-tools">
                            <button type="button" class="btn btn-default btn-sm moveup" data-id="' . $row['id'] . '" ' . $updisable . '><i class="fa fa-arrow-up"></i> </button>
                            <button type="button" class="btn btn-default btn-sm movedown" data-id="' . $row['id'] . '" ' . $downdisable . '><i class="fa fa-arrow-down"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <p>' . $instruct . '
                            <span class="pull-right">
                                <button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="' . slugify($row['description']) . '"><i class="fa fa-refresh"></i> Reset</button>
                            </span>
                        </p>
                        <div id="candidate_list">
                            <ul>
                                ' . $candidate . '
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';

    $sql = "UPDATE categories SET priority = '$num' WHERE id = '" . $row['id'] . "'";
    $conn->query($sql);

    $num++;
    $candidate = '';
}

echo json_encode($output);
?>
