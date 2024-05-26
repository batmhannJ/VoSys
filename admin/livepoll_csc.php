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
            top: 10px;
            left: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: calc(100% - 20px);
            padding: 0;
        }

        .candidate-image {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
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
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>President</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="presidentImage"></div>
                                <div id="presidentGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Repeat for other positions... -->

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="vicePresidentImage"></div>
                                <div id="vicePresidentGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div class="candidate-images" id="secretaryImage"></div>
                                <div id="secretaryGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div class="candidate-images" id="treasurerImage"></div>
                                <div id="treasurerGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div class="candidate-images" id="auditorImage"></div>
                                <div id="auditorGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Public Information Officer</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="proImage"></div>
                                <div id="proGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div class="candidate-images" id="businessManagerImage"></div>
                                <div id="businessManagerGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div class="candidate-images" id="beedRepImage"></div>
                                <div id="beedRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div class="candidate-images" id="bsedRepImage"></div>
                                <div id="bsedRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div class="candidate-images" id="bshmRepImage"></div>
                                <div id="bshmRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div class="candidate-images" id="bsoadRepImage"></div>
                                <div id="bsoadRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div class="candidate-images" id="bsitRepImage"></div>
                                <div id="bsitRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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

<script>
    function generateBarGraph(dataPoints, graphId, imageId) {
        var options = {
            animationEnabled: true,
            theme: "light2",
            title: {
                text: ""
            },
            axisY: {
                title: "Votes",
                includeZero: true
            },
            data: [{
                type: "column",
                dataPoints: dataPoints
            }]
        };
        var chart = new CanvasJS.Chart(graphId, options);

        var imageContainer = document.getElementById(imageId);
        imageContainer.innerHTML = ""; // Clear previous images
        dataPoints.forEach(function(dataPoint) {
            var candidateImage = document.createElement("div");
            candidateImage.className = "candidate-image";
            var img = document.createElement("img");
            img.src = dataPoint.image;
            candidateImage.appendChild(img);
            var label = document.createElement("span");
            label.className = "candidate-label";
            label.textContent = dataPoint.label;
            candidateImage.appendChild(label);
            imageContainer.appendChild(candidateImage);
        });

        chart.render();
        return chart;
    }

    function fetchVoteCounts() {
        return fetch('votes_csc_result.php')
            .then(response => response.json())
            .then(data => data);
    }

    function updateVoteCounts() {
        fetchVoteCounts().then(voteCounts => {
            // President
            var presidentData = voteCounts.president.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var presidentChart = generateBarGraph(presidentData, "presidentGraph", "presidentImage");

            // Vice President
            var vpData = voteCounts.vicePresident.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var vpChart = generateBarGraph(vpData, "vicePresidentGraph", "vicePresidentImage");

            // Secretary
            var secretaryData = voteCounts.secretary.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var secretaryChart = generateBarGraph(secretaryData, "secretaryGraph", "secretaryImage");

            // Treasurer
            var treasurerData = voteCounts.treasurer.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var treasurerChart = generateBarGraph(treasurerData, "treasurerGraph", "treasurerImage");

            // Auditor
            var auditorData = voteCounts.auditor.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var auditorChart = generateBarGraph(auditorData, "auditorGraph", "auditorImage");

            // PRO
            var proData = voteCounts.pro.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var proChart = generateBarGraph(proData, "proGraph", "proImage");

            // Business Manager
            var businessManagerData = voteCounts.businessManager.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var businessManagerChart = generateBarGraph(businessManagerData, "businessManagerGraph", "businessManagerImage");

            // BEED Representative
            var beedRepData = voteCounts.beedRep.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var beedRepChart = generateBarGraph(beedRepData, "beedRepGraph", "beedRepImage");

            // BSED Representative
            var bsedRepData = voteCounts.bsedRep.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var bsedRepChart = generateBarGraph(bsedRepData, "bsedRepGraph", "bsedRepImage");

            // BSHM Representative
            var bshmRepData = voteCounts.bshmRep.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var bshmRepChart = generateBarGraph(bshmRepData, "bshmRepGraph", "bshmRepImage");

            // BSOAD Representative
            var bsoadRepData = voteCounts.bsoadRep.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var bsoadRepChart = generateBarGraph(bsoadRepData, "bsoadRepGraph", "bsoadRepImage");

            // BSIT Representative
            var bsitRepData = voteCounts.bsitRep.map(item => ({ label: item.name, y: item.votes, image: item.photo }));
            var bsitRepChart = generateBarGraph(bsitRepData, "bsitRepGraph", "bsitRepImage");
        });
    }

    // Initial call
    updateVoteCounts();
    // Update every second
    setInterval(updateVoteCounts, 1000);
</script>
</body>
</html>
