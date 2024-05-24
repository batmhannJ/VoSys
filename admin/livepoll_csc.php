<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>
<head>
    <!-- Add the style block to center the box titles and style the back to top button -->
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
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar_csc.php'; ?>
    <?php include 'includes/menubar_csc.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Election Results</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Results</li>
            </ol>
        </section>

        <section class="content">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>President</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="presidentGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="vicePresidentGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Secretary</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="secretaryGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Treasurer</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="treasurerGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Auditor</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="auditorGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Public Information Officer (P.R.O)</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="proGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Business Manager</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="businessManagerGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BEED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="beedRepGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="bsedRepGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSHM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="bshmRepGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSOAD Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="bsoadRepGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BS CRIM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="bscrimRepGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSIT Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <canvas id="bsitRepGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/votes_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
    const candidateData = {
        president: [],
        vicePresident: [],
        secretary: [],
        treasurer: [],
        auditor: [],
        publicInformationOfficer: [],
        businessManager: [],
        beedRepresentative: [],
        bsedRepresentative: [],
        bshmRepresentative: [],
        bsoadRepresentative: [],
        bsCrimRepresentative: [],
        bsitRepresentative: []
    };

    function createBarChart(ctx, data) {
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.label),
                datasets: [{
                    label: 'Votes',
                    data: data.map(item => item.y),
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        stacked: true
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                animation: {
                    duration: 1000,
                    onComplete: function() {
                        const chart = this.chart;
                        const ctx = chart.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.font.size, Chart.defaults.font.style, Chart.defaults.font.family);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        this.data.datasets.forEach(function(dataset, i) {
                            const meta = chart.getDatasetMeta(i);
                            meta.data.forEach(function(bar, index) {
                                const data = dataset.data[index];
                                ctx.fillText(data, bar.x, bar.y);
                            });
                        });
                    }
                }
            }
        });
    }

    function updateBarChart(chart, data) {
        chart.data.labels = data.map(item => item.label);
        chart.data.datasets[0].data = data.map(item => item.y);
        chart.update();
    }

    $(document).ready(function() {
        const presidentCtx = document.getElementById('presidentGraph').getContext('2d');
        const presidentChart = createBarChart(presidentCtx, candidateData.president);

        const vicePresidentCtx = document.getElementById('vicePresidentGraph').getContext('2d');
        const vicePresidentChart = createBarChart(vicePresidentCtx, candidateData.vicePresident);

        const secretaryCtx = document.getElementById('secretaryGraph').getContext('2d');
        const secretaryChart = createBarChart(secretaryCtx, candidateData.secretary);

        const treasurerCtx = document.getElementById('treasurerGraph').getContext('2d');
        const treasurerChart = createBarChart(treasurerCtx, candidateData.treasurer);

        const auditorCtx = document.getElementById('auditorGraph').getContext('2d');
        const auditorChart = createBarChart(auditorCtx, candidateData.auditor);

        const proCtx = document.getElementById('proGraph').getContext('2d');
        const proChart = createBarChart(proCtx, candidateData.publicInformationOfficer);

        const businessManagerCtx = document.getElementById('businessManagerGraph').getContext('2d');
        const businessManagerChart = createBarChart(businessManagerCtx, candidateData.businessManager);

        const beedRepCtx = document.getElementById('beedRepGraph').getContext('2d');
        const beedRepChart = createBarChart(beedRepCtx, candidateData.beedRepresentative);

        const bsedRepCtx = document.getElementById('bsedRepGraph').getContext('2d');
        const bsedRepChart = createBarChart(bsedRepCtx, candidateData.bsedRepresentative);

        const bshmRepCtx = document.getElementById('bshmRepGraph').getContext('2d');
        const bshmRepChart = createBarChart(bshmRepCtx, candidateData.bshmRepresentative);

        const bsoadRepCtx = document.getElementById('bsoadRepGraph').getContext('2d');
        const bsoadRepChart = createBarChart(bsoadRepCtx, candidateData.bsoadRepresentative);

        const bsCrimRepCtx = document.getElementById('bscrimRepGraph').getContext('2d');
        const bsCrimRepChart = createBarChart(bsCrimRepCtx, candidateData.bsCrimRepresentative);

        const bsitRepCtx = document.getElementById('bsitRepGraph').getContext('2d');
        const bsitRepChart = createBarChart(bsitRepCtx, candidateData.bsitRepresentative);

        function fetchData() {
            $.ajax({
                url: 'fetch_election_data.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    candidateData.president = response.president;
                    candidateData.vicePresident = response.vicePresident;
                    candidateData.secretary = response.secretary;
                    candidateData.treasurer = response.treasurer;
                    candidateData.auditor = response.auditor;
                    candidateData.publicInformationOfficer = response.publicInformationOfficer;
                    candidateData.businessManager = response.businessManager;
                    candidateData.beedRepresentative = response.beedRepresentative;
                    candidateData.bsedRepresentative = response.bsedRepresentative;
                    candidateData.bshmRepresentative = response.bshmRepresentative;
                    candidateData.bsoadRepresentative = response.bsoadRepresentative;
                    candidateData.bsCrimRepresentative = response.bsCrimRepresentative;
                    candidateData.bsitRepresentative = response.bsitRepresentative;

                    updateBarChart(presidentChart, candidateData.president);
                    updateBarChart(vicePresidentChart, candidateData.vicePresident);
                    updateBarChart(secretaryChart, candidateData.secretary);
                    updateBarChart(treasurerChart, candidateData.treasurer);
                    updateBarChart(auditorChart, candidateData.auditor);
                    updateBarChart(proChart, candidateData.publicInformationOfficer);
                    updateBarChart(businessManagerChart, candidateData.businessManager);
                    updateBarChart(beedRepChart, candidateData.beedRepresentative);
                    updateBarChart(bsedRepChart, candidateData.bsedRepresentative);
                    updateBarChart(bshmRepChart, candidateData.bshmRepresentative);
                    updateBarChart(bsoadRepChart, candidateData.bsoadRepresentative);
                    updateBarChart(bsCrimRepChart, candidateData.bsCrimRepresentative);
                    updateBarChart(bsitRepChart, candidateData.bsitRepresentative);
                }
            });
        }

        setInterval(fetchData, 5000);
    });
</script>
</body>
</html>
