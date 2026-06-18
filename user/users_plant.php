<?php
session_start();
include '../connection/connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/user_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle plant deletion
if (isset($_POST['delete_plant_id'])) {
    $user_plant_id = (int)$_POST['delete_plant_id'];
    $delete_sql = "DELETE FROM user_plant WHERE user_plant_id = ? AND user_id = ?";
    $delete_stmt = mysqli_prepare($con, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, 'ii', $user_plant_id, $user_id);
    mysqli_stmt_execute($delete_stmt);
    mysqli_stmt_close($delete_stmt);
}

// Display plants
$sql = "SELECT user_plant_id, user_plant FROM user_plant WHERE user_id = ? ORDER BY user_plant_id ASC";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$user_plants = [];
while ($row = mysqli_fetch_assoc($result)) {
    $user_plants[] = $row;
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Plants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">My Plants</h2>

    <?php if (empty($user_plants)): ?>
        <div class="alert alert-warning text-center">
            You don't have any plants yet. Add one first.
        </div>
    <?php else: ?>
        <div class="row justify-content-center g-3">
            <?php foreach ($user_plants as $plant): ?>
                <div class="col-md-3 col-sm-4 col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center position-relative">
                            <span class="fw-semibold d-block"><?php echo htmlspecialchars($plant['user_plant']); ?></span>
                            <form method="POST" class="position-absolute top-0 end-0 p-2" style="z-index: 1;">
                                <input type="hidden" name="delete_plant_id" value="<?php echo $plant['user_plant_id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" 
                                        onclick="return confirm('Delete this plant?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="../index.php" class="btn btn-secondary px-4">Back</a>
    </div>
</div>
</body>
</html>
