<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'php/dbConnect.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

global $dbConnection;
$email = $_SESSION['username'];

$sql11 = "SELECT comID, userFname, userRole FROM user WHERE userEmail = ?";
$stmt = $dbConnection->prepare($sql11);
$stmt->bind_param("s", $email);
$stmt->execute();
$result11 = $stmt->get_result();
$row11 = $result11->fetch_assoc();
$stmt->close();

if (!$row11) {
    echo "<script>alert('User not found. Please login again.'); window.location.href='login.php';</script>";
    exit();
}

$comID = $row11['comID'];
$userRole = $row11['userRole'];


$sql = "SELECT i.issueID, i.issueType, i.issueDate, i.issueLoc, i.issueDesc, i.issuePhoto, i.issueStatus, i.userID 
        FROM issue i
        INNER JOIN user u ON i.userID = u.userID
        WHERE u.comID = ? AND i.issueStatus != 'Completed'";;
$stmt = $dbConnection->prepare($sql);
$stmt->bind_param("i", $comID);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issueIDs = isset($_POST['issue_ID']) ? $_POST['issue_ID'] : [];
    $bulk_status = isset($_POST['bulk_status']) ? $_POST['bulk_status'] : '';

    if (!empty($issueIDs) && !empty($bulk_status)) {
        $cannotUpdate = [];

        foreach ($issueIDs as $issueID) {
            $stmt = $dbConnection->prepare("SELECT issueStatus FROM issue WHERE issueID = ?");
            $stmt->bind_param("i", $issueID);
            $stmt->execute();
            $stmt->bind_result($current_status);
            $stmt->fetch();
            $stmt->close();

            if (($current_status === "Processing" && $bulk_status === "Pending") ||
                ($current_status === "Processing" && $bulk_status === "Processing")) {
                $cannotUpdate[] = $issueID;
                continue;
            }

            $update_stmt = $dbConnection->prepare("UPDATE issue SET issueStatus = ? WHERE issueID = ?");
            $update_stmt->bind_param("si", $bulk_status, $issueID);
            $update_stmt->execute();
            $update_stmt->close();
        }

        if (!empty($cannotUpdate)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode(implode(", ", $cannotUpdate)));
            exit();
        } else {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        echo "<script>alert('Please select at least one issue and a status.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Issue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/admin_issue.css">
</head>
<body class="mybg">
    <div class="container">

        <?php
            include 'admin_nav.php';
        ?>

        <h1 class="issue-title mt-4 text-center">Manage Issues</h1>
        <form id="issue_form" action="" method="POST" onsubmit="return confirmSubmit()">
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Issue ID</th>
                        <th>Issue Type</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Photo</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="issue_ID[]" value="<?= $row['issueID'] ?>" 
                                       data-status="<?= $row['issueStatus'] ?>" >
                            </td>
                            <td><?= $row['issueID'] ?></td>
                            <td><?= $row['issueType'] ?></td>
                            <td><?= $row['issueLoc'] ?></td>
                            <td><?= $row['issueStatus'] ?></td>
                            <td>
                                <?php if (!empty($row['issuePhoto'])): ?>
                                    <img src="uploads/<?= $row['issuePhoto'] ?>" alt="Issue Photo" width="100">
                                <?php else: ?>
                                    No photo
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#issueModal" onclick="loadIssueDetails('<?= $row['issueID'] ?>', '<?= $row['issueType'] ?>', '<?= $row['issueLoc'] ?>', '<?= $row['issueStatus'] ?>', '<?= $row['issuePhoto'] ?>', '<?= htmlspecialchars($row['issueDesc'], ENT_QUOTES) ?>')">View Details</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="manage text-center">
                <label for="bulk_status">Change Status for Selected Issues:</label>
                <select id="bulk_status" name="bulk_status" required>
                    <option value="">Select Status</option>
                    <option value="Processing">Processing</option>
                    <option value="Completed">Completed</option>
                </select><br><br>
            </div>

            <div class="manage text-center">
                <button type="submit" class="btn btn-success">Update Selected Issues</button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="issueTitle">Issue Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Type:</strong> <span id="issueType"></span></p>
                    <p><strong>Location:</strong> <span id="issueLocation"></span></p>
                    <p><strong>Description:</strong> <span id="issueDescription"></span></p>
                    <p><strong>Comment:</strong> <span id="issueComment"></span></p>
                    <p><strong>Status:</strong> <span id="issueStatus"></span></p>
                    <p><strong>Photo:</strong> 
                        <span id="photoContainer">
                            <img id="issuePhoto" src="" alt="Issue Photo" class="img-fluid" style="display: none; display: inline;">
                            <span id="noPhoto" style="display: none; ">No photo available</span>
                        </span>
                    </p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmSubmit() {
            return confirm("Are you sure you want to update the status of the selected issues?");
        }

        function loadIssueDetails(issueID, issueType, issueLocation, issueStatus, issuePhoto, issueDescription, issueComment) {
            document.getElementById("issueTitle").innerText = `Issue ID: ${issueID}`;
            document.getElementById("issueType").innerText = issueType;
            document.getElementById("issueLocation").innerText = issueLocation;
            document.getElementById("issueStatus").innerText = issueStatus;
            document.getElementById("issueDescription").innerText = issueDescription;
            
            const commentElement = document.getElementById("issueComment");
            commentElement.innerText = issueComment ? issueComment : "No comment available"; // Check for null or empty

            const photoElement = document.getElementById("issuePhoto");
            const noPhotoElement = document.getElementById("noPhoto");

            if (issuePhoto) {
                photoElement.src = "uploads/" + issuePhoto;
                photoElement.style.display = "inline"; // Show the image inline
                noPhotoElement.style.display = "none"; // Hide no photo message
            } else {
                photoElement.style.display = "none"; // Hide if no photo
                noPhotoElement.style.display = "inline"; // Show no photo message
            }
        }

        function disableCheckboxes() {
            const checkboxes = document.querySelectorAll('input[name="issue_ID[]"]');
            checkboxes.forEach(checkbox => {
                const status = checkbox.getAttribute('data-status'); 
                if (status === 'Completed') {
                    checkbox.disabled = true; 
                }
            });
        }

        window.onload = disableCheckboxes; 

        function confirmSubmit() {
            const selectedCheckboxes = document.querySelectorAll('input[name="issue_ID[]"]:checked');
            if (selectedCheckboxes.length === 0) {
                alert("Please select at least one issue to update.");
                return false; 
            }
            return confirm("Are you sure you want to update the selected issues?");
        }

        <?php if (isset($_GET['error'])): ?>
            alert("Cannot change status to Processing for issue(s): <?= ($_GET['error']) ?>. These issues are currently in Processing status.");
        <?php endif; ?>
    </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
