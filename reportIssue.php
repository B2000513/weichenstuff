<?php
include 'php/dbConnect.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report an Issue</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <div class="logo text-center mb-4">
                    <p class="title"> Wastex </p>
                </div>
                <?php include 'nav.php'; ?>
            </aside>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 p-4">
                <form id="form" method="post" action="php/functions.php?op=issueReport" enctype="multipart/form-data" class="p-4 shadow-sm rounded bg-white">
                    <div class="text-center mb-4">
                        <h2>Every report matters. How can we help you?</h2>
                    </div>

                    <!-- Icon Container -->
                    <div class="row text-center mb-4">
                        <div class="col-6 col-md-3">
                            <div class="icon-box border p-3 rounded" data-value="missed_pickup">
                                <img src="image/time-left.png" alt="Missed Pickup" class="img-fluid mb-2">
                                <p>Missed PickUp</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="icon-box border p-3 rounded" data-value="overflowing">
                                <img src="image/person.png" alt="Overflowing Bin" class="img-fluid mb-2">
                                <p>Overflowing Bin</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="icon-box border p-3 rounded" data-value="dumping">
                                <img src="image/illegal.png" alt="Illegal Dumping" class="img-fluid mb-2">
                                <p>Illegal Dumping</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="icon-box border p-3 rounded" data-value="others">
                                <img src="image/ellipsis.png" alt="Others" class="img-fluid mb-2">
                                <p>Others</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input for icon selection -->
                    <input type="hidden" id="selectedOption" name="selectedOption">

                    <!-- File Upload -->
                    <div class="form-group">
                        <label for="photoUpload">Upload a Photo (optional)</label>
                        <input type="file" class="form-control-file" id="photoUpload" name="photoUpload" accept=".jpg, .jpeg, .png, image/*">
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" placeholder="Enter your details here" name="description" required></textarea>
                    </div>

                    <!-- Location -->
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" placeholder="Enter your location here" name="location" required>
                    </div>

                    <!-- Date -->
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['op'])) {
                    $date = $_GET['op'];
                    echo '<input type="hidden" id="hiddenDate" name="date" value="' . $date . '">';
                }
                ?>
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // JavaScript code for icon selection and other functionalities
        const iconBoxes = document.querySelectorAll('.icon-box');
        const selectedOption = document.getElementById('selectedOption');

        iconBoxes.forEach(box => {
            box.addEventListener('click', function() {
                iconBoxes.forEach(b => b.classList.remove('active'));
                if (!this.classList.contains('active')) {
                    this.classList.add('active');
                    selectedOption.value = this.dataset.value;
                } else {
                    selectedOption.value = '';
                }
                console.log(selectedOption.value);
            });
        });

        // File upload and clear functionality
        const photoUpload = document.getElementById('photoUpload');
        const removeFile = document.getElementById('removeFile');

        photoUpload.addEventListener('change', function() {
            if (photoUpload.files.length > 0) {
                removeFile.style.display = 'inline';
            }
        });

        removeFile.addEventListener('click', function() {
            photoUpload.value = '';
            removeFile.style.display = 'none';
        });

        // Form validation
        const form = document.getElementById('form');
        form.addEventListener('submit', function(event) {
            if (selectedOption.value == '') {
                alert('Please select an issue type before submitting.');
                event.preventDefault();
            }
        });
    </script>
</body>


</html>
