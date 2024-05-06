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
            <!-- Bar Graph for President, Vice President, and Secretary - JPCS Organization -->
            <div class="row">
                <!-- President, Vice President, and Secretary Bar Graph Box -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Election Results - JPCS Organization</b></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Bar Graph Container for President, Vice President, and Secretary -->
                            <div id="electionGraph" style="height: 300px;"></div>
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
    // Initialize the chart with empty data points
    var chart = new CanvasJS.Chart("electionGraph", {
        animationEnabled: true,
        title: {
            text: "Election Results - JPCS Organization"
        },
        axisY: {
            title: "President and Vice President Votes",
            includeZero: true
        },
        axisY2: {
            title: "Secretary Votes",
            includeZero: true,
            suffix: ""
        },
        data: [{
            type: "column",
            name: "President and Vice President Votes",
            showInLegend: true,
            yValueFormatString: "#,##0",
            dataPoints: [],
            indexLabel: "{y}", // Displays y value on top of the bar
            indexLabelFontColor: "black", // Color of the y value text
            indexLabelPlacement: "inside", // Position of the y value text
            indexLabelFontSize: 14, // Font size of the y value text
            indexLabelFontWeight: "bold", // Font weight of the y value text
            indexLabelMaxWidth: 40, // Max width of the y value text
            indexLabelWrap: true, // Wrap text if exceeds max width
            width: 40, // Width of the bars
            yAxisType: "primary" // Associate with primary y-axis
        }, {
            type: "column",
            name: "Secretary Votes",
            showInLegend: true,
            yValueFormatString: "#,##0",
            dataPoints: [],
            indexLabel: "{y}", // Displays y value on top of the bar
            indexLabelFontColor: "black", // Color of the y value text
            indexLabelPlacement: "inside", // Position of the y value text
            indexLabelFontSize: 14, // Font size of the y value text
            indexLabelFontWeight: "bold", // Font weight of the y value text
            indexLabelMaxWidth: 40, // Max width of the y value text
            indexLabelWrap: true, // Wrap text if exceeds max width
            width: 40, // Width of the bars
            yAxisType: "secondary" // Associate with secondary y-axis
        }]
    });
    chart.render();

    // Function to fetch updated data from the server for JPCS organization and update the chart
    function updateData() {
        $.ajax({
            url: 'update_jpcs_data.php', // Change this to the URL of your update data script
            type: 'GET',
            dataType: 'json',
            data: { organization: 'JPCS' }, // Hardcoded organization to JPCS
            success: function(response) {
                if (response && response.length > 0) {
                    console.log("Data received:", response); // Log the received data
                    // Update data points for President and Vice President Votes
                    chart.options.data[0].dataPoints = response.presidentAndVicePresidentData;
                    // Update data points for Secretary Votes
                    chart.options.data[1].dataPoints = response.secretaryData;
                    // Re-render the chart with updated data
                    chart.render();
                } else {
                    console.error("Empty or invalid data received.");
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error); // Log any errors
            }
        });
    }

    // Call the updateData function initially
    updateData();

    // Call the updateData function every 10 seconds (adjust as needed)
    setInterval(updateData, 10000); // 10000 milliseconds = 10 seconds
</script>

</body>
</html>
