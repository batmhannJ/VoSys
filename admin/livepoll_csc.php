<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>
<head>
    <!-- Add the style block to center the box titles and style the back to top button -->
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
                <!-- Repeat this structure for each position -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>President</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="presidentGraph" style="height: 300px; position: relative;"></div>
                        </div>
                    </div>
                </div>
                <!-- Add other positions similarly... -->
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
            animationDuration: 2000,
            title: {
                text: "Vote Counts"
            },
            axisX: {
                title: "",
                includeZero: true,
                interval: 1,
                labelFormatter: function () {
                    return " ";
                }
            },
            axisY: {
                title: "",
                interval: Math.ceil(totalVotes / 10)
            },
            data: [{
                type: "bar",
                indexLabel: "{label} - {percent}%",
                indexLabelPlacement: "inside",
                indexLabelFontColor: "white",
                indexLabelFontSize: 14,
                dataPoints: dataPoints.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / totalVotes) * 100).toFixed(2)
                }))
            }]
        });

        chart.render();

        // Add images next to bars
        addImagesToBars(dataPoints, containerId);
        
        return chart;
    }

    function addImagesToBars(dataPoints, containerId) {
        var container = document.getElementById(containerId);
        container.querySelectorAll('.graph-image').forEach(img => img.remove()); // Remove old images

        dataPoints.forEach((dataPoint, index) => {
            var img = document.createElement('img');
            img.src = dataPoint.image; // Assume dataPoint contains image URL
            img.style.position = 'absolute';
            img.style.top = (index * 30 + 70) + 'px'; // Adjust as needed
            img.style.left = '5px'; // Adjust as needed
            img.style.width = '30px';
            img.style.height = '30px';
            img.className = 'graph-image';
            container.appendChild(img);
        });
    }

    function updateChartData(chart, newDataPoints, containerId) {
        var totalVotes = newDataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);
        chart.options.data[0].dataPoints = newDataPoints.map(dataPoint => ({
            ...dataPoint,
            percent: ((dataPoint.y / totalVotes) * 100).toFixed(2)
        }));
        chart.options.animationEnabled = true;
        chart.options.animationDuration = 2000;
        chart.render();

        // Add images next to bars
        addImagesToBars(newDataPoints, containerId);
    }

    function updateVoteCounts() {
        $.ajax({
            url: 'update_data_csc.php',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                updateChartData(presidentChart, response.president, 'presidentGraph');
                updateChartData(vicePresidentChart, response.vicePresident, 'vicePresidentGraph');
                updateChartData(secretaryChart, response.secretary, 'secretaryGraph');
                updateChartData(treasurerChart, response.treasurer, 'treasurerGraph');
                updateChartData(auditorChart, response.auditor, 'auditorGraph');
                updateChartData(proChart, response.publicInformationOfficer, 'proGraph');
                updateChartData(businessManagerChart, response.businessManager, 'businessManagerGraph');
                updateChartData(beedRepChart, response.beedRepresentative, 'beedRepGraph');
                updateChartData(bsedRepChart, response.bsedRepresentative, 'bsedRepGraph');
                updateChartData(bshmRepChart, response.bshmRepresentative, 'bshmRepGraph');
                updateChartData(bsoadRepChart, response.bsoadRepresentative, 'bsoadRepGraph');
                updateChartData(bscrimRepChart, response.bsCrimRepresentative, 'bscrimRepGraph');
                updateChartData(bsitRepChart, response.bsitRepresentative, 'bsitRepGraph');
            },
            error: function (error) {
                console.error("Error fetching data", error);
            }
        });
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
    $(document).ready(function () {
        var btn = $('#back-to-top');

        $(window).scroll(function () {
            if ($(window).scrollTop() > 100) {
                btn.fadeIn();
            } else {
                btn.fadeOut();
            }
        });

        btn.click(function () {
            $('html, body').animate({ scrollTop: 0 }, '100');
            return false;
        });
    });
</script>

<!-- Back to Top Button -->
<button id="back-to-top" title="Back to Top">&uarr;</button>

</body>
</html>
