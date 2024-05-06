<!DOCTYPE html>
<html>
<head>
    <title>Election Results</title>
    <!-- CSS and other header includes -->
    <?php include 'includes/header.php'; ?>
</head>
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
            <!-- Dual-Y Line Chart for President Candidates - JPCS Organization -->
            <div class="row">
                <!-- President Candidates Dual-Y Line Chart Box -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Election Results - President Candidates (JPCS Organization)</b></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Dual-Y Line Chart Container for President Candidates -->
                            <div id="presidentChart" style="height: 300px;"></div>
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
<!-- Dual-Y Line Chart Script -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Initialize the chart with empty data points
    var chart = new CanvasJS.Chart("presidentChart", {
        animationEnabled: true,
        title: {
            text: "Election Results - President Candidates (JPCS Organization)"
        },
        axisY: {
            title: "Vote Counts",
            includeZero: true
        },
        axisY2: {
            title: "Percentage",
            includeZero: true
        },
        data: [{
            type: "column",
            name: "President Votes",
            showInLegend: true,
            yValueFormatString: "#,##0",
            dataPoints: []
        },
        {
            type: "line",
            name: "Percentage of Total Votes",
            axisYType: "secondary",
            showInLegend: true,
            yValueFormatString: "#,##0.##'%'",
            dataPoints: []
        }]
    });
    chart.render();

    // Function to fetch updated data from the server for President candidates of JPCS organization and update the chart
    function updateData() {
        $.ajax({
            url: 'update_jpcs_president_data.php', // URL of your update data script for President candidates
            type: 'GET',
            dataType: 'json',
            data: { organization: 'JPCS' }, // Hardcoded organization to JPCS
            success: function(response) {
                if (response && response.length > 0) {
                    console.log("Data received:", response); // Log the received data
                    // Update data points for President Votes and Percentage of Total Votes
                    chart.options.data[0].dataPoints = response.map(function(item) {
                        return {label: item.label, y: item.presidentVotes};
                    });
                    chart.options.data[1].dataPoints = response.map(function(item) {
                        return {label: item.label, y: (item.presidentVotes / item.totalVotes) * 100};
                    });
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
