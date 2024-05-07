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
    // Function to generate bar graph with racing animation
    function generateBarChartRace(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title: {
                text: "Vote Counts"
            },
            axisY: {
                title: "Candidates",
                includeZero: true
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

        // Render chart
        chart.render();

        // Function to update data
        function updateData() {
            $.ajax({
                url: 'update_data.php', // Change this to the URL of your update data script
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Sort data based on vote counts
                    response.sort(function(a, b) {
                        return b.y - a.y;
                    });

                    // Update chart with new data
                    chart.options.data[0].dataPoints = response;
                    chart.render();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data: ' + error);
                }
            });
        }

        // Update data periodically
        setInterval(function () {
            updateData();
        }, 3000);
    }

    // Call the generateBarChartRace function for each graph
    $(document).ready(function () {
        // Generate bar chart race for each graph
        generateBarChartRace([], "presidentGraph");
        generateBarChartRace([], "vicePresidentGraph");
        generateBarChartRace([], "secretaryGraph");
    });
</script>


</body>
</html>
