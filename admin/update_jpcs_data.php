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
            <!-- Bar Graph for President and Vice President - JPCS Organization -->
            <div class="row">
                <!-- President and Vice President Bar Graph Box -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Election Results - JPCS Organization</b></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Bar Graph Container for President and Vice President -->
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
            title: "Vote Counts",
            includeZero: true
        },
        axisY2: {
            title: "Percentage (%)",
            includeZero: true
        },
        data: [
            {
                type: "column",
                name: "President and Vice President Votes",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: []
            },
            {
                type: "column",
                name: "Other Candidates Votes",
                axisYType: "secondary",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: []
            }
        ]
    });
    chart.render();

    // Function to fetch updated data from the server for JPCS organization and update the chart
    function updateData() {
        $.ajax({
            url: 'update_jpcs_data.php', // URL of your update data script
            type: 'GET',
            dataType: 'json',
            data: { organization: 'JPCS' }, // Hardcoded organization to JPCS
            success: function(response) {
                if (response && response.length > 0) {
                    // Extracting data for President and Vice President Votes
                    var presidentVicePresidentData = response.presidentVicePresidentVotes;
                    // Extracting data for Other Candidates Votes
                    var otherCandidatesData = response.otherCandidatesVotes;

                    // Update data points for President and Vice President Votes
                    chart.options.data[0].dataPoints = presidentVicePresidentData;
                    // Update data points for Other Candidates Votes
                    chart.options.data[1].dataPoints = otherCandidatesData;

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
