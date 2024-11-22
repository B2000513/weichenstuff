<?php
    include 'php/dbConnect.php';
    session_start();
    if(!isset($_SESSION['username'])){
        header ("Location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero-banner {
            background: url('assets/images/bg/02.png') no-repeat center center/cover;
            padding: 4rem 0;
        }
        .form-container {
            background: #ffffff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container-fluid hero-banner text-white text-center">
        <h1 class="display-4">Reset Password</h1>
    </div>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="mb-4 text-center">Set a New Password</h2>
                    <form action="php/functions.php?op=firstLogin" method="post" onsubmit="return validatePassword()">
                        <div class="mb-3">
                            <label for="form_password" class="form-label">New Password</label>
                            <input type="password" id="form_password" name="password" class="form-control" placeholder="Enter your new password" required>
                        </div>
                        <div class="mb-3">
                            <label for="form_password1" class="form-label">Confirm Password</label>
                            <input type="password" id="form_password1" name="password1" class="form-control" placeholder="Re-enter your password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-4">
        <p class="text-muted">&copy; 2024 Your Company. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validatePassword() {
            const password = document.getElementById('form_password').value;
            const confirmPassword = document.getElementById('form_password1').value;
            if (password !== confirmPassword) {
                alert('Passwords do not match. Please try again.');
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
