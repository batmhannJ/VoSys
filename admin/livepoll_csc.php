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
        }

        .candidate-images {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-right: 20px;
        }

        .candidate-image img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 10px;
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
                <!-- President -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>President</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="presidentImage"></div>
                                <div id="presidentGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vice President -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="vicePresidentImage"></div>
                                <div id="vicePresidentGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secretary -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Secretary</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="secretaryImage"></div>
                                <div id="secretaryGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Treasurer -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Treasurer</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="treasurerImage"></div>
                                <div id="treasurerGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Auditor -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Auditor</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="auditorImage"></div>
                                <div id="auditorGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Public Information Officer -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Public Information Officer</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="proImage"></div>
                                <div id="proGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Manager -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Business Manager</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="businessManagerImage"></div>
                                <div id="businessManagerGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BEED Representative -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BEED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="beedRepImage"></div>
                                <div id="beedRepGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BSED Representative -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bsedRepImage"></div>
                                <div id="bsedRepGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BSHM Representative -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSHM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bshmRepImage"></div>
                                <div id="bshmRepGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BSOAD Representative -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSOAD Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bsoadRepImage"></div>
                                <div id="bsoadRepGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BS Crim Representative -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BS Crim Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bscrimRepImage"></div>
                                <div id="bscrimRepGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BSIT Representative -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSIT Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bsitRepImage"></div>
                                <div id="bsitRepGraph" style="height: 300px; width: calc(100% - 90px);"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    function generateBarGraph(dataPoints, containerId, imageContainerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            theme: "light2",
            axisY: {
                title: "Votes",
                interval: 10,
            },
            data: [{
                type: "column",
                dataPoints: dataPoints
            }]
        });
        chart.render();
        return chart;
    }

    function updateChartData(chart, data, imageContainerId) {
        chart.options.data[0].dataPoints = data.map(function (item) {
            return {
                label: item.name,
                y: item.votes
            };
        });
        chart.render();

        var imageContainer = $('#' + imageContainerId);
        imageContainer.empty();
        data.forEach(function (item) {
            var imageElement = $('<div class="candidate-image"><img src="' + item.photo + '" alt="' + item.name + '"></div>');
            imageContainer.append(imageElement);
        });
    }

    function updateVoteCounts() {
        $.ajax({
            url: 'get_votes.php',
            dataType: 'json',
            success: function (response) {
                updateChartData(presidentChart, response.president, "presidentImage");
                updateChartData(vicePresidentChart, response.vicePresident, "vicePresidentImage");
                updateChartData(secretaryChart, response.secretary, "secretaryImage");
                updateChartData(treasurerChart, response.treasurer, "treasurerImage");
                updateChartData(auditorChart, response.auditor, "auditorImage");
                updateChartData(proChart, response.pro, "proImage");
                updateChartData(businessManagerChart, response.businessManager, "businessManagerImage");
                updateChartData(beedRepChart, response.beedRep, "beedRepImage");
                updateChartData(bsedRepChart, response.bsedRep, "bsedRepImage");
                updateChartData(bshmRepChart, response.bshmRep, "bshmRepImage");
                updateChartData(bsoadRepChart, response.bsoadRep, "bsoadRepImage");
                updateChartData(bscrimRepChart, response.bscrimRep, "bscrimRepImage");
                updateChartData(bsitRepChart, response.bsitRep, "bsitRepImage");
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

    $(document).ready(function () {
        updateVoteCounts();
        setInterval(updateVoteCounts, 5000);

        var backToTop = $('#back-to-top');
        $(window).scroll(function () {
            if ($(window).scrollTop() > 100) {
                backToTop.fadeIn();
            } else {
                backToTop.fadeOut();
            }
        });
        backToTop.click(function () {
            $('html, body').animate({scrollTop: 0}, 600);
            return false;
        });
    });
</script>
</body>
</html>
