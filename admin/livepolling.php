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

            <!-- Bar Graphs for Positions -->
            <div class="row">
                <!-- President Bar Graph Box -->
                <div class="col-md-6">
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

                <!-- Vice President for Internal Affairs Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President for Internal Affairs Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President for Internal Affairs Bar Graph Container -->
                            <div id="vicePresidentInternalGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- Vice President for External Affairs Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President for External Affairs Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President for External Affairs Bar Graph Container -->
                            <div id="vicePresidentExternalGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <!-- Secretary Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Secretary Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Secretary Bar Graph Container -->
                            <div id="secretaryGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- Treasurer Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Treasurer Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Treasurer Bar Graph Container -->
                            <div id="treasurerGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <!-- Auditor Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Auditor Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Auditor Bar Graph Container -->
                            <div id="auditorGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- P.R.O Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">P.R.O Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- P.R.O Bar Graph Container -->
                            <div id="proGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <!-- Director for Membership Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Director for Membership Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Director for Membership Bar Graph Container -->
                            <div id="membershipDirectorGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- Director for Special Project Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Director for Special Project Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Director for Special Project Bar Graph Container -->
                            <div id="specialProjectDirectorGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <!-- Block A 1st Year Representative Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block A 1st Year Representative Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Block A 1st Year Representative Bar Graph Container -->
                            <div id="blockA1stYearRepGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Add other positions' bar graph boxes here -->

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

                // Update vice presidents graphs
                vicePresidentInternalChart.options.data[0].dataPoints = response.vicePresidentInternalData;
                vicePresidentInternalChart.render();

                vicePresidentExternalChart.options.data[0].dataPoints = response.vicePresidentExternalData;
                vicePresidentExternalChart.render();

                // Update other position graphs here
                secretaryChart.options.data[0].dataPoints = response.secretaryData;
                secretaryChart.render();

                treasurerChart.options.data[0].dataPoints = response.treasurerData;
                treasurerChart.render();

                auditorChart.options.data[0].dataPoints = response.auditorData;
                auditorChart.render();

                proChart.options.data[0].dataPoints = response.proData;
                proChart.render();

                membershipDirectorChart.options.data[0].dataPoints = response.membershipDirectorData;
                membershipDirectorChart.render();

                specialProjectDirectorChart.options.data[0].dataPoints = response.specialProjectDirectorData;
                specialProjectDirectorChart.render();

                blockA1stYearRepChart.options.data[0].dataPoints = response.blockA1stYearRepData;
                blockA1stYearRepChart.render();

                // Update other positions' charts here
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Initialize charts
    var presidentChart = generateBarGraph([], "presidentGraph");
    var vicePresidentInternalChart = generateBarGraph([], "vicePresidentInternalGraph");
    var vicePresidentExternalChart = generateBarGraph([], "vicePresidentExternalGraph");
    var secretaryChart = generateBarGraph([], "secretaryGraph");
    var treasurerChart = generateBarGraph([], "treasurerGraph");
    var auditorChart = generateBarGraph([], "auditorGraph");
    var proChart = generateBarGraph([], "proGraph");
    var membershipDirectorChart = generateBarGraph([], "membershipDirectorGraph");
    var specialProjectDirectorChart = generateBarGraph([], "specialProjectDirectorGraph");
    var blockA1stYearRepChart = generateBarGraph([], "blockA1stYearRepGraph");
    // Initialize other positions' charts here

    // Call the updateDataAndGraphs function initially
    updateDataAndGraphs();

    // Call the updateDataAndGraphs function every 5 seconds
    setInterval(updateDataAndGraphs, 5000);
</script>
</body>
</html>
