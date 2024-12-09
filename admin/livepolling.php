<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/session.php'; ?>
    <?php include 'includes/header.php'; ?>

    <style>
        .chart-container {
            margin: 20px auto;
            width: 90%;
            max-width: 1200px;
            height: 500px;
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
    </style>

    <!-- amCharts -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
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
                <form id="organization-form" style="text-align: center; margin-bottom: 20px;">
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

                <div class="chart-container" id="chartdiv"></div>
                <button id="back-to-top" title="Back to top">&uarr;</button>
            </section>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>

    <?php include 'includes/scripts.php'; ?>

    <script>
        function generateChart(dataPoints) {
            // Dispose any previous chart
            am5.array.each(am5.registry.rootElements, function (root) {
                root.dispose();
            });

            // Create root element
            var root = am5.Root.new("chartdiv");

            // Set theme
            root.setThemes([am5themes_Animated.new(root)]);

            // Create chart
            var chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: true,
                    panY: true,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    pinchZoomX: true
                })
            );

            // Add X-axis
            var xAxis = chart.xAxes.push(
                am5xy.CategoryAxis.new(root, {
                    categoryField: "label",
                    renderer: am5xy.AxisRendererX.new(root, {})
                })
            );

            // Add Y-axis
            var yAxis = chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {})
                })
            );

            // Add series for bars
            var series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "votes",
                    categoryXField: "label",
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "{label}: {valueY}"
                    })
                })
            );

            // Add moving bullets to bars
            series.bullets.push(function () {
                return am5.Bullet.new(root, {
                    sprite: am5.Circle.new(root, {
                        radius: 5,
                        fill: series.get("fill")
                    }),
                    locationY: 1 // Bullet location at the top of the bar
                });
            });

            // Add data
            xAxis.data.setAll(dataPoints);
            series.data.setAll(dataPoints);

            // Animate bars and bullets
            series.appear(1000, 100);

            // Add cursor (for interactivity)
            chart.set("cursor", am5xy.XYCursor.new(root, {}));

            // Animate the bars with new data periodically (simulate real-time updates)
            setInterval(function () {
                dataPoints.forEach(function (dataPoint) {
                    dataPoint.votes += Math.floor(Math.random() * 10); // Randomly increase votes
                });
                xAxis.data.setAll(dataPoints);
                series.data.setAll(dataPoints);
            }, 2000);
        }

        function fetchDataAndGenerateChart() {
            // Mock data for testing (Replace this with actual AJAX call)
            const mockData = [
                { label: "Candidate A", votes: 50 },
                { label: "Candidate B", votes: 30 },
                { label: "Candidate C", votes: 20 }
            ];

            generateChart(mockData);
        }

        document.addEventListener("DOMContentLoaded", function () {
            fetchDataAndGenerateChart();

            // On form submit (to change organization or chart type)
            document.getElementById("organization-form").addEventListener("submit", function (event) {
                event.preventDefault();
                fetchDataAndGenerateChart(); // Fetch and regenerate chart
            });

            // Scroll-to-top button functionality
            const backToTopButton = document.getElementById("back-to-top");
            window.addEventListener("scroll", function () {
                if (window.scrollY > 100) {
                    backToTopButton.style.display = "block";
                } else {
                    backToTopButton.style.display = "none";
                }
            });

            backToTopButton.addEventListener("click", function () {
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
        });
    </script>
</body>

</html>
