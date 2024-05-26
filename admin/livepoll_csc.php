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
            position: relative;
            margin-bottom: 40px;
        }

        .candidate-images {
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            padding: 10px;
        }

        .candidate-image {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .candidate-image img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .candidate-label {
            margin-left: 10px;
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
                <?php
                $positions = ['President', 'Vice President', 'Secretary', 'Treasurer', 'Auditor', 'Public Information Officer (P.R.O)', 'Business Manager', 'BEED Representative', 'BSED Representative', 'BSHM Representative', 'BSOAD Representative', 'BSCRIM Representative', 'BSIT Representative'];
                foreach ($positions as $position) {
                    $slug = strtolower(str_replace([' ', '(', ')', '.'], '', $position));
                    echo '<div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>' . $position . '</b></h3>
                            </div>
                            <div class="box-body">
                                <div class="chart-container">
                                    <div class="candidate-images" id="' . $slug . 'Image"></div>
                                    <div id="' . $slug . 'Graph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>

    <button id="back-to-top" title="Back to Top"><i class="fa fa-chevron-up"></i></button>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="path/to/jquery.min.js"></script>

    <script>
        function generateBarGraph(dataPoints, containerId, imageContainerId) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            // Update the image container
            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = dataPoints.map(dataPoint =>
                `<div class="candidate-image">
                    <img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">
                    <span class="candidate-label">${dataPoint.label}</span>
                </div>`
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
                `<div class="candidate-image">
                    <img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">
                    <span class="candidate-label">${dataPoint.label}</span>
                </div>`
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

        updateVoteCounts();
        setInterval(updateVoteCounts, 5000);
    </script>
</div>
</body>
</html>
