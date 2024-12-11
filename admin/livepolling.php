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
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="path/to/jquery.min.js"></script>

    <script>
        function generateGraph(dataPoints, containerId, imageContainerId, graphType) {
            if (graphType === 'line') {
                // Create the chart container
                document.getElementById(containerId).innerHTML = '';
                
                am5.ready(function() {
                    var root = am5.Root.new(containerId);
                    root.setThemes([am5themes_Animated.new(root)]);

                    var chart = root.container.children.push(am5xy.XYChart.new(root, {
                        layout: root.verticalLayout
                    }));

                    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                        categoryField: "category",
                        renderer: am5xy.AxisRendererX.new(root, {})
                    }));

                    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                        renderer: am5xy.AxisRendererY.new(root, {})
                    }));

                    var series = chart.series.push(am5xy.LineSeries.new(root, {
                        name: "Votes",
                        xAxis: xAxis,
                        yAxis: yAxis,
                        valueYField: "value",
                        categoryXField: "category",
                        stroke: am5.color("#4F81BC")
                    }));

                    series.bullets.push(function() {
                        return am5.Bullet.new(root, {
                            sprite: am5.Circle.new(root, {
                                radius: 5,
                                fill: am5.color("#4F81BC")
                            })
                        });
                    });

                    series.data.setAll(dataPoints.map(dataPoint => ({
                        category: dataPoint.label,
                        value: dataPoint.y,
                        percent: ((dataPoint.y / dataPoints.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2) + '%'
                    })));

                    series.bullets.push(function(root, series, dataItem) {
                        return am5.Label.new(root, {
                            text: "{category}: {value} ({percent})",
                            fill: am5.color("#000"), // Black label
                            centerY: am5.p50,
                            centerX: am5.p50
                        });
                    });
                });
                return;
            }

            // Original logic for Bar and Pie charts
        }

        function fetchAndGenerateGraphs(organization) {
            const graphType = $('#graph-type').val();

            $.ajax({
                url: 'update_data.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#results-container').empty();
                    var categories = {
                        'csc': {
                            'president': 'President',
                            'vice president': 'Vice President',
                            'secretary': 'Secretary',
                            'treasurer': 'Treasurer',
                            'auditor': 'Auditor',
                            'p.r.o': 'P.R.O',
                            'businessManager': 'Business Manager',
                        }
                    };

                    var selectedCategories = categories[organization];
                    Object.keys(selectedCategories).forEach(function(category) {
                        if (response[category]) {
                            var containerHtml = `
                                <div class='col-md-12'>
                                    <div class='box'>
                                        <div class='box-header with-border'>
                                            <h3 class='box-title'><b>${selectedCategories[category]}</b></h3>
                                        </div>
                                        <div class='box-body'>
                                            <div class='chart-container'>
                                                <div id='${category}Graph' style='height: 300px;'></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            $('#results-container').append(containerHtml);
                            generateGraph(response[category], category + 'Graph', null, graphType);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data: ", status, error);
                }
            });
        }

        $(document).ready(function() {
            fetchAndGenerateGraphs('csc');

            $('#organization-form').submit(function(event) {
                event.preventDefault();
                fetchAndGenerateGraphs($('#organization-select').val());
            });

            $('#graph-type').change(function() {
                fetchAndGenerateGraphs($('#organization-select').val());
            });
        });
    </script>
</body>
</html>
