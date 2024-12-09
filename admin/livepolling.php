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
            font-family: 'Poppins', sans-serif;
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

        .candidate-image img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
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

                <div class="row justify-content-center" id="results-container"></div>
            </section>

            <button id="back-to-top" title="Back to top">&uarr;</button>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>

    <?php include 'includes/scripts.php'; ?>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function generateGraph(dataPoints, containerId, imageContainerId, graphType) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            var chart = new CanvasJS.Chart(containerId, {
                animationEnabled: true,
                animationDuration: 1500,
                backgroundColor: "transparent",
                title: { 
                    text: "Vote Counts", 
                    fontFamily: 'Poppins', 
                    fontColor: "#333" 
                },
                data: [{
                    type: "bar",
                    indexLabel: "{label} - {percent}%",
                    indexLabelFontColor: "#ffffff",
                    dataPoints: dataPoints.map(dataPoint => ({
                        ...dataPoint,
                        color: dataPoint.color || getRandomGradient()
                    }))
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
                                            <div id='${category}Graph' style='height: 300px; width: 100%;'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        $('#results-container').append(containerHtml);
                        generateGraph(response[category], `${category}Graph`, null, graphType);
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", status, error);
                }
            });
        }

        function getRandomGradient() {
            const colors = [
                "#FF5733", "#33FF57", "#3357FF", 
                "#F39C12", "#8E44AD", "#2ECC71", 
                "#E74C3C", "#1ABC9C", "#3498DB"
            ];
            const randomColor1 = colors[Math.floor(Math.random() * colors.length)];
            const randomColor2 = colors[Math.floor(Math.random() * colors.length)];
            return `linear-gradient(90deg, ${randomColor1}, ${randomColor2})`;
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
