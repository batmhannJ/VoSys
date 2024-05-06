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
    // Function to generate bar graph for President, Vice President, and Secretary
    function generateBarGraph(presidentData, vicePresidentData, secretaryData, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title:{
                text: "Election Results - JPCS Organization"
            },
            axisY: {
                title: "Vote Counts",
                includeZero: true
            },
            data: [{
                type: "column",
                name: "President Votes",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: presidentData
            },
            {
                type: "column",
                name: "Vice President Votes",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: vicePresidentData
            },
            {
                type: "column",
                name: "Secretary Votes",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: secretaryData
            }]
        });
        chart.render();
    }

    // Function to fetch updated data from the server for JPCS organization
    function updateData() {
        $.ajax({
            url: 'update_jpcs_data.php', // Change this to the URL of your update data script
            type: 'GET',
            dataType: 'json',
            data: {organization: 'JPCS'}, // Hardcoded organization to JPCS
            success: function(response) {
                // Update bar graph for President, Vice President, and Secretary
                generateBarGraph(response.presidentData, response.vicePresidentData, response.secretaryData, "electionGraph");
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Call the updateData function initially
    updateData();

    // Call the updateData function every 60 seconds (adjust as needed)
    setInterval(updateData, 60000); // 60000 milliseconds = 60 seconds
</script>

</body>
</html>
