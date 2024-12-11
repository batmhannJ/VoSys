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
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        #back-to-top {
            position: fixed;
            bottom: 40px;
            right: 40px;
            display: none;
            background-color: #007BFF;
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #back-to-top:hover {
            background-color: #0056b3;
        }

        .chart-container {
            position: relative;
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f4f6f9;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .chart-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin: 20px 0;
            position: relative;
        }

        /* Candidate Images next to the bars */
        .candidate-images {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-left: 20px; /* Align the image to the left of the graph */
            width: 100px;
            position: absolute;
            top: 10px;
        }

        .candidate-image img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Adjust for smaller screens */
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
                        <option value="stackedArea">Stacked Area Chart</option>
                        <option value="doughnut">Donut Chart</option>
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
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="path/to/jquery.min.js"></script>
    <script>
        function generateGraph(dataPoints, containerId, imageContainerId, graphType) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);
            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = '';

            // Define custom colors based on your request
            const candidateColors = [
                "rgb(43, 8, 168)",   // Blue color
                "rgb(158, 9, 29)",   // Red color
                "rgb(43, 8, 168)",   // Blue color (repeating the color)
                "rgb(158, 9, 29)",   // Red color (repeating the color)
                "rgb(43, 8, 168)",   // Blue color (repeating the color)
                "rgb(158, 9, 29)"    // Red color (repeating the color)
            ];

            dataPoints.forEach((dataPoint, index) => {
                // Add images next to the bars
                var candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-image';
                candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
                imageContainer.appendChild(candidateDiv);

                // Assign a color based on the index (repeating the custom color palette)
                dataPoint.color = candidateColors[index % candidateColors.length];
            });

            var chartOptions = {
                animationEnabled: true,
                theme: "light2",
                title: { text: "Vote Counts" },
                data: [{
                    type: graphType,
                    dataPoints: dataPoints.map(dataPoint => ({
                        ...dataPoint,
                        color: dataPoint.color || "#4F81BC", // Default color if not assigned
                        // Remove indexLabel so the names don't show on the bars
                        indexLabel: "",   // Remove text labels completely
                    }))
                }]
            };

            // Add specific options for stacked area and donut charts
            if (graphType === "stackedArea") {
                chartOptions.data[0].type = "stackedArea";
            } else if (graphType === "doughnut") {
                chartOptions.data[0].type = "doughnut";
                chartOptions.data[0].innerRadius = 70; // Create a donut effect
            }

            var chart = new CanvasJS.Chart(containerId, chartOptions);
            chart.render();
        }

        function fetchAndGenerateGraphs(organization) {
            const graphType = $('#graph-type').val();
            $.ajax({
                url: 'update_data.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    $('#results-container').empty();
                    Object.keys(response).forEach(function (category) {
                        var containerHtml = ` 
                            <div class='col-md-12'>
                                <div class='box'>
                                    <div class='box-header with-border'>
                                        <h3 class='box-title'><b>${category}</b></h3>
                                    </div>
                                    <div class='box-body'>
                                        <div class='chart-wrapper'>
                                            <div class='candidate-images' id='${category}Image'></div>
                                            <div id='${category}Graph' style='height: 300px; width: 80%;'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        $('#results-container').append(containerHtml);
                        generateGraph(response[category], category + 'Graph', category + 'Image', graphType);
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
        });
    </script>
</body>
</html>
