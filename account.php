<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <link rel="stylesheet" href="css/accountstyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/bbf63d7a1f.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* CSS styles */
        .main-content { margin-left: 50px; margin-right: 50px; max-width: 1500px; }
        .account-container { margin: 20px auto; padding: 30px; background-color: #f8f9fa; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .header, .welcome-message { text-align: center; margin-bottom: 20px; }
        .input-container { position: relative; }
        .input-container .edit-button { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; }
    </style>
</head>
<body>
    <div class="container1">
        <aside class="col-md-3 col-lg-2 bg-dark text-light p-3">
            <div class="logo">
                <p class="title"> Wastex </p>
            </div>
            <?php include 'nav.php'; ?>
        </aside>
        <main class="main-content">
            <div class="account-container">
                <header class="header">
                    <div class="welcome-message">
                        <h1>My Account</h1>
                    </div>
                </header>
                <form id="accountForm">
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
                                <button type="button" class="edit-button" id="changePassword">
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
                    
                    <div class="row mb-3">
    <div class="col">
        <label for="address">Address</label>
        <input type="text" class="form-control" id="address" placeholder="Address">
    </div>
    <div class="col">
        <label for="city">City</label>
        <input type="text" class="form-control" id="city" placeholder="City">
    </div>
</div>
<div class="row mb-3">
    <div class="col">
        <label for="state">State</label>
        <select class="form-control" name="state" id="state">
                <option selected>State</option>
                <option value="selangor">Selangor</option>
                <option value="johor">Johor</option>
                <option value="kedah">Kedah</option>
                <option value="kelantan">Kelantan</option>
                <option value="malacca">Malacca</option>
                <option value="negeri_sembilan">Negeri Sembilan</option>
                <option value="Pahang">Pahang</option>
                <option value="perak">Perak</option>
                <option value="perlis">Perlis</option>
                <option value="penang">Penang</option>
                <option value="sabah">Sabah</option>
                <option value="sarawak">Sarawak</option>
                <option value="terengganu">Terengganu</option>
                </select>
    </div>
</div>
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
    <script>
        $(document).ready(function () {
            // Fetch user details
            fetchUserData();

            // Handle form submission
            $('#accountForm').on('submit', function (event) {
                event.preventDefault();
                updateUserDetails();
            });

            // Fetch user data from the server
            function fetchUserData() {
                $.ajax({
                    url: 'php/functions.php?op=getUserDetails',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        $('#firstName').val(response.firstName);
                        $('#lastName').val(response.lastName);
                        $('#email').val(response.email);
                        $('#contactNumber').val(response.contactNumber);
                        $('#address').val(response.address); // New
                        $('#city').val(response.city);       // New
                        $('#state').val(response.state);     // New
                    },
                    error: function (xhr, status, error) {
                        alert('Error fetching user data.');
                        console.error(error);
                    }
                });
            }

            // Update user details
            function updateUserDetails() {
                const userData = {
                    firstName: $('#firstName').val(),
                    lastName: $('#lastName').val(),
                    password: $('#password').val(),
                    email: $('#email').val(),
                    contactNumber: $('#contactNumber').val(),
                 address: $('#address').val(), // New
                city: $('#city').val(),       // New
                state: $('#state').val()      // New
                };

                $.ajax({
                    url: 'php/functions.php?op=updateUserDetails',
                    type: 'POST',
                    data: userData,
                    success: function (response) {
                        alert(response.message || 'User details updated successfully.');
                        fetchUserData(); // Refresh data
                    },
                    error: function (xhr, status, error) {
                        alert('Error updating user details.');
                        console.error(error);
                    }
                });
            }
        });
    </script>
</body>
</html>
