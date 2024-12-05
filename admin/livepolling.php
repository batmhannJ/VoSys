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
        #chart-type-select {
            margin-left: 10px;
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
                <!-- Organization Selection Form -->
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

                    <label for="chart-type-select">Select Chart Type:</label>
                    <select id="chart-type-select" name="chartType">
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                        <option value="line">Line Chart</option>
                    </select>

                    <button type="submit">Show Results</button>
                </form>
                <br>

                <div class="row justify-content-center" id="results-container">
                    <!-- Results will be dynamically inserted here -->
                </div>
            </section>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="path/to/jquery.min.js"></script>
    <script>
        function generateChart(dataPoints, containerId, chartType) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            var chartOptions = {
                animationEnabled: true,
                animationDuration: 2000,
                title: { text: "Election Results" },
                axisY: { title: "Votes", interval: Math.ceil(totalVotes / 10) },
                data: []
            };

            switch (chartType) {
                case "bar":
                    chartOptions.data.push({
                        type: "bar",
                        dataPoints: dataPoints
                    });
                    break;
                case "pie":
                    chartOptions.data.push({
                        type: "pie",
                        showInLegend: true,
                        legendText: "{label}",
                        dataPoints: dataPoints
                    });
                    break;
                case "line":
                    chartOptions.data.push({
                        type: "line",
                        dataPoints: dataPoints
                    });
                    break;
                default:
                    chartOptions.data.push({
                        type: "bar",
                        dataPoints: dataPoints
                    });
            }

            var chart = new CanvasJS.Chart(containerId, chartOptions);
            chart.render();
        }

        function fetchAndGenerateGraphs(organization, chartType) {
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
                            // ... other positions
                        },
                        'jpcs': {
                            'jpcsPresident': 'President',
                            'jpcsVicePresident': 'Vice President',
                            // ... other positions
                        }
                        // ... other organizations
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
                                            <div id='${category}Graph' style='height: 300px; width: 100%;'></div>
                                        </div>
                                    </div>
                                </div>`;
                            $('#results-container').append(containerHtml);
                            generateChart(response[category], `${category}Graph`, chartType);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", status, error);
                }
            });
        }

        $(document).ready(function () {
            fetchAndGenerateGraphs('csc', 'bar');

            $('#organization-form').submit(function (event) {
                event.preventDefault();
                const selectedOrganization = $('#organization-select').val();
                const selectedChartType = $('#chart-type-select').val();
                fetchAndGenerateGraphs(selectedOrganization, selectedChartType);
            });
        });
    </script>
</body>
</html>
