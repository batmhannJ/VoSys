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

            <!-- Bar Graphs for President, Vice President for Internal Affairs, and Vice President for External Affairs -->
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
                <!-- Vice President for Internal Affairs Bar Graph Box -->
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President for Internal Affairs Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President for Internal Affairs Bar Graph Container -->
                            <div id="vpInternalAffairsGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- Vice President for External Affairs Bar Graph Box -->
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President for External Affairs Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President for External Affairs Bar Graph Container -->
                            <div id="vpExternalAffairsGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
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
                type: "bar", // Change type to "bar"
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

    // Function to fetch updated data and update graphs
    function updateDataAndGraphs() {
        $.ajax({
            url: 'update_data.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Update president graph
                presidentChart.options.data[0].dataPoints = response.presidentData;
                presidentChart.render();

                // Update vice president for internal affairs graph
                vpInternalAffairsChart.options.data[0].dataPoints = response.vpInternalAffairsData;
                vpInternalAffairsChart.render();

                // Update vice president for external affairs graph
                vpExternalAffairsChart.options.data[0].dataPoints = response.vpExternalAffairsData;
                vpExternalAffairsChart.render();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Initialize charts
    presidentChart = generateBarGraph([], "presidentGraph");
    vpInternalAffairsChart = generateBarGraph([], "vpInternalAffairsGraph");
    vpExternalAffairsChart = generateBarGraph([], "vpExternalAffairsGraph");

    // Call the updateDataAndGraphs function initially
    updateDataAndGraphs();

    // Call the updateDataAndGraphs function every 5 seconds
    setInterval(updateDataAndGraphs, 5000);
</script>
</body>
</html>
