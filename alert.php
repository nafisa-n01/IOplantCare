<?php
session_start();
include 'connection/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: user/user_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// SQL Query: Groups multiple issues for the same plant into one card
$sql = "SELECT 
            a.user_plant_id, 
            up.user_plant, 
            GROUP_CONCAT(a.alert_type SEPARATOR ', ') as types,
            GROUP_CONCAT(CONCAT(a.alert_type, ': ', a.score, '/5') SEPARATOR ' | ') as details,
            MAX(a.alert_time) as latest_time
        FROM bad_performance_alerts a
        JOIN user_plant up ON a.user_plant_id = up.user_plant_id
        WHERE a.user_id = $user_id AND a.handled = 0
        GROUP BY a.user_plant_id
        ORDER BY latest_time DESC";

$result = mysqli_query($con, $sql);
$grouped_alerts = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Plant Health Alerts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="container py-5" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-danger fw-bold m-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Plant Care Needed</h2>
            <a href="index.php" class="btn btn-outline-secondary btn-sm">Back</a>
        </div>

        <?php if (empty($grouped_alerts)): ?>
            <div class="text-center py-5 card border-0 shadow-sm">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-success">Your garden is healthy!</h4>
                    <p class="text-muted small">No critical issues detected right now.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning border-0 shadow-sm small mb-4">
                <i class="bi bi-info-circle-fill me-2"></i> 
                These alerts disappear automatically once you record a care action for the specific plant.
            </div>

            <?php foreach ($grouped_alerts as $alert): ?>
                <div class="card mb-3 border-danger border-start border-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($alert['user_plant']); ?></h5>
                        
                        <div class="d-flex gap-2 mb-2">
                            <?php if (strpos($alert['types'], 'Water') !== false): ?>
                                <span class="badge bg-primary rounded-pill"><i class="bi bi-droplet-fill me-1"></i> Needs Water</span>
                            <?php endif; ?>
                            <?php if (strpos($alert['types'], 'Sunlight') !== false): ?>
                                <span class="badge bg-warning text-dark rounded-pill"><i class="bi bi-brightness-high-fill me-1"></i> Needs Sunlight</span>
                            <?php endif; ?>
                        </div>

                        <div class="text-muted x-small" style="font-size: 0.85rem;">
                            Current Health: <span class="text-danger"><?php echo htmlspecialchars($alert['details']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>