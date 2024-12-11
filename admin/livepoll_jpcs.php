<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/session.php'; ?>
    <?php include 'includes/header_jpcs.php'; ?>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .box-title {
            text-align: center;
            width: 100%;
            display: inline-block;
            font-weight: bold;
            font-size: 1.5rem;
            color: #4CAF50;
        }

        /* Organization Selection Form Styling */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        label {
            font-size: 1.1rem;
            color: #4CAF50;
        }

        select, button {
            padding: 10px;
            border-radius: 5px;
            font-size: 1rem;
            border: 1px solid #ddd;
            width: 200px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Back to Top button styles */
        #back-to-top {
            position: fixed;
            bottom: 40px;
            right: 40px;
            display: none;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            text-align: center;
            font-size: 30px;
            line-height: 60px;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        #back-to-top:hover {
            background-color: #45a049;
        }

        /* Graph Container */
        .chart-container {
            position: relative;
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        /* Candidate Images */
        .candidate-images {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .candidate-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 10px;
            opacity: 0;
            animation: fadeIn 1s ease-in-out forwards;
        }

        .candidate-image img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid #4CAF50;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .candidate-image img {
                width: 90px;
                height: 90px;
            }
        }

        @media (max-width: 480px) {
            .candidate-image img {
                width: 110px;
                height: 110px;
            }
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Modern Graph Styling */
        .canvasjs-chart-canvas {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding: 10px;
        }
    </style>
</head>
<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar_jpcs.php'; ?>
        <?php include 'includes/menubar_jpcs.php'; ?>

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
                        <option value="jpcs">JPCS</option>
                    </select>

                    <label for="graph-select">Select Graph Type:</label>
                    <select id="graph-select" name="graph-type">
                        <option value="bar">Bar Graph</option>
                        <option value="pie">Pie Chart</option>
                        <option value="line">Line Chart</option>
                        <option value="donut">Donut Chart</option>
                        <option value="stacked">Stacked Area Chart</option>
                    </select>

                    <button type="submit">Show Results</button>
                </form>

                <div class="row justify-content-center" id="results-container">
                    <!-- Results will be dynamically inserted here -->
                </div>
            </section>

            <button id="back-to-top" title="Back to top">&uarr;</button>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="path/to/jquery.min.js"></script>
    <script>
        // Bar Graph
        function generateBarGraph(dataPoints, containerId, imageContainerId) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = '';
            dataPoints.forEach(dataPoint => {
                var candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-image';
                candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
                imageContainer.appendChild(candidateDiv);
            });

            var chart = new CanvasJS.Chart(containerId, {
                animationEnabled: true,
                animationDuration: 3000,
                animationEasing: "easeInOutBounce",
                title: {
                    text: "Vote Counts",
                    fontSize: 22,
                    fontFamily: "Arial",
                    fontWeight: "bold",
                    color: "#4CAF50"
                },
                axisX: {
                    title: "",
                    includeZero: true,
                    interval: 1,
                    labelFormatter: function () {
                        return " ";
                    },
                    labelFontSize: 16
                },
                axisY: {
                    title: "",
                    interval: Math.ceil(totalVotes / 10),
                    labelFontSize: 16
                },
                data: [{
                    type: "bar",
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

        // Additional graph functions (Pie, Line, Donut, Stacked) follow the same pattern
        // Update their chart options to follow a modern, clean design like the Bar Graph function above
    </script>
</body>
</html>
