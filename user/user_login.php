<?php
include '../connection/connect.php';
session_start();

if (isset($_POST['user_login'])) {
    $username = $_POST['user_username'] ?? '';
    $password = $_POST['user_password'] ?? '';

    if ($username !== '' && $password !== '') {

        $sql  = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
        $res  = mysqli_query($con, $sql);
        $user = mysqli_fetch_assoc($res);

        if ($user && $user['password'] === $password) {
            $_SESSION['user_id']  = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            // redirect to index.php
            header('Location: /IOPlantCare/index.php#');
            exit;
        } else {
            $login_error = "Invalid username or password.";
        }
    } else {
        $login_error = "Please fill in both fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#ffffff; color:#1f3d2b; font-family:Inter,Segoe UI,sans-serif;">

<div class="container-fluid my-5">
  <div class="row">
    <div class="col-lg-6">
      <h2 class="mb-4 fw-semibold">User Login</h2>

      <?php if (!empty($login_error)): ?>
        <div class="alert alert-danger py-2"><?php echo $login_error; ?></div>
      <?php endif; ?>

      <form action="" method="post">
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

        <div class="mb-4">
          <label for="user_password" class="form-label small text-muted">Password</label>
          <input type="password"
                 id="user_password"
                 class="form-control rounded-3"
                 placeholder="Enter your password"
                 required
                 name="user_password"
                 style="border-color:#d6e8dc;">
        </div>

        <div class="d-flex flex-column gap-3">
          <input type="submit"
                 value="Login"
                 name="user_login"
                 class="btn rounded-3 px-4 py-2 text-white"
                 style="background-color:#2f7d4a; width:fit-content;">

          <p class="small text-muted mb-0">
            Don’t have an account?
            <a href="user_registration.php"
               style="color:#2f7d4a; text-decoration:none; font-weight:500;">
              Register
            </a>
          </p>
        </div>
      </form>

    </div>
  </div>
</div>

</body>
</html>
