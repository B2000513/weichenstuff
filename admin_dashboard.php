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
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lucida Consol&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<?php 
    global $dbConnection;
    $email = $_SESSION['username'];
    $sql = "SELECT comID,userFname FROM user WHERE userEmail = '$email'";
    $result = mysqli_query($dbConnection, $sql);
    $row = mysqli_fetch_assoc($result);
    $comID = $row['comID'];

    $sql2 = "SELECT * FROM community WHERE comID = $comID";
    $result2 = mysqli_query($dbConnection, $sql2);
    $community = mysqli_fetch_assoc($result2);

    $sql3 = "SELECT * FROM user WHERE comID = '$comID'";
    $result3 = mysqli_query($dbConnection, $sql3);

    $counter = 1;

    $sql4 = "SELECT COUNT(*) AS total_members FROM user WHERE comID = ?";
    $stmt = mysqli_prepare($dbConnection, $sql4);
    mysqli_stmt_bind_param($stmt, 'i', $comID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_members);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
?>

<body>
    <div class="container">


        <?php
            include 'admin_nav.php';
        ?>

        <div class="content">
            <div class='dashboard-title'> 
                <h1> Dashboard </h1>
            </div>
            <div class="dashboard-content">
                <div class="welcome-container">
                    <div class="image-placeholder">
                        <img src="image/logo_image.png" alt="Community Image" id="community-image">
                    </div>
                    <div class="community-details">
                        <?php
                            // Display community details
                            echo "
                            <p><strong>Community Name:</strong> {$community['comArea']}</p>
                            <p><strong>Community State:</strong> {$community['comState']}</p>
                            <p><strong>Date:</strong> {$community['createdDate']}</p>";
                        ?>
                    </div>
                </div>

                <!-- Community Members List Section -->
                <div class="community-members">
                    <h3>Community Members (Total Members: <?php echo $total_members; ?>)</h3>
                    <table>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>User Role</th>
                        </tr>
                        <?php
                            while ($member = mysqli_fetch_assoc($result3)) {
                                echo "<td>" . $counter . "</td>"; 
                                echo "<td>" . htmlspecialchars($member['userFname']) . " " . htmlspecialchars($member['userLname']) . "</td>";
                                echo "<td>" . htmlspecialchars($member['userEmail']) . "</td>";
                                echo "<td>" . htmlspecialchars($member['userRole']) . "</td>";
                                echo "</tr>";

                                $counter++;
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>

</body>

</html>
