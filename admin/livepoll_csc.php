<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/session.php'; ?>
    <?php include 'includes/header_csc.php'; ?>
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

            <button id="back-to-top" title="Back to top">&uarr;</button>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="path/to/jquery.min.js"></script>
    <script>
    // Store previous response to compare changes
    let previousResponse = null;

    function generateBarGraph(dataPoints, containerId, imageContainerId) {
        var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            animationDuration: 2000,
            title: {
                text: "Vote Counts"
            },
            axisX: {
                interval: 1,
                labelFormatter: function () {
                    return " ";
                }
            },
            axisY: {
                interval: Math.ceil(totalVotes / 10)
            },
            data: [{
                type: "bar",
                color: "#4F81BC", // Custom color
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
    }

    function generateLineGraph(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title: { text: "Vote Trend" },
            axisX: { labelFontSize: 12 },
            axisY: { labelFontSize: 12 },
            data: [{
                type: "line",
                lineColor: "#1589FF",
                dataPoints: dataPoints.map((dataPoint, index) => ({
                    x: index + 1,
                    y: dataPoint.y,
                    label: dataPoint.label
                }))
            }]
        });
        chart.render();
    }

    function generatePieChart(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title: { text: "Vote Distribution" },
            data: [{
                type: "pie",
                indexLabel: "{label} - {percent}%",
                indexLabelFontSize: 14,
                dataPoints: dataPoints.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / dataPoints.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }))
            }]
        });
        chart.render();
    }

    function generateDoughnutChart(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title: { text: "Vote Composition" },
            data: [{
                type: "doughnut",
                innerRadius: 70,
                indexLabel: "{label} - {percent}%",
                indexLabelFontSize: 14,
                dataPoints: dataPoints.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / dataPoints.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }))
            }]
        });
        chart.render();
    }

    function fetchAndGenerateGraphs(organization) {
        $.ajax({
            url: 'update_data_csc.php',
            method: 'GET',
            dataType: 'json',
            data: { t: new Date().getTime() }, // Prevent caching
            success: function (response) {
                if (!hasDataChanged(previousResponse, response)) {
                    return; // If data has not changed, do nothing
                }

                previousResponse = response;

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
                        'beedRep': 'BEED Representative', 
                        'bsedRep': 'BSED Representative', 
                        'bshmRep': 'BSHM Representative',
                        'bsoadRep': 'BSOAd Representative', 
                        'bs crimRep': 'BSCrim Representative', 
                        'bsitRep': 'BSIT Representative'
                                
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
                                            <div id='${category}BarGraph' style='height: 300px; width: 100%;'></div>
                                            <div id='${category}LineGraph' style='height: 300px; width: 100%;'></div>
                                            <div id='${category}PieChart' style='height: 300px; width: 100%;'></div>
                                            <div id='${category}DoughnutChart' style='height: 300px; width: 100%;'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        $('#results-container').append(containerHtml);
                        const categoryData = response[category];
                        generateBarGraph(categoryData, category + 'BarGraph', category + 'Image');
                        generateLineGraph(categoryData, category + 'LineGraph');
                        generatePieChart(categoryData, category + 'PieChart');
                        generateDoughnutChart(categoryData, category + 'DoughnutChart');
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data: ", status, error);
            }
        });
    }

    function hasDataChanged(oldData, newData) {
        if (!oldData) return true;
        if (JSON.stringify(oldData) !== JSON.stringify(newData)) {
            return true;
        }
        return false;
    }

    $(document).ready(function () {
        fetchAndGenerateGraphs('csc');
        
        $('#organization-form').submit(function (event) {
            event.preventDefault();
            const selectedOrganization = $('#organization-select').val();
            fetchAndGenerateGraphs(selectedOrganization);
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

        setInterval(function () {
            fetchAndGenerateGraphs('csc');
        }, 3000);
    });
</script>


</body>
</html>