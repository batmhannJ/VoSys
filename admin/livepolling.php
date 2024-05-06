<?php
// Include necessary PHP files
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
                <h1>Election Results</h1>
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
                                            <?php
                                            // Fetch and display organizations
                                            $organizationQuery = $conn->query("SELECT DISTINCT organization FROM voters");
                                            while ($organizationRow = $organizationQuery->fetch_assoc()) {
                                                $selected = ($_GET['organization'] ?? '') == $organizationRow['organization'] ? 'selected' : '';
                                                echo "<option value='" . $organizationRow['organization'] . "' $selected>" . $organizationRow['organization'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- Remove the filter button -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bar Graphs for President, Vice President, and Secretary -->
                <div class="row">
                    <!-- President Bar Graph Box -->
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>President Candidates</b></h3>
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

                    <!-- Vice President Bar Graph Box -->
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>Vice President Candidates</b></h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- Vice President Bar Graph Container -->
                                <div id="vicePresidentGraph" style="height: 300px;"></div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- Secretary Bar Graph Box -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>Secretary Candidates</b></h3>
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
                title: {
                    text: "Vote Counts"
                },
                axisY: {
                    title: "Candidates",
                    includeZero: true,
                    labelFormatter: function (e) {
                        return Math.round(e.value);
                    }
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
        }

        // Function to fetch data and update graphs
        function updateGraphs(organization) {
            $.ajax({
                url: 'update_data.php', // Change this to the URL of your update data script
                type: 'GET',
                dataType: 'json',
                data: { organization: organization },
                success: function (response) {
                    // Update president bar graph
                    generateBarGraph(response.presidentData, "presidentGraph");

                    // Update vice president bar graph
                    generateBarGraph(response.vicePresidentData, "vicePresidentGraph");

                    // Update secretary bar graph
                    generateBarGraph(response.secretaryData, "secretaryGraph");
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data: ' + error);
                }
            });
        }

        // Call updateGraphs initially with default organization
        var selectedOrganization = $('#organization').val();
        updateGraphs(selectedOrganization);

        // Call updateGraphs when organization is changed
        $('#organization').change(function () {
            selectedOrganization = $(this).val();
            updateGraphs(selectedOrganization);
        });
    </script>

</body>

</html>
