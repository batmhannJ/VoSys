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
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="path/to/jquery.min.js"></script>
    <script>
    function generateGraph(dataPoints, containerId, imageContainerId, graphType) {
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
            title: { text: "Vote Counts" },
            data: [{
                type: graphType,
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

        if (graphType === 'bar') {
            chart.options.axisX = {
                title: "",
                includeZero: true,
                interval: 1,
                labelFormatter: function () {
                    return " ";
                }
            };

            chart.options.axisY = {
                title: "",
                interval: Math.ceil(totalVotes / 10)
            };

            // ** Add rounded corners to the bar graph **
            chart.options.data[0].cornerRadius = 10; // Set the radius for rounded corners (you can adjust this)
        }

        chart.render();
    }
</script>

</body>
</html>
