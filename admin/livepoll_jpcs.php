<?php
    // Include necessary PHP files
    include 'includes/session.php';
    include 'includes/header.php';
    ?>

    <!-- President Bar Graph Container -->
    <div id="presidentGraph" style="height: 300px; width: 50%; float: left;"></div>

    <!-- Vice President Bar Graph Container -->
    <div id="vicePresidentGraph" style="height: 300px; width: 50%; float: right;"></div>

    <!-- Secretary Bar Graph Container -->
    <div id="secretaryGraph" style="height: 300px; width: 50%; float: left;"></div>

    <!-- Include footer and necessary scripts -->
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/votes_modal.php'; ?>

    <script>
        // Function to generate bar graph
        function generateBarGraph(dataPoints, containerId) {
            var chart = new CanvasJS.Chart(containerId, {
                animationEnabled: true,
                title:{
                    text: "Vote Counts"
                },
                axisY: {
                    title: "Candidates",
                    includeZero: true,
                    labelFormatter: function (e) {
                        return Math.round(e.value);
                    }
                },
                axisX: {
                    title: "Vote Count",
                    includeZero: true
                },
                data: [{
                    type: "bar", // Change type to "bar"
                    dataPoints: dataPoints
                }]
            });
            chart.render();
        }

        // Function to fetch updated data from the server
        function updateData() {
            $.ajax({
                url: 'jpcs_update_data.php', // Change this to the URL of your updated data script
                type: 'GET',
                dataType: 'json',
                data: {organization: $('#organization').val()}, // Pass the selected organization to the server
                success: function(response) {
                    // Update president bar graph
                    generateBarGraph(response.presidentData, "presidentGraph");

                    // Update vice president bar graph
                    generateBarGraph(response.vicePresidentData, "vicePresidentGraph");

                    // Update secretary bar graph
                    generateBarGraph(response.secretaryData, "secretaryGraph");
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data: ' + error);
                }
            });
        }

        // Call the updateData function initially
        updateData();

        // Call the updateData function every 60 seconds (adjust as needed)
        setInterval(updateData, 60000); // 60000 milliseconds = 60 seconds
    </script>
</body>
</html>
