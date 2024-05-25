<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>
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
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .candidate-image {
            margin-right: 20px;
        }

        .candidate-image img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .chart-container {
                flex-direction: column;
                align-items: center;
            }

            .candidate-image {
                margin-bottom: 20px;
                margin-right: 0;
            }

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
<body>
    <div class="chart-container">
        <div id="presidentImage" class="candidate-image"></div>
        <div id="presidentGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="vicePresidentImage" class="candidate-image"></div>
        <div id="vicePresidentGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="secretaryImage" class="candidate-image"></div>
        <div id="secretaryGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="treasurerImage" class="candidate-image"></div>
        <div id="treasurerGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="auditorImage" class="candidate-image"></div>
        <div id="auditorGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="proImage" class="candidate-image"></div>
        <div id="proGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="businessManagerImage" class="candidate-image"></div>
        <div id="businessManagerGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="beedRepImage" class="candidate-image"></div>
        <div id="beedRepGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="bsedRepImage" class="candidate-image"></div>
        <div id="bsedRepGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="bshmRepImage" class="candidate-image"></div>
        <div id="bshmRepGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="bsoadRepImage" class="candidate-image"></div>
        <div id="bsoadRepGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="bscrimRepImage" class="candidate-image"></div>
        <div id="bscrimRepGraph" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="chart-container">
        <div id="bsitRepImage" class="candidate-image"></div>
        <div id="bsitRepGraph" style="height: 300px; width: 100%;"></div>
    </div>

    <button id="back-to-top">↑</button>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function generateBarGraph(dataPoints, containerId, imageContainerId) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = dataPoints.map(dataPoint =>
                `<div><img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}"></div>`
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
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "white",
                    indexLabelFontSize: 14,
                    dataPoints: dataPoints.map(dataPoint => ({
                        y: dataPoint.y,
                        label: `${dataPoint.label} - ${((dataPoint.y / totalVotes) * 100).toFixed(2)}%`,
                        toolTipContent: `<img src='${dataPoint.image}' style='width:20px; height:20px; border-radius:50%; vertical-align:middle; margin-right: 5px;'> ${dataPoint.label} - ${((dataPoint.y / totalVotes) * 100).toFixed(2)}%`
                    }))
                }]
            });

            chart.render();

            window.addEventListener('resize', function () {
                chart.options.width = document.getElementById(containerId).offsetWidth;
                chart.render();
            });

            return chart;
        }

        function updateChartData(chart, newDataPoints, imageContainerId) {
            var totalVotes = newDataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);
            chart.options.data[0].dataPoints = newDataPoints.map(dataPoint => ({
                y: dataPoint.y,
                label: `${dataPoint.label} - ${((dataPoint.y / totalVotes) * 100).toFixed(2)}%`,
                toolTipContent: `<img src='${dataPoint.image}' style='width:20px; height:20px; border-radius:50%; vertical-align:middle; margin-right: 5px;'> ${dataPoint.label} - ${((dataPoint.y / totalVotes) * 100).toFixed(2)}%`
            }));

            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = newDataPoints.map(dataPoint =>
                `<div><img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}"></div>`
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
                    console.error(error.responseText);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            $.ajax({
                url: 'fetch_votes.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    presidentChart = generateBarGraph(response.president, 'presidentGraph', 'presidentImage');
                    vicePresidentChart = generateBarGraph(response.vicePresident, 'vicePresidentGraph', 'vicePresidentImage');
                    secretaryChart = generateBarGraph(response.secretary, 'secretaryGraph', 'secretaryImage');
                    treasurerChart = generateBarGraph(response.treasurer, 'treasurerGraph', 'treasurerImage');
                    auditorChart = generateBarGraph(response.auditor, 'auditorGraph', 'auditorImage');
                    proChart = generateBarGraph(response.publicInformationOfficer, 'proGraph', 'proImage');
                    businessManagerChart = generateBarGraph(response.businessManager, 'businessManagerGraph', 'businessManagerImage');
                    beedRepChart = generateBarGraph(response.beedRepresentative, 'beedRepGraph', 'beedRepImage');
                    bsedRepChart = generateBarGraph(response.bsedRepresentative, 'bsedRepGraph', 'bsedRepImage');
                    bshmRepChart = generateBarGraph(response.bshmRepresentative, 'bshmRepGraph', 'bshmRepImage');
                    bsoadRepChart = generateBarGraph(response.bsoadRepresentative, 'bsoadRepGraph', 'bsoadRepImage');
                    bscrimRepChart = generateBarGraph(response.bsCrimRepresentative, 'bscrimRepGraph', 'bscrimRepImage');
                    bsitRepChart = generateBarGraph(response.bsitRepresentative, 'bsitRepGraph', 'bsitRepImage');

                    setInterval(updateVoteCounts, 30000);
                },
                error: function (error) {
                    console.error(error.responseText);
                }
            });

            var backToTopButton = document.getElementById('back-to-top');
            window.addEventListener('scroll', function () {
                if (window.pageYOffset > 100) {
                    backToTopButton.style.display = 'block';
                } else {
                    backToTopButton.style.display = 'none';
                }
            });

            backToTopButton.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>
