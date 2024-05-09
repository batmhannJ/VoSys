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

            <!-- Dual Bar Graphs for President and Vice President -->
            <div class="row">
                <!-- Dual Graph Box -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vote Counts</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Dual Bar Graph Container -->
                            <div id="dualGraph" style="height: 300px;"></div>
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
<!-- Bar Graph Script -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Function to generate bar graph
    function generateBarGraph(dataPoints1, dataPoints2, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title:{
                text: "Vote Counts"
            },
            axisY: [{
                title: "President Candidates"
            }, {
                title: "Vice President Candidates",
                reversed: true
            }],
            axisX: {
                title: "Vote Count",
                includeZero: true
            },
            data: [{
                type: "bar",
                showInLegend: true,
                legendText: "President",
                dataPoints: dataPoints1
            }, {
                type: "bar",
                showInLegend: true,
                legendText: "Vice President",
                dataPoints: dataPoints2
            }]
        });
        chart.render();
        return chart;
    }

    // Initialize chart
    var dualChart;

    // Function to fetch updated data from the server
    function updateData() {
        $.ajax({
            url: 'update_data.php', // Change this to the URL of your update data script
            type: 'GET',
            dataType: 'json',
            data: {organization: $('#organization').val()}, // Pass the selected organization to the server
            success: function(response) {
                // Update chart
                if (!dualChart) {
                    dualChart = generateBarGraph(response.presidentData, response.vicePresidentData, "dualGraph");
                } else {
                    updateBarGraph(response.presidentData, response.vicePresidentData, dualChart);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Function to update bar graph with animation
    function updateBarGraph(newDataPoints1, newDataPoints2, chart) {
        for (var i = 0; i < newDataPoints1.length; i++) {
            chart.options.data[0].dataPoints[i].y = newDataPoints1[i].y;
        }
        for (var i = 0; i < newDataPoints2.length; i++) {
            chart.options.data[1].dataPoints[i].y = newDataPoints2[i].y;
        }
        chart.render();
    }

    // Call the updateData function initially
    updateData();

    // Call the updateData function every 60 seconds (adjust as needed)
    setInterval(updateData, 3000); // 60000 milliseconds = 60 seconds
</script>
</body>
</html>
