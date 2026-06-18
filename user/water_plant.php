<?php
session_start();
include '../connection/connect.php';

date_default_timezone_set('Asia/Dhaka');
$today = date('Y-m-d');


// if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/user_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// init session bucket
if (!isset($_SESSION['water_temp'])) {
    $_SESSION['water_temp'] = [
        'user_plant_id' => null,
        'total'         => 0
    ];
}

// 1) user clicks an amount button
if (
    isset($_POST['water_amount'], $_POST['user_plant_id'], $_POST['action']) &&
    $_POST['action'] === 'add'
) {
    $user_plant_id = (int)$_POST['user_plant_id'];
    $amount        = (int)$_POST['water_amount'];

    // if plant changed, reset running total
    if ($_SESSION['water_temp']['user_plant_id'] !== $user_plant_id) {
        $_SESSION['water_temp']['user_plant_id'] = $user_plant_id;
        $_SESSION['water_temp']['total']         = 0;
    }

    $_SESSION['water_temp']['total'] += $amount;
}

// 2) user clicks Change amount
if (isset($_POST['action']) && $_POST['action'] === 'reset') {
    $_SESSION['water_temp']['total']         = 0;
    $_SESSION['water_temp']['user_plant_id'] = isset($_POST['user_plant_id'])
        ? (int)$_POST['user_plant_id']
        : null;
}

// 3) user clicks Done watering
$done_message = null;
if (
    isset($_POST['action'], $_POST['user_plant_id']) &&
    $_POST['action'] === 'done'
) {
    $user_plant_id = (int)$_POST['user_plant_id'];
    $today         = date('Y-m-d');

    // use session total BEFORE clearing
    $total = (int)$_SESSION['water_temp']['total'];

    if ($total > 0) {
        // check existing row for this plant+date
        $checkSql = "SELECT wrecord_id, calculated_wamount
                     FROM water_records
                     WHERE user_plant_id = ? AND date = ?
                     ORDER BY wrecord_id DESC
                     LIMIT 1";
        $stmt = mysqli_prepare($con, $checkSql);
        mysqli_stmt_bind_param($stmt, 'is', $user_plant_id, $today);
        mysqli_stmt_execute($stmt);
        $result   = mysqli_stmt_get_result($stmt);
        $existing = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($existing) {
            // update existing row
            $newTotal  = (int)$existing['calculated_wamount'] + $total;
            $updateSql = "UPDATE water_records
                          SET calculated_wamount = ?
                          WHERE wrecord_id = ?";
            $stmt = mysqli_prepare($con, $updateSql);
            mysqli_stmt_bind_param($stmt, 'ii', $newTotal, $existing['wrecord_id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            // insert new row
            $insertSql = "INSERT INTO water_records (user_plant_id, date, calculated_wamount)
                          VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $insertSql);
            mysqli_stmt_bind_param($stmt, 'isi', $user_plant_id, $today, $total);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        $done_message = "Watered Today (✿◠‿◠) – Total: {$total}L";

        // keep plant id, clear total AFTER using it
        $_SESSION['water_temp']['user_plant_id'] = $user_plant_id;
        $_SESSION['water_temp']['total']         = 0;
    } else {
        $done_message = "No water amount selected yet.";
    }
}

// which plant was posted from the forms
$selected_user_plant_id = isset($_POST['user_plant_id'])
    ? (int)$_POST['user_plant_id']
    : null;

// fetch user's plants
$sql  = "SELECT user_plant_id, user_plant
         FROM user_plant
         WHERE user_id = ?
         ORDER BY user_plant_id ASC";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$plants_result = mysqli_stmt_get_result($stmt);

$plants = [];
while ($row = mysqli_fetch_assoc($plants_result)) {
    $plants[] = $row;
}
mysqli_stmt_close($stmt);

// find chosen plant name
$chosen_plant_name = null;
if ($selected_user_plant_id !== null) {
    foreach ($plants as $p) {
        if ((int)$p['user_plant_id'] === $selected_user_plant_id) {
            $chosen_plant_name = $p['user_plant'];
            break;
        }
    }
}

// current total for selected plant (from session)
$current_total = 0;
if (
    $selected_user_plant_id !== null &&
    $_SESSION['water_temp']['user_plant_id'] === $selected_user_plant_id
) {
    $current_total = (int)$_SESSION['water_temp']['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Plant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">

<?php if (empty($plants)): ?>
    <h3 class="mb-4 text-center">Who do you want to water today? (✯◡✯)</h3>
    <div class="alert alert-warning text-center">
        You have no plants yet. Add a plant first.
    </div>
    <div class="text-center mt-3">
        <a href="../index.php" class="btn btn-secondary px-4">Back</a>
    </div>

<?php elseif ($selected_user_plant_id === null || $chosen_plant_name === null): ?>
    <!-- STEP 1: choose plant -->
    <h3 class="mb-4 text-center">Who do you want to water today? (✯◡✯)</h3>

    <form method="post" class="row justify-content-center g-2">
        <div class="col-md-6">
            <select name="user_plant_id" class="form-select" required>
                <option value="">Select one of your plants</option>
                <?php foreach ($plants as $p): ?>
                    <option value="<?php echo $p['user_plant_id']; ?>">
                        <?php echo htmlspecialchars($p['user_plant']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Next</button>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="../index.php" class="btn btn-secondary px-4">Back</a>
    </div>

<?php else: ?>
    <!-- STEP 2: choose amount -->
    <h3 class="mb-3 text-center">
        Watering: <?php echo htmlspecialchars($chosen_plant_name); ?>
    </h3>
    <p class="text-center text-muted mb-2">
        Choose how much water to give today.
    </p>

    <!-- current total -->
    <p class="text-center fw-semibold">
        Current total: <?php echo $current_total; ?>L
    </p>

    <!-- amount buttons -->
    <form method="post" class="row justify-content-center g-3 mb-4">
        <input type="hidden" name="user_plant_id" value="<?php echo $selected_user_plant_id; ?>">
        <input type="hidden" name="action" value="add">

        <?php
        $amounts = [1, 5, 10, 20, 50];
        foreach ($amounts as $a):
        ?>
            <div class="col-6 col-sm-4 col-md-2">
                <button type="submit"
                        name="water_amount"
                        value="<?php echo $a; ?>"
                        class="btn btn-outline-primary w-100 py-3">
                    <?php echo $a; ?>L
                </button>
            </div>
        <?php endforeach; ?>
    </form>

    <!-- confirmation buttons -->
    <form method="post" class="text-center mb-3">
        <input type="hidden" name="user_plant_id" value="<?php echo $selected_user_plant_id; ?>">

        <button type="submit" name="action" value="done" class="btn btn-success me-2">
            Done watering
        </button>

        <button type="submit" name="action" value="reset" class="btn btn-outline-secondary">
            Change amount
        </button>
    </form>

    <?php if ($done_message !== null): ?>
        <div class="text-center mt-3">
            <div class="alert alert-info d-inline-block">
                <?php echo htmlspecialchars($done_message); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="water_plant.php" class="btn btn-outline-secondary me-2">Change Plant</a>
        <a href="../index.php" class="btn btn-secondary px-4">Back</a>
    </div>
<?php endif; ?>

</div>
</body>
</html>
