<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/session.php'; ?>
    <?php include 'includes/header.php'; ?>
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
            position: relative;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
        }

        .candidate-images {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-right: 10px;
        }

        .candidate-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 10px;
        }

        .candidate-image img {
            width: 60px;
            height: 60px;
            margin-right: -10px;
            margin-bottom: 25px;
            margin-top: 35px;
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
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Election Results</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Results</li>
                </ol>
            </section>

            <section class="content">
                <form id="organization-form">
                    <label for="organization-select">Select Organization:</label>
                    <select id="organization-select" name="organization">
                        <option value="csc">CSC</option>
                        <option value="jpcs">JPCS</option>
                        <option value="ymf">YMF</option>
                        <option value="pasoa">PASOA</option>
                        <option value="code-tg">CODE-TG</option>
                        <option value="hmso">HMSO</option>
                    </select>

                    <label for="graph-type">Select Graph Type:</label>
                    <select id="graph-type">
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                        <option value="line">Line Chart</option>
                    </select>

                    <button type="submit">Show Results</button>
                </form>
                <br>

                <div class="row justify-content-center" id="results-container"></div>
            </section>

            <button id="back-to-top" title="Back to top">&uarr;</button>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
    
    <!-- amCharts -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    
    <script src="path/to/jquery.min.js"></script>
    <script>
        function generateGraph(dataPoints, containerId, imageContainerId, graphType) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = '';
            dataPoints.forEach(dataPoint => {
                var candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-image';
                candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
                imageContainer.appendChild(candidateDiv);
            });

            am4core.useTheme(am4themes_animated);

            if (graphType === 'pie') {
                var chart = am4core.create(containerId, am4charts.PieChart);
                chart.data = dataPoints.map((dataPoint) => ({
                    category: dataPoint.label,
                    value: dataPoint.y,
                    percentage: ((dataPoint.y / totalVotes) * 100).toFixed(2)
                }));

                var series = chart.series.push(new am4charts.PieSeries());
                series.dataFields.value = "value";
                series.dataFields.category = "category";
                series.slices.template.tooltipText = "{category}: [bold]{value} votes ({percentage}%)";
                series.slices.template.fill = am4core.color("#FF0000");

                series.slices.template.events.on("hit", function(event) {
                    event.target.fill = am4core.color("#0000FF");
                });
            } else if (graphType === 'line') {
                var chart = am4core.create(containerId, am4charts.XYChart);
                chart.data = dataPoints.map((dataPoint) => ({
                    category: dataPoint.label,
                    value: dataPoint.y,
                    percentage: ((dataPoint.y / totalVotes) * 100).toFixed(2)
                }));

                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.grid.template.location = 0;

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

                var series = chart.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "value";
                series.dataFields.categoryX = "category";
                series.name = "Votes";
                series.strokeWidth = 3;
                series.tensionX = 0.8;
                series.tooltipText = "{category}: [bold]{valueY} votes ({percentage}%)";

                var bullet = series.bullets.push(new am4charts.CircleBullet());
                bullet.circle.fill = am4core.color("#fff");
                bullet.circle.strokeWidth = 2;
                bullet.circle.stroke = am4core.color("#FF0000");
                bullet.circle.radius = 5;

                chart.cursor = new am4charts.XYCursor();
                chart.cursor.snapToSeries = series;
            } else {
                var chart = am4core.create(containerId, am4charts.XYChart);
                chart.data = dataPoints.map((dataPoint, index) => ({
                    category: dataPoint.label,
                    value: dataPoint.y,
                    percentage: ((dataPoint.y / totalVotes) * 100).toFixed(2),
                    color: index % 2 === 0 ? am4core.color("#FF0000") : am4core.color("#0000FF")
                }));

                var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.grid.template.location = 0;

                var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());

                var series = chart.series.push(new am4charts.ColumnSeries());
                series.dataFields.valueX = "value";
                series.dataFields.categoryY = "category";
                series.columns.template.propertyFields.fill = "color";
                series.columns.template.tooltipText = "{category}: [bold]{valueX} votes ({percentage}%)[/]";

                // Control Bar Width (narrower bars)
                series.columns.template.width = am4core.percent(20); // Adjust the percentage for narrower bars

                // Add candidate names and percentages inside the bars (centered)
                var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                labelBullet.label.text = "{category}: {percentage}% ({valueX} votes)";
                labelBullet.label.fill = am4core.color("#fff");
                labelBullet.label.fontSize = 12;  // Adjust font size as needed
                labelBullet.label.horizontalCenter = "middle";  // Center the text horizontally
                labelBullet.label.verticalCenter = "middle";    // Center the text vertically
                labelBullet.label.padding(50, 0, 0, 0);           // Optional: Remove extra padding

                chart.cursor = new am4charts.XYCursor();
                chart.cursor.snapToSeries = series;
            }
        }

        function fetchAndGenerateGraphs(organization) {
            const graphType = $('#graph-type').val();

            $.ajax({
                url: 'update_data.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    $('#results-container').empty();

                    var categories = {
                        'csc': {
                            'president': 'President',
                            'vice president': 'Vice President',
                            'secretary': 'Secretary',
                            'treasurer': 'Treasurer',
                            'auditor': 'Auditor',
                            'p.r.o': 'P.R.O',
                            'businessManager': 'Business Manager'
                        }
                    };

                    var selectedCategories = categories[organization];

                    Object.keys(selectedCategories).forEach(function (category) {
                        if (response[category]) {
                            var containerHtml = ` 
                                <div class='col-md-12'>
                                    <div class='box'>
                                        <div class='box-header with-border'>
                                            <h3 class='box-title'><b>${selectedCategories[category]}</b></h3>
                                        </div>
                                        <div class='box-body'>
                                            <div class='chart-container'>
                                                <div class='candidate-images' id='${category}Image'></div>
                                                <div id='${category}Graph' style='height: 300px; width: 1000px;'></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            $('#results-container').append(containerHtml);
                            generateGraph(response[category], category + 'Graph', category + 'Image', graphType);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", status, error);
                }
            });
        }

        $(document).ready(function () {
            fetchAndGenerateGraphs('csc');

            $('#organization-form').submit(function (event) {
                event.preventDefault();
                fetchAndGenerateGraphs($('#organization-select').val());
            });

            $('#graph-type').change(function () {
                fetchAndGenerateGraphs($('#organization-select').val());
            });

            $('#back-to-top').click(function () {
                $('html, body').animate({ scrollTop: 0 }, 600);
            });
        });
    </script>
</body>
</html>
