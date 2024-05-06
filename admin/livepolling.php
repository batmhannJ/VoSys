<?php
// Include necessary PHP files
include 'includes/session.php';
include 'includes/header.php';

// Include database connection file if you have one
include 'includes/db_connection.php';

// Assuming you have a function to fetch data from the database
// Modify this according to your database structure and connection method
function getCandidateCounts($position) {
    global $conn; // Assuming $conn is your database connection

    $query = "SELECT COUNT(*) AS count FROM candidates WHERE position = '$position'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    } else {
        return 0;
    }
}

// Get counts for each position
$presidentCount = getCandidateCounts("President");
$vicePresidentCount = getCandidateCounts("Vice President");
$secretaryCount = getCandidateCounts("Secretary");
?>

<!-- HTML code for displaying bar graphs -->
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Election Results</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Results</li>
                </ol>
            </section>

            <section class="content">
                <!-- Bar Graphs for President, Vice President, and Secretary -->
                <div class="row">
                    <!-- President Bar Graph Box -->
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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

                    <!-- Secretary Bar Graph Box -->
                    <div class="col-md-4">
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
    </div>
    <!-- ./wrapper -->

    <!-- Include necessary JavaScript files -->
    <?php include 'includes/scripts.php'; ?>

    <!-- Bar Graph Script -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        // Function to generate bar graph with data
        function generateBarChart(count, containerId) {
            var chart = new CanvasJS.Chart(containerId, {
                animationEnabled: true,
                title: {
                    text: "Candidate Counts"
                },
                data: [{
                    type: "column",
                    dataPoints: [
                        { label: "Candidates", y: count }
                    ]
                }]
            });

            chart.render();
        }

        // Call the generateBarChart function for each graph
        $(document).ready(function () {
            generateBarChart(<?php echo $presidentCount; ?>, "presidentGraph");
            generateBarChart(<?php echo $vicePresidentCount; ?>, "vicePresidentGraph");
            generateBarChart(<?php echo $secretaryCount; ?>, "secretaryGraph");
        });
    </script>
</body>
</html>
