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
    <title>My Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lucida Consol&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/bbf63d7a1f.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    
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
            <main class="col-md-9 col-lg-10">
                <header class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <h1>My Dashboard</h1>
                        <p class="text-muted">Welcome to wasteX</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-light me-3 position-relative" onclick="toggleNotiDropdown()">
                            <i class="fa-solid fa-bell"></i>
                        </button>

                        <!-- Notifications Dropdown -->
                        <div class="position-absolute noti-dropdown bg-white shadow p-3" id="notiDropdown" style="display: none;">
                            <button class="btn-close" onclick="closeNotiDropdown()"></button>
                            <?php
                            global $dbConnection;
                            $email = $_SESSION['username'];
                            $sql = "SELECT comID FROM user WHERE userEmail = '$email'";
                            $result = mysqli_query($dbConnection, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $comID = $row['comID'];

                            $sql2 = "SELECT annoTitle, annoDesc, annoDate, annoImage FROM announcement WHERE comID = '$comID'";
                            $result = mysqli_query($dbConnection, $sql2);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='noti-item' onclick=\"showNotificationDetails('" . addslashes($row['annoTitle']) . "', '" . $row['annoDate'] . "', 'photoUpload/" . $row['annoImage'] . "', '" . addslashes($row['annoDesc']) . "')\">
                                        <h6 class='fw-bold'>" . htmlspecialchars($row['annoTitle']) . "</h6>
                                        <p class='text-muted'>" . htmlspecialchars(substr($row['annoDesc'], 0, 50)) . "...</p>
                                      </div>";
                            }
                            ?>
                        </div>

                        <div class="text-center">
                            <img src="image/handsome.jpeg" alt="Profile Picture" class="rounded-circle" width="40" height="40">
                            <?php
                            $sql = "SELECT userFname, userLname FROM user WHERE comID = '$comID'";
                            $result = mysqli_query($dbConnection, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $fname = $row['userFname'];
                            ?>
                            <p class="mb-0">Hello <?php echo htmlspecialchars($fname); ?></p>
                        </div>
                    </div>
                </header>

                <!-- Content Section -->
                <section class="p-3">
                    <div class="row">
                        <!-- Profile Card -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    $sql2 = "SELECT comArea FROM community WHERE comID = '$comID'";
                                    $result2 = mysqli_query($dbConnection, $sql2);
                                    $row2 = mysqli_fetch_assoc($result2);
                                    ?>
                                    <h2 class="card-title"><?php echo htmlspecialchars($row2['comArea']); ?></h2>

                                    <div class="mt-3">
                                        <h5 class="fw-bold">Timetable</h5>
                                        <?php
                                        function displayTimetable($day, $comID, $dbConnection) {
                                            $sql = "SELECT scheTime FROM schedule WHERE comID = '$comID' AND scheDay = '$day' ORDER BY STR_TO_DATE(scheTime, '%h:%i %p') ASC";
                                            $result = mysqli_query($dbConnection, $sql);

                                            $timeSlots = [];
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $timeSlots[] = $row['scheTime'];
                                            }

                                            echo '<span class="text-muted">' . ($timeSlots ? implode(', ', $timeSlots) : '-') . '</span>';
                                        }

                                        $daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
                                        foreach ($daysOfWeek as $day) {
                                            echo '<div class="d-flex justify-content-between border-bottom py-2">';
                                            echo '<span class="fw-bold">' . $day . '</span>';
                                            displayTimetable($day, $comID, $dbConnection);
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                    <button class="btn btn-primary mt-4 w-100" onclick="window.location.href='schedule.php';">Schedule Your Pickup</button>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Account & Statistics -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Recent PickUp History</h5>
                                    <div>
                                        <?php
                                        $sql = "SELECT userID FROM user WHERE userEmail = '$email'";
                                        $result = mysqli_query($dbConnection, $sql);
                                        $row = mysqli_fetch_assoc($result);
                                        $userID = $row['userID'];

                                        $sql2 = "SELECT * FROM pickup WHERE userID = '$userID' ORDER BY pickupDate DESC LIMIT 3";
                                        $result2 = mysqli_query($dbConnection, $sql2);
                                        $empty = 3 - mysqli_num_rows($result2);
                                        while ($row2 = mysqli_fetch_assoc($result2)) {
                                            $day = date('l', strtotime($row2['pickupDate']));
                                            echo '<div class="border-bottom py-2">';
                                            echo '<p class="mb-0">' . $day . ' ' . $row2['pickupDate'] . '</p>';
                                            echo '<p class="text-muted">Type: ' . ucfirst($row2['pickupType']) . ' Waste</p>';
                                            echo '</div>';
                                        }

                                        for ($i = 0; $i < $empty; $i++) {
                                            echo '<div class="border-bottom py-2 text-muted">';
                                            echo '<p class="mb-0">No History</p>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Statistics</h5>
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="modal fade" id="notification-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="btn-close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <p id="modal-date"></p>
                    <img id="modal-image" src="" alt="Notification Image" class="img-fluid mb-3" />
                    <p id="modal-content"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/dashboard.js"></script>


    <?php
    // Query 1
    $result1 = mysqli_query($dbConnection, "SELECT COUNT(pickupID) FROM pickup WHERE pickupType = 'household' AND userID = '$userID'");
    $row1 = mysqli_fetch_assoc($result1);
    $value1 = $row1['COUNT(pickupID)'];


    // Query 2
    $result2 = mysqli_query($dbConnection, "SELECT COUNT(pickupID) FROM pickup WHERE pickupType = 'recyclable' AND userID = '$userID'");
    $row2 = mysqli_fetch_assoc($result2);
    $value2 = $row2['COUNT(pickupID)'];


    // Query 3
    $result3 = mysqli_query($dbConnection, "SELECT COUNT(pickupID) FROM pickup WHERE pickupType = 'hazardous' AND userID = '$userID'");
    $row3 = mysqli_fetch_assoc($result3);
    $value3 = $row3['COUNT(pickupID)'];

    ?>

    <script>
        var xValues = ["Household", "Recyclable", "Hazardous"];
        var yValues = [<?php echo $value1; ?>, <?php echo $value2; ?>, <?php echo $value3; ?>];

        var barColors = [
            "#a2df9c",
            "#00aba9",
            "#3a3939e9"
        ];

        new Chart("myChart", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Type of Waste"
                },
                legend: {
                    display: true,
                    position: 'right'
                }
            }
        });
    </script>

