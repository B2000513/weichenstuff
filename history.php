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
    <title>Pickup History</title>
    <link rel="stylesheet" href="css/history.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lucida+Console&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/bbf63d7a1f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-3 col-lg-2 bg-dark text-light p-3">
                <div class="text-center mb-4">
                    <p class="title"> Wastex </p>                
                </div>
                <?php include 'nav.php'; ?>
            </aside>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10">
                <div class="py-4 px-3">
                    <h2 class="text-primary mb-4">Pickup History</h2>

                    <!-- Filters -->
                    <div class="row mb-3 g-3">
                        <div class="col-md-6">
                            <input type="text" id="date-range" placeholder="Select Date Range ▾" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="type">
                                <option selected value="all">All ▾</option>
                                <option value="household">Household Waste</option>
                                <option value="recyclable">Recycable Waste</option>
                                <option value="hazardous">Hazardous Waste</option>
                            </select>
                        </div>
                    </div>

                    <!-- History Entries -->
                    <div id="history-container" class="list-group">
                        <?php
                        global $dbConnection;
                        $email = $_SESSION['username'];
                        $sql = "SELECT userID FROM user WHERE userEmail = '$email'";
                        $result = mysqli_query($dbConnection, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $userID = $row['userID'];

                        $sql2 = "SELECT * FROM pickup WHERE userID = '$userID'";
                        $result2 = mysqli_query($dbConnection, $sql2);

                        if (mysqli_num_rows($result2) == 0) {
                            echo '<div class="alert alert-info">No pickup history available.</div>';
                        } else {
                            while ($row2 = mysqli_fetch_assoc($result2)) {
                                $src = "";
                                if ($row2['pickupType'] == "household") {
                                    $src = "image/trash.png";
                                } else if ($row2['pickupType'] == "recyclable") {
                                    $src = "image/recycle-bin.png";
                                } else if ($row2['pickupType'] == "hazardous") {
                                    $src = "image/radiation-alt.png";
                                }
                                echo '<div class="list-group-item d-flex justify-content-between align-items-center ' . $row2['pickupType'] . ' ' . $row2['pickupDate'] . '">';
                                echo '    <div class="d-flex align-items-center">';
                                echo '        <img src="' . $src . '" alt="' . $row2['pickupType'] . ' waste" class="me-3" style="width: 40px; height: 40px;">';
                                echo '        <div>';
                                echo '            <p class="fw-bold mb-1">' . ucfirst($row2['pickupType']) . ' Waste</p>';
                                echo '            <p class="mb-0 text-muted">' . $row2['pickupDate'] . ' • ' . $row2['pickupTime'] . '</p>';
                                echo '            <span class="badge bg-' . ($row2['pickupStatus'] === 'completed' ? 'success' : 'secondary') . '">' . ucfirst($row2['pickupStatus']) . '</span>';
                                echo '        </div>';
                                echo '    </div>';
                                echo '    <button type="button" class="btn btn-link text-warning" onclick="window.location.href=\'reportIssue.php?op=' . $row2['pickupDate'] . '\'">Report Issue</button>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <script>
        flatpickr("#date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "2000-01-01"
        });
        
        function filter() {
            const dateRange = document.getElementById('date-range').value;
            const wasteType = document.getElementById('type').value;
            const historyEntries = document.querySelectorAll('.list-group-item');
            historyEntries.forEach(entry => entry.style.display = 'block');

            if (dateRange || wasteType !== 'all') {
                const [startDate, endDate] = dateRange.includes(" to ") ? dateRange.split(" to ") : [dateRange, dateRange];
                const start = startDate ? new Date(startDate) : null;
                const end = endDate ? new Date(endDate) : null;

                historyEntries.forEach(entry => {
                    const entryDate = new Date(entry.classList[2]);

                    const inDateRange = (!start || !end || (entryDate >= start && entryDate <= end));
                    const matchesType = (wasteType === 'all' || entry.classList.contains(wasteType));

                    entry.style.display = inDateRange && matchesType ? 'block' : 'none';
                });
            }

            const visibleEntries = Array.from(historyEntries).some(entry => entry.style.display === 'block');
            const noHistoryContainer = document.querySelector('.alert-info');
            if (!visibleEntries && !noHistoryContainer) {
                const noHistoryMessage = document.createElement('div');
                noHistoryMessage.classList.add('alert', 'alert-info');
                noHistoryMessage.innerText = "No pickup history available.";
                document.getElementById('history-container').appendChild(noHistoryMessage);
            } else if (visibleEntries && noHistoryContainer) {
                noHistoryContainer.remove();
            }
        }

        document.getElementById('date-range').addEventListener('change', filter);
        document.getElementById('type').addEventListener('change', filter);
    </script>
</body>

</html>
