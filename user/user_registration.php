<?php
include '../connection/connect.php';

if (isset($_POST['user_register'])) {
    // read form fields
    $name          = $_POST['user_name'];
    $email         = $_POST['user_email'];
    $password      = $_POST['user_password'];
    $conf_password = $_POST['conf_user_password'];
    $username      = $_POST['user_username'];

    $creation_date = date('Y-m-d H:i:s');

    $insert_user = "INSERT INTO users (name, email, password, creation_date, username)
                    VALUES ('$name', '$email', '$password', '$creation_date', '$username')";
    $result = mysqli_query($con, $insert_user);

    if ($result) {
        header('Location: ../index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration form</title>

    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color:#ffffff; color:#1f3d2b; font-family:Inter,Segoe UI,sans-serif;">

<div class="container-fluid my-5">
    <div class="row">
        <div class="col-lg-6">

            <!-- Title -->
            <h2 class="mb-4 fw-semibold">New User Registration</h2>

            <!-- Form -->
            <form action="" method="post">

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="user_name" class="form-label small text-muted">Full Name</label>
                    <input type="text"
                           id="user_name"
                           class="form-control rounded-3"
                           placeholder="Enter your full name"
                           autocomplete="off"
                           required
                           name="user_name"
                           style="border-color:#d6e8dc;">
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label for="user_username" class="form-label small text-muted">Username</label>
                    <input type="text"
                           id="user_username"
                           class="form-control rounded-3"
                           placeholder="Enter your username"
                           autocomplete="off"
                           required
                           name="user_username"
                           style="border-color:#d6e8dc;">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="user_email" class="form-label small text-muted">Email</label>
                    <input type="email"
                           id="user_email"
                           class="form-control rounded-3"
                           placeholder="Enter your email address"
                           autocomplete="off"
                           required
                           name="user_email"
                           style="border-color:#d6e8dc;">
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="user_password" class="form-label small text-muted">Password</label>
                    <input type="password"
                           id="user_password"
                           class="form-control rounded-3"
                           placeholder="Create a password"
                           required
                           name="user_password"
                           style="border-color:#d6e8dc;">
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="confirm_user_password" class="form-label small text-muted">Confirm Password</label>
                    <input type="password"
                           id="confirm_user_password"
                           class="form-control rounded-3"
                           placeholder="Confirm password"
                           required
                           name="conf_user_password"
                           style="border-color:#d6e8dc;">
                </div>

                <!-- Register Button -->
                <div class="d-flex flex-column gap-3">
                    <input type="submit"
                           value="Register"
                           name="user_register"
                           class="btn rounded-3 px-4 py-2 text-white"
                           style="background-color:#2f7d4a; width:fit-content;">

                    <p class="small text-muted mb-0">
                        Already have an account?
                        <a href="user_login.php" style="color:#2f7d4a; text-decoration:none; font-weight:500;">
                            Login
                        </a>
                    </p>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>
