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
    <title>Schedule Pickup</title>
    
        <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reportIssue.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lucida+Console&display=swap">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/bbf63d7a1f.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>

<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <aside class="col-md-3 col-lg-2 bg-dark text-light p-3">
            <div class="text-center mb-4">
                <img src="image/wasteX1.png" alt="Logo" class="img-fluid">
            </div>
            <?php include 'nav.php'; ?>
        </aside>
        
        <!-- Main Content -->
        <main class="col-md-9 p-4">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title text-center mb-0">Schedule Pickup</h3>
                </div>
                <div class="card-body">
                    <form id="form" method="post" action="php/functions.php?op=addPickup">
                        <!-- Waste Type Selection -->
                        <div class="form-group text-center mb-4">
                            <h5>Choose Waste Type</h5>
                            <div class="row">
                                <?php
                                $wasteTypes = [
                                    ["household", "Household Waste", "image/household.png"],
                                    ["recyclable", "Recyclable Waste", "image/recyclable.png"],
                                    ["hazardous", "Hazardous Waste", "image/hazardous.png"]
                                ];
                                foreach ($wasteTypes as $type) {
                                    echo "<div class='col-md-4'>
                                            <div class='card p-3 icon-box text-center' data-value='{$type[0]}' style='cursor: pointer;'>
                                                <img src='{$type[2]}' alt='{$type[1]}' class='card-img-top' style='height: 80px;'>
                                                <div class='card-body'>
                                                    <p class='card-text'>{$type[1]}</p>
                                                </div>
                                            </div>
                                          </div>";
                                }
                                ?>
                            </div>
                        </div>
                        <input type="hidden" id="selectedOption" name="selectedOption">

                        <!-- Date and Time Selection -->
                        <div class="form-group">
                            <label for="daySelector">Pickup Date</label>
                            <select name="day" id="daySelector" class="form-control" required>
                                <option selected value="">Select day</option>
                                <?php
                                // Fetch the user's community and available schedule days
                                $email = $_SESSION['username'];
                                $sql = "SELECT comID FROM user WHERE userEmail = '$email'";
                                $result = mysqli_query($dbConnection, $sql);
                                $row = mysqli_fetch_assoc($result);
                                $comID = $row['comID'];

                                $sql2 = "SELECT DISTINCT scheDay FROM schedule WHERE comID = '$comID' ORDER BY FIELD(scheDay, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')";
                                $result2 = mysqli_query($dbConnection, $sql2);

                                function getNextAvailableDate($day) {
                                    $dayMap = ["Monday" => 1, "Tuesday" => 2, "Wednesday" => 3, "Thursday" => 4, "Friday" => 5, "Saturday" => 6, "Sunday" => 7];
                                    $currentDayNumber = date('N');
                                    $targetDayNumber = $dayMap[$day];
                                    $daysToAdd = ($targetDayNumber - $currentDayNumber + 7) % 7;
                                    if ($daysToAdd == 0) $daysToAdd = 7;
                                    return date('Y-m-d', strtotime("+$daysToAdd days"));
                                }

                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                    $scheDay = $row2['scheDay'];
                                    $nextAvailableDate = getNextAvailableDate($scheDay);
                                    echo "<option value='$nextAvailableDate'>$scheDay ( $nextAvailableDate )</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="timeSelector">Pickup Time</label>
                            <select name="time" id="timeSelector" class="form-control" required>
                                <option selected value="">Select a day first</option>
                                <?php
                                $daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
                                foreach ($daysOfWeek as $day) {
                                    $sql = "SELECT scheTime FROM schedule WHERE comID='$comID' AND scheDay='$day' ORDER BY STR_TO_DATE(scheTime, '%h:%i %p') ASC";
                                    $result = mysqli_query($dbConnection, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option class='$day' value='" . $row['scheTime'] . "'>" . $row['scheTime'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    // Waste Type Selection
    const iconBoxes = document.querySelectorAll('.icon-box');
    const selectedOption = document.getElementById('selectedOption');
    iconBoxes.forEach(box => {
        box.addEventListener('click', function() {
            iconBoxes.forEach(b => b.classList.remove('border-primary')); // Remove active state
            this.classList.add('border-primary'); // Highlight selected box
            selectedOption.value = this.dataset.value;
        });
    });

    // Filter Time Options Based on Selected Day
    const daySelector = document.getElementById('daySelector');
    const timeSelector = document.getElementById('timeSelector');
    daySelector.addEventListener('change', function() {
        const selectedDay = daySelector.options[daySelector.selectedIndex].text.split(' ')[0];
        for (let option of timeSelector.options) {
            option.style.display = option.classList.contains(selectedDay) ? 'block' : 'none';
        }
        timeSelector.value = ''; // Reset time selector when day changes
    });

    // Form Validation on Submit
    document.getElementById('form').addEventListener('submit', function(event) {
        if (selectedOption.value === '') {
            alert('Please select a waste type');
            event.preventDefault();
        }
    });
</script>

</body>
</html>
