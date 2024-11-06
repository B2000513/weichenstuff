<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log In</title>
  <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" />
  <link href="css/theme.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lucida Consol&display=swap">
  <style>
    /* Basic Styling */
    body {
      font-family: 'Lucida Consol', sans-serif;
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    /* Split layout container */
    .login-container {
      display: flex;
      width: 800px;
      height: 500px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
      background-color: #fff;
    }

    /* Left image section */
    .login-image {
      flex: 1;
      background: url('image/low-apprasials-and-condo-associations-can-trip-up-mortgages.webp') center center/cover no-repeat;
    }

    /* Right form section */
    .login-form {
      flex: 1;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-form h2 {
      font-size: 1.5rem;
      color: #333;
      margin-bottom: 1rem;
    }

    /* Form Controls */
    .form-control {
      border-radius: 6px;
      box-shadow: none;
      transition: all 0.3s;
      border: 1px solid #ced4da;
    }

    .form-control:focus {
      border-color: #66bb6a;
      box-shadow: 0 0 8px rgba(102, 187, 106, 0.2);
    }

    /* Button Styling */
    .btn-primary {
      background-color: #66bb6a;
      border: none;
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      border-radius: 6px;
      transition: background-color 0.3s ease;
      width: 100%;
    }

    .btn-primary:hover {
      background-color: #57a55a;
    }

    /* Remember Checkbox and Forgot Password */
    .remember-checkbox {
      font-size: 0.9rem;
    }

    .remember-checkbox a {
      color: #66bb6a;
    }

    .remember-checkbox a:hover {
      text-decoration: underline;
    }

    /* Sign Up Link Styling */
    .signup-link {
      font-size: 0.9rem;
      color: #66bb6a;
    }

    .signup-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <!-- Left Image Section -->
    <div class="login-image"></div>

    <!-- Right Form Section -->
    <div class="login-form">
      <h2>Sign In</h2>
      <form id="contact-form" method="post" action="php/functions.php?op=login">
        <div class="form-group mb-3">
          <input id="form_name" type="email" value="<?php if (isset($_COOKIE['email'])) {
                                                      echo $_COOKIE['email'];
                                                    } ?>"
            name="name" class="form-control" placeholder="Username" required>
        </div>
        <div class="form-group mb-3">
          <input id="form_password" type="password" value="<?php if (isset($_COOKIE['pass'])) {
                                                              echo $_COOKIE['pass'];
                                                            } ?>"
            name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group mb-4">
          <div class="remember-checkbox d-flex align-items-center justify-content-between">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" <?php if (isset($_COOKIE['email'])) {
                                                                echo 'checked';
                                                              } ?> name="remember" id="check1">
              <label class="form-check-label" for="check1">Remember Me</label>
            </div>
            <a href="forget_password.php">Forgot Password?</a>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Sign In</button>
      </form>
      <div class="text-center mt-3">
        <span>Not a member?</span>
        <a href="signup.php" class="signup-link">Sign Up</a>
      </div>
    </div>
  </div>
  <script src="js/bootstrap/bootstrap.min.js"></script>
</body>

</html>