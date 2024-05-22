<?php
include 'includes/session.php';
include 'includes/header.php';
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Election Results
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Results</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Organization Filter -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <form method="get" action="">
                                <div class="form-group">
                                    <label for="organization">Select Organization:</label>
                                    <select class="form-control" name="organization" id="organization">
                                        <option value="">All Organizations</option>
                                        <?php
                                        // Fetch and display organizations
                                        $organizationQuery = $conn->query("SELECT DISTINCT organization FROM voters");
                                        while($organizationRow = $organizationQuery->fetch_assoc()){
                                            $selected = ($_GET['organization'] ?? '') == $organizationRow['organization'] ? 'selected' : '';
                                            echo "<option value='".$organizationRow['organization']."' $selected>".$organizationRow['organization']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bar Graphs for Various Positions -->
            <div class="row">
                <!-- President Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">President Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Vice President for Internal Affairs Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President for Internal Affairs Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentInternalGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Vice President for External Affairs Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President for External Affairs Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentExternalGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Secretary Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Secretary Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="secretaryGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Treasurer Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Treasurer Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="treasurerGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Auditor Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Auditor Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="auditorGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- P.R.O. Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">P.R.O. Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="proGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Director for Membership Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Director for Membership Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="directorMembershipGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Director for Special Project Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Director for Special Project Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="directorSpecialProjectGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Block A 1st Year Representative Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block A 1st Year Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="blockA1stYearGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Block B 1st Year Representative Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block B 1st Year Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="blockB1stYearGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Block A 2nd Year Representative Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block A 2nd Year Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="blockA2ndYearGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Block B 2nd Year Representative Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block B 2nd Year Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="blockB2ndYearGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Block A 3rd Year Representative Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block A 3rd Year Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="blockA3rdYearGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Block B 3rd Year Representative Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block B 3rd Year Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="blockB3rdYearGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Block A 4th Year Representative Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block A 4th Year Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="blockA4thYearGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Block B 4th Year Representative Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block B 4th Year Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="blockB4thYearGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/votes_modal.php'; ?>
</div>
<!-- ./wrapper -->
<?php include 'includes/scripts.php'; ?>
<!-- Bar Graph Script -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    // Function to generate bar graph
    function generateBarGraph(dataPoints, containerId, title) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title: {
                text: title
            },
            axisX: {
                title: "Vote Count",
                includeZero: true
            },
            axisY: {
                title: "Candidates"
            },
            data: [{
                type: "bar",
                dataPoints: dataPoints
            }]
        });
        chart.render();
    }

    // Fetch and process data for President
    <?php
    $presidentData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'President'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $presidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($presidentData); ?>, "presidentGraph", "President Candidates Vote Count");
    "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($proData); ?>, "proGraph", "P.R.O. Candidates Vote Count");

    // Fetch and process data for Director for Membership
    <?php
    $directorMembershipData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Director for Membership'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $directorMembershipData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($directorMembershipData); ?>, "directorMembershipGraph", "Director for Membership Candidates Vote Count");

    // Fetch and process data for Director for Special Project
    <?php
    $directorSpecialProjectData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Director for Special Project'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $directorSpecialProjectData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($directorSpecialProjectData); ?>, "directorSpecialProjectGraph", "Director for Special Project Candidates Vote Count");

    // Fetch and process data for Block A 1st Year Representative
    <?php
    $blockA1stYearData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Block A 1st Year Representative'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $blockA1stYearData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($blockA1stYearData); ?>, "blockA1stYearGraph", "Block A 1st Year Representative Candidates Vote Count");

    // Fetch and process data for Block B 1st Year Representative
    <?php
    $blockB1stYearData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Block B 1st Year Representative'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $blockB1stYearData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($blockB1stYearData); ?>, "blockB1stYearGraph", "Block B 1st Year Representative Candidates Vote Count");

    // Fetch and process data for Block A 2nd Year Representative
    <?php
    $blockA2ndYearData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Block A 2nd Year Representative'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $blockA2ndYearData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($blockA2ndYearData); ?>, "blockA2ndYearGraph", "Block A 2nd Year Representative Candidates Vote Count");

    // Fetch and process data for Block B 2nd Year Representative
    <?php
    $blockB2ndYearData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Block B 2nd Year Representative'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $blockB2ndYearData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($blockB2ndYearData); ?>, "blockB2ndYearGraph", "Block B 2nd Year Representative Candidates Vote Count");

    // Fetch and process data for Block A 3rd Year Representative
    <?php
    $blockA3rdYearData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Block A 3rd Year Representative'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $blockA3rdYearData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($blockA3rdYearData); ?>, "blockA3rdYearGraph", "Block A 3rd Year Representative Candidates Vote Count");

    // Fetch and process data for Block B 3rd Year Representative
    <?php
    $blockB3rdYearData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Block B 3rd Year Representative'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $blockB3rdYearData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($blockB3rdYearData); ?>, "blockB3rdYearGraph", "Block B 3rd Year Representative Candidates Vote Count");

    // Fetch and process data for Block A 4th Year Representative
    <?php
    $blockA4thYearData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Block A 4th Year Representative'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $blockA4thYearData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($blockA4thYearData); ?>, "blockA4thYearGraph", "Block A 4th Year Representative Candidates Vote Count");

    // Fetch and process data for Block B 4th Year Representative
    <?php
    $blockB4thYearData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Block B 4th Year Representative'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $blockB4thYearData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($blockB4thYearData); ?>, "blockB4thYearGraph", "Block B 4th Year Representative Candidates Vote Count");

</script>
</body>
</html>
