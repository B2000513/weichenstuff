<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <link rel="stylesheet" href="css/accountstyle.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lucida+Sans&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/bbf63d7a1f.js" crossorigin="anonymous"></script>
    <style>
        /* General container styling */
        .account-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header, .welcome-message {
            text-align: center;
            margin-bottom: 20px;
        }
        .avatar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        .btn-avatar {
            margin-top: 10px;
            font-size: 14px;
        }
        .input-container {
            position: relative;
        }
        .input-container .edit-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
        }
        .btn {
            font-size: 16px;
        }
        .form-container {
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="container1">
        
        <aside class="col-md-3 col-lg-2 bg-dark text-light p-3">
            <div class="logo">
                <img src="" alt="Logo">
            </div>
            <?php include 'nav.php'; ?>
        </aside>
        </aside>
        <main class="main-content">
            <div class="account-container">
                <header class="header">
                    <div class="welcome-message">
                        <h1>My Account</h1>
                        
                    </div>
                    
                </header>

               
                   

                    <!-- Form Fields -->
                    <form>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" id="firstName" placeholder="First Name">
                            </div>
                            <div class="col">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" id="lastName" placeholder="Last Name">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="password">Password</label>
                                <div class="input-container">
                                    <input type="password" class="form-control" id="password" placeholder="Password">
                                    <button class="edit-button">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Email">
                            </div>
                            <div class="col">
                                <label for="contactNumber">Contact Number</label>
                                <input type="tel" class="form-control" id="contactNumber" placeholder="Contact Number">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary">Cancel</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                
            </div>
        </main>
    </div>
</body>

</html>
