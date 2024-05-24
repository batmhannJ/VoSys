<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>
<head>
    <style>
        .box-title {
            text-align: center;
            width: 100%;
            display: inline-block;
        }

        /* Back to Top button styles */
        #back-to-top {
            position: fixed;
            bottom: 40px;
            right: 40px;
            display: none;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 22px;
            line-height: 50px;
            cursor: pointer;
            z-index: 1000;
        }

        #back-to-top:hover {
            background-color: #555;
        }
    </style>
</head>
<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar_csc.php'; ?>
    <?php include 'includes/menubar_csc.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Election Results</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Results</li>
            </ol>
        </section>

        <section class="content">
            <div class="row justify-content-center">
                <!-- Your election result boxes here -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>President</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <!-- Repeat similar blocks for other positions -->
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/votes_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function generateBarGraph(dataPoints, containerId) {
        var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);
        
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            animationDuration: 2000, // Animation duration
            title: {
                text: "Vote Counts"
            },
            axisX: {
                title: "",
                includeZero: true,
                interval: 1,
                labelFormatter: function() {
                    return " ";
                }
            },
            axisY: {
                title: "",
                interval: Math.ceil(totalVotes / 10) // Adjust the Y-axis interval for better scaling
            },
            data: [{
                type: "bar",
                indexLabel: "{label} - {percent}%",
                indexLabelPlacement: "inside",
                indexLabelFontColor: "white",
                indexLabelFontSize: 14,
                bevelEnabled: true, // Enable bevel to make bars look better
                cornerRadius: 5, // Add rounded corners
                dataPoints: dataPoints.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / totalVotes) * 100).toFixed(2)
                }))
            }]
        });
        chart.render();
        return chart;
    }

    function updateVoteCounts() {
        $.ajax({
            url: 'update_data_csc.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Update charts dynamically
                updateChart(presidentChart, response.president);
                updateChart(vicePresidentChart, response.vicePresident);
                updateChart(secretaryChart, response.secretary);
                updateChart(treasurerChart, response.treasurer);
                updateChart(auditorChart, response.auditor);
                updateChart(proChart, response.publicInformationOfficer);
                updateChart(businessManagerChart, response.businessManager);
                updateChart(beedRepChart, response.beedRepresentative);
                updateChart(bsedRepChart, response.bsedRepresentative);
                updateChart(bshmRepChart, response.bshmRepresentative);
                updateChart(bsoadRepChart, response.bsoadRepresentative);
                updateChart(bscrimRepChart, response.bsCrimRepresentative);
                updateChart(bsitRepChart, response.bsitRepresentative);
            },
            error: function(error) {
                console.error("Error fetching data", error);
            }
        });
    }

    function updateChart(chart, dataPoints) {
        var totalVotes = dataPoints.reduce((acc, dp) => acc + dp.y, 0);
        chart.options.data[0].dataPoints = dataPoints.map(dp => ({
            ...dp,
            percent: ((dp.y / totalVotes) * 100).toFixed(2)
        }));
        chart.render();
    }

    var presidentChart = generateBarGraph([], "presidentGraph");
    var vicePresidentChart = generateBarGraph([], "vicePresidentGraph");
    var secretaryChart = generateBarGraph([], "secretaryGraph");
    var treasurerChart = generateBarGraph([], "treasurerGraph");
    var auditorChart = generateBarGraph([], "auditorGraph");
    var proChart = generateBarGraph([], "proGraph");
    var businessManagerChart = generateBarGraph([], "businessManagerGraph");
    var beedRepChart = generateBarGraph([], "beedRepGraph");
    var bsedRepChart = generateBarGraph([], "bsedRepGraph");
    var bshmRepChart = generateBarGraph([], "bshmRepGraph");
    var bsoadRepChart = generateBarGraph([], "bsoadRepGraph");
    var bscrimRepChart = generateBarGraph([], "bscrimRepGraph");
    var bsitRepChart = generateBarGraph([], "bsitRepGraph");

    updateVoteCounts();
    setInterval(updateVoteCounts, 5000);

    // Back to top button script
    $(document).ready(function() {
        var btn = $('#back-to-top');
        
        $(window).scroll(function() {
            if ($(window).scrollTop() > 100) {
                btn.fadeIn();
            } else {
                btn.fadeOut();
            }
        });
        
        btn.click(function() {
            $('html, body').animate({scrollTop: 0}, '100');
            return false;
        });
    });
</script>

<!-- Back to Top Button -->
<button id="back-to-top" title="Back to Top">&uarr;</button>

</body>
</html>
