<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/session.php'; ?>
    <?php include 'includes/header_jpcs.php'; ?>
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
    // Store previous response to compare changes
    let previousResponse = null;

    function generateBarGraph(dataPoints, containerId, imageContainerId) {
    var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

    var imageContainer = document.getElementById(imageContainerId);
    imageContainer.innerHTML = '';
    
    // Reverse the order of dataPoints to display the images in reverse
    dataPoints.reverse().forEach(dataPoint => {
        var candidateDiv = document.createElement('div');
        candidateDiv.className = 'candidate-image';
        candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
        imageContainer.appendChild(candidateDiv);
    });

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


    function fetchAndGenerateGraphs(organization) {
        $.ajax({
            url: 'update_jpcs_data.php',
            method: 'GET',
            dataType: 'json',
            data: { t: new Date().getTime() }, // Prevent caching
            success: function (response) {
                // Compare the new response with the previous one
                if (!hasDataChanged(previousResponse, response)) {
                    // If data has not changed, do nothing
                    return;
                }

                // Store new response as the current state
                previousResponse = response;

                $('#results-container').empty();
                var categories = {
                    'jpcs': {
                        'president': 'President',
                        'vp for internal affairs': 'VP for Internal Affairs',
                        'vp for external affairs': 'VP for External Affairs',
                        'secretary': 'Secretary',
                        'treasurer': 'Treasurer',
                        'auditor': 'Auditor',
                        'p.r.o': 'P.R.O',
                        'dir. for membership': 'Dir. for Membership',
                        'dir. for special project': 'Dir. for Special Project'
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
                                            <div id='${category}Graph' style='height: 300px; width: calc(100% - 80px);'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        $('#results-container').append(containerHtml);
                        generateBarGraph(response[category], category + 'Graph', category + 'Image');
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data: ", status, error);
            }
        });
    }

    /**
     * Compares two sets of vote data to see if anything has changed.
     * @param {Object} oldData - The old vote data.
     * @param {Object} newData - The new vote data.
     * @return {boolean} True if data has changed, false otherwise.
     */
    function hasDataChanged(oldData, newData) {
        if (!oldData) return true; // If there's no previous data, assume it's new
        if (JSON.stringify(oldData) !== JSON.stringify(newData)) {
            return true; // If the new data is different from the old one, refresh
        }
        return false;
    }

    $(document).ready(function () {
        fetchAndGenerateGraphs('jpcs');
        
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

        // Check for new votes every 3 seconds
        setInterval(function () {
            fetchAndGenerateGraphs('jpcs');
        }, 3000); // 3 seconds
    });
</script>

</body>
</html>