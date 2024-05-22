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

            <!-- Combined Column Graph for President and Vice President -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Combined Column Graph Container -->
                            <div id="combinedGraph" style="height: 400px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
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
<!-- Column Graph Script -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Function to generate combined column graph with multiple axes
    function generateCombinedGraph(presidentDataPoints, vicePresidentDataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title: {
                text: "Vote Counts"
            },
            axisY: {
                title: "Vote Count",
                includeZero: true
            },
            axisX: {
                title: "Candidates"
            },
            data: [
                {
                    type: "column",
                    name: "President",
                    showInLegend: true,
                    color: "#4F81BC", // Blue color for president candidates
                    dataPoints: presidentDataPoints
                },
                {
                    type: "column",
                    name: "Vice President",
                    showInLegend: true,
                    color: "#C0504E", // Red color for vice president candidates
                    dataPoints: vicePresidentDataPoints
                }
            ]
        });
        chart.render();
        return chart;
    }

    // Initialize chart
    var combinedChart;

    // Function to fetch updated data from the server
    function updateData() {
        $.ajax({
            url: 'update_data.php', // Change this to the URL of your update data script
            type: 'GET',
            dataType: 'json',
            data: {organization: $('#organization').val()}, // Pass the selected organization to the server
            success: function(response) {
                // Update combined column graph
                if (response.presidentData.length > 0 || response.vicePresidentData.length > 0) {
                    if (!combinedChart) {
                        combinedChart = generateCombinedGraph(response.presidentData, response.vicePresidentData, "combinedGraph");
                    } else {
                        updateCombinedGraph(response.presidentData, response.vicePresidentData, combinedChart);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Function to update combined column graph with animation
    function updateCombinedGraph(newPresidentDataPoints, newVicePresidentDataPoints, chart) {
        chart.options.data[0].dataPoints = newPresidentDataPoints;
        chart.options.data[1].dataPoints = newVicePresidentDataPoints;
        chart.render();
    }

    // Initialize chart
    var combinedChart = generateCombinedGraph([], [], "combinedGraph");

    // Call the updateData function initially
    updateData();

    // Call the updateData function every 5 seconds
    setInterval(updateData, 5000);

    // Update data when organization is changed
    $('#organization').change(function() {
        updateData();
    });
</script>
</body>
</html>
