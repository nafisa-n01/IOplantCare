<?php
session_start();
include '../connection/connect.php';

// If user not logged in, just show message
if (!isset($_SESSION['user_id'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Plant</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="alert alert-info text-center">
                Login to see your plants.
            </div>
            <div class="text-center mt-3">
                <a href="../index.php" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// user is logged in
$user_id = $_SESSION['user_id'];

// delete a plant if requested
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];

    // 1) delete related sunlight records
    $stmt = mysqli_prepare($con,
        "DELETE FROM sunlight_records WHERE user_plant_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $del_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 2) delete related water records
    $stmt = mysqli_prepare($con,
        "DELETE FROM water_records WHERE user_plant_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $del_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 3) delete related daily_status rows
    $stmt = mysqli_prepare($con,
        "DELETE FROM daily_status WHERE user_plant_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $del_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 4) delete related user_journal rows
    $stmt = mysqli_prepare($con,
        "DELETE FROM user_journal WHERE user_plant_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $del_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 5) finally delete the plant itself (only for this user)
    $stmt = mysqli_prepare($con,
        "DELETE FROM user_plant WHERE user_plant_id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $del_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('Location: add_plant.php');
    exit;
}


// adding plants by user
if (isset($_POST['add_plant'])) {
    $plant_id = $_POST['plant_id'] ?? '';

    if ($plant_id !== '') {
        // get plant name from plants table
        $plant_id = (int)$plant_id;
        $qPlant   = mysqli_query($con, "SELECT plant_name FROM plants WHERE plant_id = $plant_id");
        $plantRow = mysqli_fetch_assoc($qPlant);

        if ($plantRow) {
            $plant_name = $plantRow['plant_name'];

            // insert into user_plant table
            $today = date('Y-m-d');
            $stmt  = mysqli_prepare(
                $con,
                "INSERT INTO user_plant (user_id, date, user_plant) VALUES (?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, 'iss', $user_id, $today, $plant_name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header('Location: add_plant.php'); // avoid resubmit on refresh
    exit;
}

// get all plants from plants table
$plants_res = mysqli_query($con, "SELECT plant_id, plant_name FROM plants ORDER BY plant_name");

// get this user's plants with ids
$user_plants_res = mysqli_query(
    $con,
    "SELECT user_plant_id, user_plant FROM user_plant WHERE user_id = $user_id ORDER BY user_plant_id ASC"
);

// build array to count duplicates
$user_plants = [];
while ($row = mysqli_fetch_assoc($user_plants_res)) {
    $user_plants[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Plant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <h2 class="mb-4">My Plants</h2>

    <!-- Add plant form -->
    <form method="post" class="row g-2 align-items-center mb-4">
        <div class="col-md-6">
            <select name="plant_id" class="form-select" required>
                <option value="">Select a plant</option>
                <?php mysqli_data_seek($plants_res, 0); ?>
                <?php while ($p = mysqli_fetch_assoc($plants_res)): ?>
                    <option value="<?php echo $p['plant_id']; ?>">
                        <?php echo htmlspecialchars($p['plant_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" name="add_plant" class="btn btn-success w-100">
                Add
            </button>
        </div>
    </form>

    <!-- User plants grid -->
    <div class="row g-3 mb-4">
        <?php if (count($user_plants) === 0): ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    Add a plant you want to care for ('▽^人)
                </div>
            </div>
        <?php else: ?>
            <?php
            // track counts per plant name
            $nameCounts = [];
            foreach ($user_plants as $up):
                $name = $up['user_plant'];
                if (!isset($nameCounts[$name])) {
                    $nameCounts[$name] = 1;
                } else {
                    $nameCounts[$name]++;
                }

                $label = $name;
                if ($nameCounts[$name] > 1) {
                    $label .= ' (' . $nameCounts[$name] . ')';
                }
            ?>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span><?php echo htmlspecialchars($label); ?></span>
                            <a href="add_plant.php?delete=<?php echo $up['user_plant_id']; ?>"
                               class="text-danger fw-bold"
                               onclick="return confirm('Remove this plant?');">
                                ×
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Back button -->
    <div class="text-center">
        <a href="../index.php" class="btn btn-secondary px-4">
            Back
        </a>
    </div>

</div>

</body>
</html>
