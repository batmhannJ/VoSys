<?php
include 'includes/session.php';
include 'includes/header.php';
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>
<head>
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
        }

        .candidate-images {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px;
        }

        .candidate-image {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .candidate-image img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .candidate-label {
            margin-left: 10px;
            font-weight: bold;
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
            <!-- Search Form -->
            <div class="row">
                <div class="col-md-12">
                    <form id="organization-form">
                        <div class="form-group">
                            <label for="organization-select">Select Organization:</label>
                            <select id="organization-select" class="form-control">
                                <option value="JPCS">JPCS</option>
                                <option value="CSC">CSC</option>
                                <option value="YMF">YMF</option>
                                <option value="PASOA">PASOA</option>
                                <option value="CODE-TG">CODE-TG</option>
                                <option value="HMSO">HMSO</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Show Results</button>
                    </form>
                </div>
            </div>
            
            <!-- Placeholder for the dynamically generated content -->
            <div class="row justify-content-center" id="results-container">
                <!-- Content will be inserted here based on the selected organization -->
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
    const categories = {
        'JPCS': {
            'president': 'President',
            'vice president': 'Vice President',
            'secretary': 'Secretary',
            'treasurer': 'Treasurer',
            'auditor': 'Auditor',
            'p.r.o': 'P.R.O',
            'businessManager': 'Business Manager',
            'beedRep': 'BEED Rep',
            'bsedRep': 'BSED Rep',
            'bshmRep': 'BSHM Rep',
            'bsoadRep': 'BSOAD Rep',
            'bs crimRep': 'BS CRIM Rep',
            'bsitRep': 'BSIT Rep'
        },
        'CSC': {
            'president': 'President',
            'vice president': 'Vice President',
            'secretary': 'Secretary',
            'treasurer': 'Treasurer',
            'auditor': 'Auditor',
            'p.r.o': 'P.R.O'
        },
        'YMF': {
            'president': 'President',
            'vice president': 'Vice President',
            'secretary': 'Secretary',
            'treasurer': 'Treasurer'
        },
        'PASOA': {
            'president': 'President',
            'vice president': 'Vice President',
            'secretary': 'Secretary',
            'treasurer': 'Treasurer'
        },
        'CODE-TG': {
            'president': 'President',
            'vice president': 'Vice President',
            'secretary': 'Secretary',
            'treasurer': 'Treasurer'
        },
        'HMSO': {
            'president': 'President',
            'vice president': 'Vice President',
            'secretary': 'Secretary',
            'treasurer': 'Treasurer'
        }
    };

    function generateBarGraph(dataPoints, containerId, imageContainerId) {
        var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

        // Update the image container
        var imageContainer = document.getElementById(imageContainerId);
        imageContainer.innerHTML = dataPoints.map(dataPoint =>
            `<div class="candidate-image">
                <img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">
                <span class="candidate-label">${dataPoint.label}</span>
            </div>`
        ).join('');

        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            animationDuration: 3000,
            animationEasing: "easeInOutBounce",
            title: {
                text: "Vote Counts"
            },
            axisX: {
                title: "",
                includeZero: true,
                interval: 1,
                labelFormatter: function () {
                    return " ";
                }
            },
            axisY: {
                title: "",
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
            url: 'update_data.php',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                // Clear previous results
                $('#results-container').empty();

                // Generate graphs for the selected organization
                const selectedCategories = categories[organization];

                for (const [categoryKey, categoryName] of Object.entries(selectedCategories)) {
                    $('#results-container').append(`
                        <div class='col-md-12'>
                            <div class='box'>
                                <div class='box-header with-border'>
                                    <h3 class='box-title'><b>${categoryName}</b></h3>
                                </div>
                                <div class='box-body'>
                                    <div class='chart-container'>
                                        <div id='${categoryKey}Graph' style='height: 300px; width: calc(100% - 70px); margin-left: 70px;'></div>
                                    </div>
                                    <div class='candidate-images' id='${categoryKey}Image'></div>
                                </div>
                            </div>
                        </div>
                    `);

                    if (response[categoryKey]) {
                        generateBarGraph(response[categoryKey], categoryKey + 'Graph', categoryKey + 'Image');
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data: ", status, error);
            }
        });
    }

    $(document).ready(function () {
        // Initial fetch for the default organization (e.g., JPCS)
        fetchAndGenerateGraphs('JPCS');

        // Handle form submission to fetch data for selected organization
        $('#organization-form').on('submit', function (event) {
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
    });
</script>
</body>
</html>
