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

            <!-- Combined Bar Graph for President, Vice Presidents, and Secretary -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="combinedGraph" style="height: 300px;"></div>
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

    // Fetch and process combined data
    <?php
    $combinedData = array();
    $positions = ["President", "Vice President for Internal Affairs", "Vice President for External Affairs", "Secretary"];
    foreach($positions as $position) {
        $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count, '$position' AS position
                FROM categories 
                LEFT JOIN candidates ON categories.id = candidates.category_id
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != '' AND categories.name = '$position'
                ".$organizationFilter."
                GROUP BY candidates.id";
        $query = $conn->query($sql);
        while($row = $query->fetch_assoc()) {
            $combinedData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']." (".$row['position'].")");
        }
    }
    ?>
    generateBarGraph(<?php echo json_encode($combinedData); ?>, "combinedGraph");
</script>
</body>
</html>
