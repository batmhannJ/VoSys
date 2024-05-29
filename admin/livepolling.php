<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header.php';
?>
<head>
    <style>
        .center-title {
            text-align: center;
            width: 100%;
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
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <form method="get" action="">
                                <div class="form-group">
                                    <label for="organization">Select Organization:</label>
                                    <select class="form-control" name="organization" id="organization" onchange="updateVoteCounts()">
                                        <option value="">All Organizations</option>
                                        <option value="CSC">CSC</option>
                                        <?php
                                        $organizationQuery = $conn->query("SELECT DISTINCT organization FROM voters");
                                        while($organizationRow = $organizationQuery->fetch_assoc()){
                                            $selected = ($_GET['organization'] ?? '') == $organizationRow['organization'] ? 'selected' : '';
                                            echo "<option value='".$organizationRow['organization']."' $selected>".$organizationRow['organization']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="positionsContainer">
                <!-- Dynamic position containers will be generated here -->
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/votes_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Function to generate bar graph
    function generateBarGraph(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title: {
                text: "Vote Counts"
            },
            axisY: {
                title: "Candidates"
            },
            axisX: {
                title: "Vote Count",
                includeZero: true
            },
            data: [{
                type: "bar",
                dataPoints: dataPoints
            }]
        });
        chart.render();
    }

    // Function to fetch updated data from the server
    function updateVoteCounts() {
        var organization = $('#organization').val();
        var url = organization === 'CSC' ? 'update_data_csc.php' : 'update_data.php';

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            data: { organization: organization },
            success: function(response) {
                $('#positionsContainer').empty(); // Clear previous graphs

                // Loop through each category in the response
                for (var category in response) {
                    var containerId = category + "Graph"; // Create container ID
                    var categoryName = getCategoryName(category, organization);

                    // Append new graph container
                    $('#positionsContainer').append(`
                        <div class='col-md-12'>
                            <div class='box'>
                                <div class='box-header with-border'>
                                    <h3 class='box-title center-title'><b>${categoryName}</b></h3>
                                </div>
                                <div class='box-body'>
                                    <div id='${containerId}' style='height: 300px;'></div>
                                </div>
                            </div>
                        </div>
                    `);

                    generateBarGraph(response[category], containerId); // Generate the graph
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Function to get the category name from the key
    function getCategoryName(categoryKey, organization) {
        var categories = {};

        if (organization === 'CSC') {
            categories = {
                'president': 'President',
                'vicePresident': 'Vice President',
                'secretary': 'Secretary',
                'treasurer': 'Treasurer',
                'auditor': 'Auditor',
                'pro': 'P.R.O',
                'businessManager': 'Business Manager',
                'beedRep': 'BEED Rep',
                'bsedRep': 'BSED Rep',
                'bshmRep': 'BSHM Rep',
                'bsoadRep': 'BSOAD Rep',
                'bscrimRep': 'BS Crim Rep',
                'bsitRep': 'BSIT Rep'
            };
        } else {
            categories = {
                'president': 'President',
                'vicePresidentInternal': 'Vice President for Internal Affairs',
                'vicePresidentExternal': 'Vice President for External Affairs',
                'secretary': 'Secretary',
                'treasurer': 'Treasurer',
                'auditor': 'Auditor',
                'pro': 'P.R.O',
                'dirMembership': 'Dir. for Membership',
                'dirSpecialProject': 'Dir. for Special Project',
                'blockA1stYearRep': 'Block A 1st Year Representative',
                'blockB1stYearRep': 'Block B 1st Year Representative',
                'blockA2ndYearRep': 'Block A 2nd Year Representative',
                'blockB2ndYearRep': 'Block B 2nd Year Representative',
                'blockA3rdYearRep': 'Block A 3rd Year Representative',
                'blockB3rdYearRep': 'Block B 3rd Year Representative',
                'blockA4thYearRep': 'Block A 4th Year Representative',
                'blockB4thYearRep': 'Block B 4th Year Representative'
            };
        }

        return categories[categoryKey] || categoryKey;
    }

    $(document).ready(function() {
        // Fetch and update vote counts
        updateVoteCounts();

        // Update vote counts every 5 seconds
        setInterval(updateVoteCounts, 5000);
    });
</script>
</body>
</html>
