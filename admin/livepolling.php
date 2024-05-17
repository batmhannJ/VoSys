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

            <!-- Bar Graphs for President and Vice Presidents -->
            <div class="row">
                <!-- President Bar Graph Box -->
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">President Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- President Bar Graph Container -->
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <!-- VP Internal Affairs Bar Graph Box -->
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">VP Internal Affairs Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- VP Internal Affairs Bar Graph Container -->
                            <div id="vpInternalAffairsGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <!-- VP External Affairs Bar Graph Box -->
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">VP External Affairs Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- VP External Affairs Bar Graph Container -->
                            <div id="vpExternalAffairsGraph" style="height: 300px;"></div>
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
    function generateBarGraph(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title:{
                text: "Vote Counts"
            },
            axisY: {
                title: "Candidates"
            },
            axisX: {
                title: "Vote Count",
                includeZero: true
            },
            data: [{
                type: "bar",
                dataPoints: dataPoints
            }]
        });
        chart.render();
        return chart;
    }

    // Initialize charts
    var presidentChart;
    var vpInternalAffairsChart;
    var vpExternalAffairsChart;

    // Function to fetch updated data from the server
    function updateData() {
        $.ajax({
            url: 'update_data.php', // Change this to the URL of your update data script
            type: 'GET',
            dataType: 'json',
            data: {organization: $('#organization').val()}, // Pass the selected organization to the server
            success: function(response) {
                // Update president bar graph
                if (response.presidentData.length > 0) {
                    if (!presidentChart) {
                        presidentChart = generateBarGraph(response.presidentData, "presidentGraph");
                    } else {
                        updateBarGraph(response.presidentData, presidentChart);
                    }
                }

                // Update VP Internal Affairs bar graph
                if (response.vpInternalAffairsData.length > 0) {
                    if (!vpInternalAffairsChart) {
                        vpInternalAffairsChart = generateBarGraph(response.vpInternalAffairsData, "vpInternalAffairsGraph");
                    } else {
                        updateBarGraph(response.vpInternalAffairsData, vpInternalAffairsChart);
                    }
                }

                // Update VP External Affairs bar graph
                if (response.vpExternalAffairsData.length > 0) {
                    if (!vpExternalAffairsChart) {
                        vpExternalAffairsChart = generateBarGraph(response.vpExternalAffairsData, "vpExternalAffairsGraph");
                    } else {
                        updateBarGraph(response.vpExternalAffairsData, vpExternalAffairsChart);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Function to update bar graph with animation
    function updateBarGraph(newDataPoints, chart) {
        chart.options.data[0].dataPoints = newDataPoints;
        chart.render();
    }

    // Initialize charts
    var presidentChart = generateBarGraph([], "presidentGraph");
    var vpInternalAffairsChart = generateBarGraph([], "vpInternalAffairsGraph");
    var vpExternalAffairsChart = generateBarGraph([], "vpExternalAffairsGraph");

    // Fetch initial data and update graphs
    updateData();

    // Set interval to update data and graphs every 5 seconds
    setInterval(updateData, 5000);
</script>
</body>
</html>
