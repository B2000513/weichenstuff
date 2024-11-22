<?php
include 'php/dbConnect.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="css/overview.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lucida+Console&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/bbf63d7a1f.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-2 bg-dark text-white p-3">
                <div class="text-center mb-4">
                    <img src="image/wasteX1.png" alt="Logo" class="img-fluid">
                </div>
                <?php include 'nav.php'; ?>
            </aside>

            <!-- Main Content -->
            <main class="col-md-10">
                <header class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <h1 class="h3">Overview</h1>
                        <p class="text-muted">Welcome to wasteX</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn position-relative me-3">
                            <i class="fa-solid fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                        </button>
                    </div>
                </header>

                <section class="content mt-4">
                    <div class="card p-4 shadow-sm">
                        <form>
                            <h2 id="statisticsH2" class="h4 mb-4">Statistics</h2>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <input type="text" id="date-range" placeholder="Select Date Range ▾" required="required" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <select id="type" class="form-select">
                                        <option selected>Report ▾</option>
                                        <option>Pickup Statistics</option>
                                        <option>Issue Reported</option>
                                        <option>Rate of Recycling</option>
                                    </select>
                                </div>
                            </div>

                            <div id="statistics" class="d-none mb-4">
                                <canvas id="myChart"></canvas>
                            </div>
                            <div id="statistics2" class="d-none mb-4">
                                <canvas id="myChart2"></canvas>
                            </div>

                            <div id="message">
                                <p class="text-center text-muted">-- Select date and report type to generate --</p>
                            </div>
                            <div id="message2" class="d-none">
                                <p class="text-center text-muted">-- There is no data for this date range. Please choose a wider date range. --</p>
                            </div>

                            <div class="curve_chart">
                                <div id="curve_chart" class="d-none" style="width: 100%; height: 350px;"></div>
                            </div>

                            <button class="btn btn-primary mt-3 generateBtn">Generate</button>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        flatpickr("#date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "2000-01-01"
        });

        let myChart;
        let myChart2;

        document.querySelector(".generateBtn").addEventListener("click", function(event) {
            event.preventDefault();
            var dateRange = document.getElementById("date-range").value;
            var reportType = document.getElementById("type").value;

            if (dateRange === "") {
                alert("Please select a date range.");
                return;
            }
            if (reportType === "Report ▾") {
                alert("Please select a report type.");
                return;
            }

            if (reportType === "Pickup Statistics") {
                document.getElementById("message").style.display = "none";
                document.getElementById("message2").style.display = "none";
                document.getElementById("curve_chart").style.display = "none";
                document.getElementById("statistics2").style.display = "none";
                document.getElementById("statistics").classList.remove("d-none");

                if (myChart) {
                 myChart.destroy(); // Destroy the existing chart to avoid conflicts
                }

                $.ajax({
                    url: 'php/functions.php?op=userPickupStatistics',
                    type: 'GET',
                    data: { dateRange: dateRange },
                    dataType: 'json',
                    success: function(response) {
                        var xValues = ["Household", "Recyclable", "Hazardous"];
                        var yValues = response.yValues;
                        var result = response.result;
                        var barColors = ["#a2df9c", "#00aba9", "#3a3939e9"];

                        if (myChart) myChart.destroy();

                        myChart = new Chart("myChart", {
                            type: "pie",
                            data: { labels: xValues, datasets: [{ backgroundColor: barColors, data: yValues }] },
                            options: { title: { display: true, text: "Pickup Statistics" }, legend: { display: true, position: 'right' } }
                        });

                        if (result == 'noData') {
                            document.getElementById("message2").classList.remove("d-none");
                            document.getElementById("statistics").classList.add("d-none");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else if (reportType === "Rate of Recycling") {
                if (!dateRange.includes('to')) {
                    alert("Select date range instead of a single date.");
                    return;
                }
                document.getElementById("message").style.display = "none";
                document.getElementById("message2").style.display = "none";
                document.getElementById("statistics").style.display = "none";
                document.getElementById("statistics2").style.display = "none";
                document.getElementById("curve_chart").classList.remove("d-none");

            
                $.ajax({
                    url: 'php/functions.php?op=userRateOfRecycling',
                    type: 'GET',
                    data: { dateRange: dateRange },
                    dataType: 'json',
                    success: function(response) {
                        var recyclingData = response.recyclingData;
                        var result = response.result;

                        google.charts.load('current', { 'packages': ['corechart'] });
                        google.charts.setOnLoadCallback(function() {
                            drawChart(recyclingData);
                        });

                        if (result == 'noData') {
                            document.getElementById("message2").classList.remove("d-none");
                            document.getElementById("curve_chart").classList.add("d-none");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

                function drawChart(recyclingData) {
                    var data = new google.visualization.DataTable();
                    data.addColumn('number', 'Week');
                    data.addColumn('number', 'ROR');

                    data.addRows(recyclingData);

                    const options = { title: 'Recycling Rate', hAxis: { title: 'Week' }, vAxis: { title: 'Rate of Recycling' }, legend: 'none' };
                    const chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                    chart.draw(data, options);
                }
            } else if (reportType === "Issue Reported") {
                document.getElementById("message").style.display = "none";
                document.getElementById("message2").style.display = "none";
                document.getElementById("curve_chart").style.display = "none";
                document.getElementById("statistics2").classList.remove("d-none");
                document.getElementById("statistics").style.display = "none";

                $.ajax({
                    url: 'php/functions.php?op=userIssueReported',
                    type: 'GET',
                    data: { dateRange: dateRange },
                    dataType: 'json',
                    success: function(response) {
                        var xValues = ["Missed Pickup", "Other Issue"];
                        var yValues = response.yValues;
                        var result = response.result;
                        var barColors = ["#FF6565", "#FAAA0C"];

                        if (myChart2){
                             myChart2.destroy(); // Destroy the existing chart to avoid conflicts
                        }
                        myChart2 = new Chart("myChart2", {
                            type: "bar",
                            data: { labels: xValues, datasets: [{ backgroundColor: barColors, data: yValues }] },
                            options: { title: { display: true, text: "Issues Reported" }, legend: { display: true, position: 'right' } }
                        });

                        if (result == 'noData') {
                            document.getElementById("message2").classList.remove("d-none");
                            document.getElementById("statistics2").classList.add("d-none");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });
    </script>
</body>

</html>
