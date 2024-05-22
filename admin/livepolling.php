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

            <!-- Bar Graphs for President, Vice Presidents, and Secretary -->
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
            <!-- /.row -->
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

    // Fetch and process data for Vice President for Internal Affairs
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
    generateBarGraph(<?php echo json_encode($vicePresidentInternalData); ?>, "vicePresidentInternalGraph", "Vice President for Internal Affairs Candidates Vote Count");

    // Fetch and process data for Vice President for External Affairs
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
    generateBarGraph(<?php echo json_encode($vicePresidentExternalData); ?>, "vicePresidentExternalGraph", "Vice President for External Affairs Candidates Vote Count");

    // Fetch and process data for Secretary
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
    generateBarGraph(<?php echo json_encode($secretaryData); ?>, "secretaryGraph", "Secretary Candidates Vote Count");
</script>
</body>
</html>
