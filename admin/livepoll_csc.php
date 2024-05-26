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

        .chart-container {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            position: relative;
        }

        .candidate-images {
            display: flex;
            flex-direction: column;
            margin-right: 20px;
            align-items: center;
        }

        .candidate-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 10px;
        }

        .candidate-image img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .chart {
            width: 100%;
            height: 300px;
        }

        @media (max-width: 768px) {
            .candidate-image img {
                width: 75px;
                height: 75px;
            }
        }

        @media (max-width: 480px) {
            .candidate-image img {
                width: 100px;
                height: 100px;
            }
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
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>President</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="presidentImage"></div>
                                <div id="presidentGraph" class="chart"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Repeat this structure for other positions -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="vicePresidentImage"></div>
                                <div id="vicePresidentGraph" class="chart"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add other positions similarly -->
                
            </div>
        </section>

        <!-- Back to Top Button -->
        <button id="back-to-top"><i class="fa fa-chevron-up"></i></button>
    </div>

    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    function generateBarGraph(dataPoints, containerId, imageContainerId) {
        var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

        // Update the image container
        var imageContainer = document.getElementById(imageContainerId);
        imageContainer.innerHTML = dataPoints.map(dataPoint =>
            `<div class="candidate-image"><img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}"><span>${dataPoint.label}</span></div>`
        ).join('');

        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            animationDuration: 3000,
            animationEasing: "easeInOutBounce",
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

        // Adjust the chart size when the window is resized
        window.addEventListener('resize', function () {
            chart.options.width = document.getElementById(containerId).offsetWidth;
            chart.render();
        });

        return chart;
    }

    function updateChartData(chart, newDataPoints, imageContainerId) {
        var totalVotes = newDataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);
        chart.options.data[0].dataPoints = newDataPoints.map(dataPoint => ({
            ...dataPoint,
            percent: ((dataPoint.y / totalVotes) * 100).toFixed(2)
        }));

        // Update the image container
        var imageContainer = document.getElementById(imageContainerId);
        imageContainer.innerHTML = newDataPoints.map(dataPoint =>
            `<div class="candidate-image"><img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}"><span>${dataPoint.label}</span></div>`
        ).join('');

        chart.options.animationEnabled = true;
        chart.options.animationDuration = 2000;
        chart.options.animationEasing = "easeInOutBounce";
        chart.render();
    }

    function updateVoteCounts() {
        $.ajax({
            url: 'update_data_csc.php',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                updateChartData(presidentChart, response.president, 'presidentImage');
                updateChartData(vicePresidentChart, response.vicePresident, 'vicePresidentImage');
                updateChartData(secretaryChart, response.secretary, 'secretaryImage');
                updateChartData(treasurerChart, response.treasurer, 'treasurerImage');
                updateChartData(auditorChart, response.auditor, 'auditorImage');
                updateChartData(proChart, response.publicInformationOfficer, 'proImage');
                updateChartData(businessManagerChart, response.businessManager, 'businessManagerImage');
                updateChartData(beedRepChart, response.beedRepresentative, 'beedRepImage');
                updateChartData(bsedRepChart, response.bsedRepresentative, 'bsedRepImage');
                updateChartData(bshmRepChart, response.bshmRepresentative, 'bshmRepImage');
                updateChartData(bsoadRepChart, response.bsoadRepresentative, 'bsoadRepImage');
                updateChartData(bscrimRepChart, response.bsCrimRepresentative, 'bscrimRepImage');
                updateChartData(bsitRepChart, response.bsitRepresentative, 'bsitRepImage');
            },
            error: function (error) {
                console.error("Error fetching data", error);
            }
        });
    }

    var presidentChart = generateBarGraph([], "presidentGraph", "presidentImage");
    var vicePresidentChart = generateBarGraph([], "vicePresidentGraph", "vicePresidentImage");
    var secretaryChart = generateBarGraph([], "secretaryGraph", "secretaryImage");
    var treasurerChart = generateBarGraph([], "treasurerGraph", "treasurerImage");
    var auditorChart = generateBarGraph([], "auditorGraph", "auditorImage");
    var proChart = generateBarGraph([], "proGraph", "proImage");
    var businessManagerChart = generateBarGraph([], "businessManagerGraph", "businessManagerImage");
    var beedRepChart = generateBarGraph([], "beedRepGraph", "beedRepImage");
    var bsedRepChart = generateBarGraph([], "bsedRepGraph", "bsedRepImage");
    var bshmRepChart = generateBarGraph([], "bshmRepGraph", "bshmRepImage");
    var bsoadRepChart = generateBarGraph([], "bsoadRepGraph", "bsoadRepImage");
    var bscrimRepChart = generateBarGraph([], "bscrimRepGraph", "bscrimRepImage");
    var bsitRepChart = generateBarGraph([], "bsitRepGraph", "bsitRepImage");

    // Initial load of the vote counts
    updateVoteCounts();

    // Refresh the vote counts every 5 seconds
    setInterval(updateVoteCounts, 5000);

    // Back to Top button functionality
    var backToTopButton = document.getElementById("back-to-top");

    window.onscroll = function () {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            backToTopButton.style.display = "block";
        } else {
            backToTopButton.style.display = "none";
        }
    };

    backToTopButton.onclick = function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
</script>
</body>
</html>
