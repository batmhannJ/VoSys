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
            align-items: center;
            background-color: #f4f6f9;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .candidate-images {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-right: 15px;
        }

        .candidate-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .candidate-image img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="path/to/jquery.min.js"></script>
    <script>
       function generateGraph(dataPoints, containerId, imageContainerId, graphType) {
    var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);
    var imageContainer = document.getElementById(imageContainerId);
    imageContainer.innerHTML = '';

    // Define custom colors based on your request
    const candidateColors = [
        "rgb(43, 8, 168)",   // Blue color (5, 3, 107)
        "rgb(158, 9, 29)",  // Red color (133, 4, 21)
        "rgb(43, 8, 168)",   // Blue color (repeating the color)
        "rgb(158, 9, 29)",  // Red color (repeating the color)
        "rgb(43, 8, 168)",   // Blue color (repeating the color)
        "rgb(158, 9, 29)"   // Red color (repeating the color)
    ];

    dataPoints.forEach((dataPoint, index) => {
        var candidateDiv = document.createElement('div');
        candidateDiv.className = 'candidate-image';
        candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
        imageContainer.appendChild(candidateDiv);

        // Assign a color based on the index (repeating the custom color palette)
        dataPoint.color = candidateColors[index % candidateColors.length];
    });

    var chart = new CanvasJS.Chart(containerId, {
        animationEnabled: true,
        theme: "light2",
        title: { text: "Vote Counts" },
        data: [{
            type: graphType,
            dataPoints: dataPoints.map(dataPoint => ({
                ...dataPoint,
                color: dataPoint.color || "#4F81BC", // Default color if not assigned
                indexLabel: `${dataPoint.label} - ${(dataPoint.y / totalVotes * 100).toFixed(2)}%`,
                indexLabelFontColor: "black",
                indexLabelPlacement: "inside",
                indexLabelFontSize: 14,
                indexLabelFontWeight: "bold"
            })),
            // Adjust bar width to create space between bars
            barWidth: 30, // Customize the bar width (adjust this to achieve 20px apart spacing)
            spacing: 20 // Adds space between the bars (customize this for 20px apart)
        }]
    });

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
                                        <div class='chart-container'>
                                            <div class='candidate-images' id='${category}Image'></div>
                                            <div id='${category}Graph' style='height: 300px; width: 100%;'></div>
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
