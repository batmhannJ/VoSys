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
            font-weight: bold;
            font-size: 24px;
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
            justify-content: center;
            align-items: center;
        }

        .candidate-image {
            position: absolute;
            bottom: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
            object-fit: cover;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .chart-container canvas {
            width: 100% !important;
            height: auto !important;
        }

        .modern-bar-chart .canvasjs-chart-container {
            border-radius: 12px;
            overflow: hidden;
        }

        .modern-bar-chart .canvasjs-chart-credit {
            display: none;
        }

        @media (max-width: 768px) {
            .candidate-image {
                width: 30px;
                height: 30px;
            }
        }

        @media (max-width: 480px) {
            .candidate-image {
                width: 25px;
                height: 25px;
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

                    <label for="graph-type">Select Graph Type:</label>
                    <select id="graph-type">
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

            <button id="back-to-top" title="Back to top">&uarr;</button>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>

    <?php include 'includes/scripts.php'; ?>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="path/to/jquery.min.js"></script>
    <script>
        function generateGraph(dataPoints, containerId) {
            var chart = new CanvasJS.Chart(containerId, {
                animationEnabled: true,
                backgroundColor: "#f4f6f9",
                title: { text: "Vote Counts" },
                axisY: {
                    labelFontSize: 14,
                    labelFontColor: "#666",
                    gridThickness: 0,
                },
                data: [{
                    type: "bar",
                    indexLabelFontColor: "#fff",
                    indexLabelPlacement: "inside",
                    dataPoints: dataPoints.map((dataPoint, index) => ({
                        y: dataPoint.y,
                        label: dataPoint.label,
                        color: dataPoint.color || `hsl(${index * 45}, 70%, 50%)`, // Dynamic color
                        image: dataPoint.image
                    }))
                }]
            });

            chart.render();

            // Add candidate images inside the bars
            var canvas = document.querySelector(`#${containerId} .canvasjs-chart-canvas`);
            dataPoints.forEach((dataPoint, index) => {
                var barRect = canvas.getBoundingClientRect();
                var candidateImage = document.createElement('img');
                candidateImage.src = dataPoint.image;
                candidateImage.className = 'candidate-image';
                candidateImage.style.left = `${barRect.left + (index * 70)}px`; // Position image at the start of each bar
                candidateImage.style.top = `${barRect.top + 10}px`;
                document.body.appendChild(candidateImage);
            });
        }

        function fetchAndGenerateGraphs(organization) {
            $.ajax({
                url: 'update_data.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    $('#results-container').empty();

                    var containerId = 'results-container';
                    var containerHtml = `<div class='col-md-12'>
                        <div class='box'>
                            <div class='box-header with-border'>
                                <h3 class='box-title'><b>Election Results</b></h3>
                            </div>
                            <div class='box-body'>
                                <div class='chart-container modern-bar-chart'>
                                    <div id='${containerId}' style='height: 400px;'></div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    
                    $('#results-container').append(containerHtml);

                    generateGraph(response[organization], containerId);
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
