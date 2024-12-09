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
            flex-direction: row-reverse; /* Flip the order of elements */
        }

        .candidate-images {
            margin-left: 10px; /* Add space between images and graph */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .candidate-image img {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .chart-container > div {
            flex-grow: 1; /* Make the graph section take up available space */
            margin-left: 1000px;
        }

        @media (max-width: 1000px) {
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
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
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

    <script src="path/to/jquery.min.js"></script>
    <script>
        function generateGraph(dataPoints, containerId, imageContainerId, graphType) {
            var chart = am4core.create(containerId, am4charts.XYChart);

            chart.paddingTop = 20;
            chart.paddingRight = 40;
            chart.paddingBottom = 40;

            chart.data = dataPoints.map(dataPoint => ({
                category: dataPoint.label,
                value: dataPoint.y,
                image: dataPoint.image
            }));

            var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "category";
            categoryAxis.renderer.inversed = true; // Reverse category axis for horizontal bar
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;

            var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minGridDistance = 50;

            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueX = "value";
            series.dataFields.categoryY = "category";
            series.name = "Votes";
            series.columns.template.tooltipText = "{categoryY}: [bold]{valueX}[/] votes";
            series.columns.template.fillOpacity = 0.8;

            series.columns.template.width = am4core.percent(80); // Adjust width of the bars
            var bullet = series.bullets.push(new am4charts.Bullet());
            var image = bullet.createChild(am4core.Image);
            image.horizontalCenter = "middle";
            image.verticalCenter = "bottom";
            image.dy = 30;
            image.propertyFields.href = "image";

            series.columns.template.column.cornerRadiusTopLeft = 10;
            series.columns.template.column.cornerRadiusTopRight = 10;

            categoryAxis.renderer.cellStartLocation = 0.1;
            categoryAxis.renderer.cellEndLocation = 0.9;
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
                            'businessManager': 'Business Manager',
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
                                                <div id='${category}Graph' style='height: 300px;'></div>
                                                <div class='candidate-images' id='${category}Image'></div>
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

            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('#back-to-top').fadeIn();
                } else {
                    $('#back-to-top').fadeOut();
                }
            });

            $('#back-to-top').click(function () {
                $('html, body').animate({ scrollTop: 0 }, 600);
                return false;
            });
        });
    </script>
</body>
</html>
