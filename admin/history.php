<?php
include 'includes/session.php';
include 'includes/header.php';
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Election Results
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Results</li>
            </ol>
        </section>

        <section class="content">
            <!-- Organization Filter -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
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

            <!-- Ranking and Graphs Section -->
            <div class="row">
                <!-- Ranking Section -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Ranking of Candidates</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- President Ranking List -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            President Candidates
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Rank</th>
                                                            <th>Organization</th>
                                                            <th>Candidate</th>
                                                            <th>Vote Count</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    // Fetch and display president candidate ranking based on vote count and organization filter
                                                    $organizationFilter = !empty($_GET['organization']) ? " AND voters1.organization = '".$_GET['organization']."'" : "";
                                                    $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                                            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                                            FROM categories 
                                                            LEFT JOIN candidates ON categories.id = candidates.category_id
                                                            LEFT JOIN votes ON candidates.id = votes.candidate_id
                                                            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                                            WHERE voters1.organization != '' AND categories.name = 'President'".$organizationFilter."
                                                            GROUP BY voters1.organization, candidates.id
                                                            ORDER BY vote_count DESC";
                                                    $query = $conn->query($sql);
                                                    $rank = 1;
                                                    while($row = $query->fetch_assoc()){
                                                        echo "
                                                            <tr>
                                                            <td>".$rank."</td>
                                                            <td>".$row['organization']."</td>
                                                            <td>".$row['candidate_name']."</td>
                                                            <td>".$row['vote_count']."</td>
                                                            </tr>";
                                                        $rank++;
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Vice President for Internal Affairs Ranking List -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            VP Internal Affairs Candidates
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Rank</th>
                                                            <th>Organization</th>
                                                            <th>Candidate</th>
                                                            <th>Vote Count</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    // Fetch and display vice president candidate ranking based on vote count and organization filter
                                                    $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                                            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                                            FROM categories 
                                                            LEFT JOIN candidates ON categories.id = candidates.category_id
                                                            LEFT JOIN votes ON candidates.id = votes.candidate_id
                                                            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                                            WHERE voters1.organization != '' AND categories.name = 'Vice President for Internal Affairs'".$organizationFilter."
                                                            GROUP BY voters1.organization, candidates.id
                                                            ORDER BY vote_count DESC";
                                                    $query = $conn->query($sql);
                                                    $rank = 1;
                                                    while($row = $query->fetch_assoc()){
                                                        echo "
                                                            <tr>
                                                            <td>".$rank."</td>
                                                            <td>".$row['organization']."</td>
                                                            <td>".$row['candidate_name']."</td>
                                                            <td>".$row['vote_count']."</td>
                                                            </tr>";
                                                        $rank++;
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Vice President for External Affairs Ranking List -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            VP External Affairs Candidates
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Rank</th>
                                                            <th>Organization</th>
                                                            <th>Candidate</th>
                                                            <th>Vote Count</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    // Fetch and display vice president candidate ranking based on vote count and organization filter
                                                    $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                                            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                                            FROM categories 
                                                            LEFT JOIN candidates ON categories.id = candidates.category_id
                                                            LEFT JOIN votes ON candidates.id = votes.candidate_id
                                                            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                                            WHERE voters1.organization != '' AND categories.name = 'Vice President for External Affairs'".$organizationFilter."
                                                            GROUP BY voters1.organization, candidates.id
                                                            ORDER BY vote_count DESC";
                                                    $query = $conn->query($sql);
                                                    $rank = 1;
                                                    while($row = $query->fetch_assoc()){
                                                        echo "
                                                            <tr>
                                                            <td>".$rank."</td>
                                                            <td>".$row['organization']."</td>
                                                            <td>".$row['candidate_name']."</td>
                                                            <td>".$row['vote_count']."</td>
                                                            </tr>";
                                                        $rank++;
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Secretary Ranking List -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            Secretary Candidates
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Rank</th>
                                                            <th>Organization</th>
                                                            <th>Candidate</th>
                                                            <th>Vote Count</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    // Fetch and display secretary candidate ranking based on vote count and organization filter
                                                    $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                                            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                                            FROM categories 
                                                            LEFT JOIN candidates ON categories.id = candidates.category_id
                                                            LEFT JOIN votes ON candidates.id = votes.candidate_id
                                                            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                                            WHERE voters1.organization != '' AND categories.name = 'Secretary'".$organizationFilter."
                                                            GROUP BY voters1.organization, candidates.id
                                                            ORDER BY vote_count DESC";
                                                    $query = $conn->query($sql);
                                                    $rank = 1;
                                                    while($row = $query->fetch_assoc()){
                                                        echo "
                                                            <tr>
                                                            <td>".$rank."</td>
                                                            <td>".$row['organization']."</td>
                                                            <td>".$row['candidate_name']."</td>
                                                            <td>".$row['vote_count']."</td>
                                                            </tr>";
                                                        $rank++;
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bar Graphs for President, Vice Presidents, and Secretary -->
                <div class="row">
                    <!-- President Bar Graph Box -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                President Candidates Vote Count
                            </div>
                            <div class="card-body">
                                <div id="presidentGraph" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Vice President for Internal Affairs Bar Graph Box -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                VP Internal Affairs Candidates Vote Count
                            </div>
                            <div class="card-body">
                                <div id="vicePresidentInternalGraph" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Vice President for External Affairs Bar Graph Box -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                VP External Affairs Candidates Vote Count
                            </div>
                            <div class="card-body">
                                <div id="vicePresidentExternalGraph" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Secretary Bar Graph Box -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                Secretary Candidates Vote Count
                            </div>
                            <div class="card-body">
                                <div id="secretaryGraph" style="height: 300px;"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <span class="pull-right">
                                        <a href="export_results.php?organization=<?php echo $_GET['organization'] ?? ''; ?>" class="btn btn-success btn-sm btn-flat"><span class="glyphicon glyphicon-print"></span> Export PDF</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/votes_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<!-- Bar Graph Script -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    // Function to generate bar graph
    function generateBarGraph(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title:{
                text: "Vote Counts"
            },
            axisX: {
                title: "Candidates"
            },
            axisY: {
                title: "Vote Count",
                includeZero: true
            },
            data: [{
                type: "column",
                dataPoints: dataPoints
            }]
        });
        chart.render();
    }

    // Fetch and process president data
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
    generateBarGraph(<?php echo json_encode($presidentData); ?>, "presidentGraph");

    // Fetch and process vice president for internal affairs data
    <?php
    $vicePresidentInternalData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Vice President for Internal Affairs'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $vicePresidentInternalData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($vicePresidentInternalData); ?>, "vicePresidentInternalGraph");

    // Fetch and process vice president for external affairs data
    <?php
    $vicePresidentExternalData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Vice President for External Affairs'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $vicePresidentExternalData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($vicePresidentExternalData); ?>, "vicePresidentExternalGraph");

    // Fetch and process secretary data
    <?php
    $secretaryData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = 'Secretary'
            ".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $secretaryData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>
    generateBarGraph(<?php echo json_encode($secretaryData); ?>, "secretaryGraph");
</script>
</body>
</html>
