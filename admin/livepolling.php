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

            <!-- Bar Graphs for President and Vice President -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Election Results</h3>
                        </div>
                        <div class="box-body">
                            <!-- Combined Bar Graph Container -->
                            <div id="combinedGraph" style="height: 300px;"></div>
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
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Initialize combined chart
    var combinedChart;

    // Function to generate combined bar graph with president and vice president candidates grouped side by side
    function generateCombinedGraph(presidentDataPoints, vicePresidentDataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title: {
                text: "Election Results"
            },
            axisY: {
                title: "Vote Count"
            },
            axisX: {
                title: "Candidates"
            },
            data: [{
                type: "stackedColumn",
                showInLegend: true,
                name: "President",
                color: "blue",
                dataPoints: presidentDataPoints
            },
            {
                type: "stackedColumn",
                showInLegend: true,
                name: "Vice President",
                color: "green",
                dataPoints: vicePresidentDataPoints
            }]
        });
        chart.render();
        return chart;
    }

    // Function to update combined bar graph
    function updateCombinedGraph(presidentDataPoints, vicePresidentDataPoints, chart) {
        chart.options.data[0].dataPoints = presidentDataPoints;
        chart.options.data[1].dataPoints = vicePresidentDataPoints;
        chart.render();
    }

    // Fetch and update data initially
    updateData();

    // Fetch and update data every 3 seconds
    setInterval(updateData, 3000);

    // Function to fetch updated data from the server
    function updateData() {
        $.ajax({
            url: 'update_data.php',
            type: 'GET',
            dataType: 'json',
            data: {organization: $('#organization').val()},
            success: function(response) {
                // Update combined bar graph
                if (!combinedChart) {
                    combinedChart = generateCombinedGraph(response.presidentData, response.vicePresidentData, "combinedGraph");
                } else {
                    updateCombinedGraph(response.presidentData, response.vicePresidentData, combinedChart);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }
</script>
</body>
</html>