<script>
    function toggleNotiDropdown() {
        const dropdown = document.getElementById('notiDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }

    function closeNotiDropdown() {
        document.getElementById('notiDropdown').style.display = 'none';
    }

    // Close dropdown when clicking outside of it
    window.onclick = function(event) {
        const dropdown = document.getElementById('notiDropdown');
        if (!event.target.matches('.noti, .noti *')) {
            dropdown.style.display = 'none';
        }
    }
</script>

    <script>
        // document.addEventListener('DOMContentLoaded', function () {
        //     // This will close the notification modal when clicking outside of it
        //     window.addEventListener('click', function(event) {
        //         const modal = document.getElementById('notification-modal');
        // const notificationList = document.getElementById('notification-list');

        // // Check if modal and notificationList are not null
        // if (modal && notificationList) {
        //     // Close the modal if the click is outside of it
        //     if (event.target === modal) {
        //         closeModal();
        //     }

        //     // Close the notification list if clicking outside of it
        //     if (!notificationList.contains(event.target) && !modal.contains(event.target)) {
        //         // Hide the notification list or perform your close logic
        //         // closeNotificationList(); // Implement this function if necessary
        //     }
        // } else {
        //     console.error("Modal or notification list element not found.");
        // }
        //     });
        // });

        function showNotificationDetails(title, date, image, content) {
            // Set the content of the modal
            document.getElementById('modal-title').innerText = title;
            document.getElementById('modal-date').innerText = date;
            document.getElementById('modal-content').innerText = content;
            const modalImage = document.getElementById('modal-image');

            if (image == 'photoUpload/') {
                modalImage.style.display = 'none'; // Hide image if not present
            } else {

                modalImage.src = image;
                modalImage.style.display = 'block'; // Show image if present
            }

            // Display the modal
            document.getElementById('notification-modal').style.display = 'block';
        }

        function closeModal() {
            // Hide the modal
            document.getElementById('notification-modal').style.display = 'none';
        }
    </script>

</body>

</html>