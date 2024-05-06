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
function generateBarGraph(dataPoints, containerId, organization) {
    var color;

    // Set color based on organization
    switch (organization) {
        case 'JPCS':
            color = "#4CAF50"; // Green
            break;
        case 'CSC':
            color = "#000000"; // Black
            break;
        case 'CODE-TG':
            color = "#800000"; // Maroon
            break;
        case 'YMF':
            color = "#00008b"; // Dark Blue
            break;
        case 'HMSO':
            color = "#cba328"; // Gold
            break;
        case 'PASOA':
            color = "#e6cc00"; // Yellow
            break;
        default:
            color = "#000000"; // Default to Black
    }

    var chart = new CanvasJS.Chart(containerId, {
        animationEnabled: true,
        title: {
            text: "Vote Counts"
        },
        axisY: {
            title: "Candidates",
            includeZero: true,
            labelFormatter: function (e) {
                // Include candidate name and round vote count to whole number
                return dataPoints[e.value].label + " - " + Math.round(e.value);
            }
        },
        axisX: {
            title: "Vote Count",
            includeZero: true
        },
        data: [{
            type: "bar", // Change type to "bar"
            dataPoints: dataPoints,
            color: color // Set the color based on organization
        }]
    });
    chart.render();
}


    // Function to fetch updated data from the server
    function updateData() {
        $.ajax({
            url: 'update_data.php', // Change this to the URL of your update data script
            type: 'GET',
            dataType: 'json',
            data: {organization: $('#organization').val()}, // Pass the selected organization to the server
            success: function(response) {
                // Update president bar graph with color based on organization
                generateBarGraph(response.presidentData, "presidentGraph", $('#organization').val());

                // Update vice president bar graph with color based on organization
                generateBarGraph(response.vicePresidentData, "vicePresidentGraph", $('#organization').val());

                // Update secretary bar graph with color based on organization
                generateBarGraph(response.secretaryData, "secretaryGraph", $('#organization').val());
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Call the updateData function initially
    updateData();

    // Call the updateData function every 60 seconds (adjust as needed)
    setInterval(updateData, 3000); // 60000 milliseconds = 60 seconds
</script>

</body>
</html>