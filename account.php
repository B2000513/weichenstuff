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
</head>

<body>
    <div class="container1">
        <aside class="sidebar">
            <div class="logo">
                <img src="image/wasteX1.png" alt="Logo">
            </div>
            <?php include 'nav.php'; ?>
        </aside>

        <main class="main-content">
            <header class="header">
                <div class="welcome-message">
                    <h1>My Account</h1>
                    <p>Welcome to wasteX</p>
                </div>
                <div class="user-profile">
                    <button class="noti">
                        <i class="fa-solid fa-bell"></i>
                        <span class="noti-num">3</span>
                    </button>
                    <img src="image/handsome.jpeg" alt="Profile Picture">
                    <p>Hello Nigg4</p>
                </div>
            </header>

            <section class="content">
                <div class="container">
                    <!-- Profile Image Section -->
                    <div class="row align-items-center mb-4">
                        <div class="col-3 text-center">
                            <div class="avatar">
                                <img src="image/handsome.jpeg" alt="User Avatar">
                            </div>
                        </div>
                        <div class="col-1 d-flex flex-column btn-div">
                            <button class="btn btn-avatar btn-change">Change</button>
                            <button class="btn btn-avatar btn-delete">Delete</button>
                        </div>
                        <div class="col-6"></div>
                    </div>

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
                                    <button class="edit-button btn">
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
                            <div class="col-2 offset-8 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary">Cancel</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
