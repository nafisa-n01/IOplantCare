<?php
session_start();
include '../connection/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/user_login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$sql = "
    SELECT 
        up.user_plant  AS display_name,
        p.plant_name   AS base_name,
        p.ideal_moisture,
        p.ideal_sunlight
    FROM plant_care_tips pct
    INNER JOIN user_plant up
        ON pct.user_plant_id = up.user_plant_id
    INNER JOIN plants p
        ON pct.plant_id = p.plant_id
    WHERE up.user_id = ?
    ORDER BY up.user_plant_id ASC
";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$plants = [];
while ($row = mysqli_fetch_assoc($result)) {
    $plants[] = $row;
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Plant Care Tips</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            color: #1f3d2b;
            font-family: "Inter","Segoe UI",sans-serif;
        }
        .care-wrapper {
            max-width: 720px;
        }
        .care-item {
            border-bottom: 1px solid #d6e8dc;
            padding: 0.9rem 0;
        }
        .care-item:last-child {
            border-bottom: none;
        }
        .care-name {
            font-weight: 600;
        }
        .care-meta {
            font-size: 0.9rem;
            color: #7da892;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5 d-flex justify-content-center">
    <div class="care-wrapper w-100">
        <h3 class="mb-4 text-center">My Plant Care Tips</h3>

        <?php if (empty($plants)): ?>
            <div class="alert alert-warning text-center">
                No care tips yet, add plants to view more tips.
            </div>
        <?php else: ?>
            <?php foreach ($plants as $p): ?>
                <div class="care-item d-flex justify-content-between align-items-baseline">
                    <div>
                        <div class="care-name">
                            <?php echo htmlspecialchars($p['display_name']); ?>
                        </div>
                        <div class="care-meta">
                            Base plant: <?php echo htmlspecialchars($p['base_name']); ?>
                        </div>
                    </div>
                    <div class="text-end care-meta">
                        <?php echo (int)$p['ideal_moisture']; ?> L per day<br>
                        <?php echo (int)$p['ideal_sunlight']; ?> hours per day
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="../index.php" class="btn btn-secondary px-4">Back</a>
        </div>
    </div>
</div>

</body>
</html>
