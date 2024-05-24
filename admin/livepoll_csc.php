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

        .chart-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .candidate-image {
            margin-right: 20px;
        }

        .candidate-image img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
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
                                <div class="candidate-image" id="presidentImage"></div>
                                <div id="presidentGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="vicePresidentImage"></div>
                                <div id="vicePresidentGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Secretary</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="secretaryImage"></div>
                                <div id="secretaryGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Treasurer</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="treasurerImage"></div>
                                <div id="treasurerGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Auditor</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="auditorImage"></div>
                                <div id="auditorGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Public Information Officer (P.R.O)</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="proImage"></div>
                                <div id="proGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Business Manager</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="businessManagerImage"></div>
                                <div id="businessManagerGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BEED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="beedRepImage"></div>
                                <div id="beedRepGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="bsedRepImage"></div>
                                <div id="bsedRepGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSHM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="bshmRepImage"></div>
                                <div id="bshmRepGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSOAD Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="bsoadRepImage"></div>
                                <div id="bsoadRepGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BS CRIM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="bscrimRepImage"></div>
                                <div id="bscrimRepGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSIT Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="bsitRepImage"></div>
                                <div id="bsitRepGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
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
    function generateBarGraph(dataPoints, containerId, imageContainerId) {
        var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

        // Update the image container
        var imageContainer = document.getElementById(imageContainerId);
        imageContainer.innerHTML = dataPoints.map(dataPoint => 
            `<div><img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}"></div>`
        ).join('');

        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            animationDuration: 2000, // Animation duration for initial rendering
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
                interval: Math.ceil(totalVotes / 10) // Adjust the Y-axis interval for better scaling
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
            `<div><img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}"></div>`
        ).join('');

        chart.options.animationEnabled = true;
        chart.options.animationDuration = 2000; // Animation duration for updates
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
