<?php
session_start();
include '../connection/connect.php';

$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id) {
    echo '<div class="alert alert-warning text-center">Please login to view achievements.</div>';
    exit;
}

// 1. ACHIEVEMENT CHECKER LOGIC
$newly_earned = [];

function checkAndAward($con, $uid, $code, &$newly_earned) {
    $q = mysqli_query($con, "SELECT achievement_id, title FROM achievements WHERE criteria_code = '$code' LIMIT 1");
    if ($row = mysqli_fetch_assoc($q)) {
        $aid   = $row['achievement_id'];
        $title = $row['title'];
        $check = mysqli_query($con, "SELECT id FROM user_achievements WHERE user_id = $uid AND achievement_id = $aid");
        if (mysqli_num_rows($check) == 0) {
            $stmt = mysqli_prepare($con, "INSERT INTO user_achievements (user_id, achievement_id, date_earned) VALUES (?, ?, NOW())");
            mysqli_stmt_bind_param($stmt, 'ii', $uid, $aid);
            if (mysqli_stmt_execute($stmt)) {
                $newly_earned[] = $title;
            }
        }
    }
}

// Trigger Checks
checkAndAward($con, $user_id, 'auth_register', $newly_earned);
$res = mysqli_query($con, "SELECT COUNT(*) as cnt FROM user_plant WHERE user_id = $user_id");
if (mysqli_fetch_assoc($res)['cnt'] > 0) checkAndAward($con, $user_id, 'plant_add_first', $newly_earned);

$sql_sun = "SELECT COUNT(*) as cnt FROM sunlight_records s JOIN user_plant up ON s.user_plant_id = up.user_plant_id WHERE up.user_id = $user_id";
if (mysqli_fetch_assoc(mysqli_query($con, $sql_sun))['cnt'] > 0) checkAndAward($con, $user_id, 'record_sun_first', $newly_earned);

$sql_water = "SELECT COUNT(*) as cnt FROM water_records w JOIN user_plant up ON w.user_plant_id = up.user_plant_id WHERE up.user_id = $user_id";
if (mysqli_fetch_assoc(mysqli_query($con, $sql_water))['cnt'] > 0) checkAndAward($con, $user_id, 'record_water_first', $newly_earned);

// 2. FETCH DATA
$all_achievements = [];
$res = mysqli_query($con, "SELECT * FROM achievements");
while ($r = mysqli_fetch_assoc($res)) {
    $all_achievements[$r['achievement_id']] = $r;
    $all_achievements[$r['achievement_id']]['is_earned'] = false;
    $all_achievements[$r['achievement_id']]['date'] = null;
}

$res = mysqli_query($con, "SELECT achievement_id, date_earned FROM user_achievements WHERE user_id = $user_id");
while ($r = mysqli_fetch_assoc($res)) {
    if (isset($all_achievements[$r['achievement_id']])) {
        $all_achievements[$r['achievement_id']]['is_earned'] = true;
        $all_achievements[$r['achievement_id']]['date'] = $r['date_earned'];
    }
}
?>

<?php if (!empty($newly_earned)): ?>
    <div id="new-achievements-trigger" data-titles='<?php echo json_encode($newly_earned); ?>' style="display:none;"></div>
<?php endif; ?>

<div class="container-fluid py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="m-0 fw-bold" style="color: var(--green-dark);">Your Achievements</h3>
        
        <div class="btn-group" role="group">
            <input type="radio" class="btn-check" name="achFilter" id="filterAll" autocomplete="off" checked onchange="filterAch('all')">
            <label class="btn btn-outline-success btn-sm" for="filterAll">All</label>

            <input type="radio" class="btn-check" name="achFilter" id="filterEarned" autocomplete="off" onchange="filterAch('earned')">
            <label class="btn btn-outline-success btn-sm" for="filterEarned">Earned</label>

            <input type="radio" class="btn-check" name="achFilter" id="filterLocked" autocomplete="off" onchange="filterAch('locked')">
            <label class="btn btn-outline-success btn-sm" for="filterLocked">Locked</label>
        </div>
    </div>

    <div class="ach-list-container">
        <?php foreach ($all_achievements as $ach): 
            $earned = $ach['is_earned'];
            $rowClass = $earned ? 'ach-row-earned' : 'ach-row-locked';
            $iconColor = $earned ? 'text-success' : 'text-muted';
            $filterClass = $earned ? 'ach-earned' : 'ach-locked';
        ?>
            <div class="ach-item <?php echo $filterClass; ?>">
                <div class="ach-row <?php echo $rowClass; ?>">
                    
                    <div class="ach-icon-circle <?php echo $iconColor; ?>">
                        <i class="bi <?php echo htmlspecialchars($ach['icon_class']); ?>"></i>
                    </div>

                    <div class="ach-text-content">
                        <h5 class="<?php echo $earned ? 'text-dark' : 'text-secondary'; ?>">
                            <?php echo htmlspecialchars($ach['title']); ?>
                        </h5>
                        <p class="text-muted">
                            <?php echo htmlspecialchars($ach['description']); ?>
                        </p>
                        
                        <?php if ($earned): ?>
                            <div class="mt-2">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2">
                                    <i class="bi bi-check-lg"></i> Unlocked <?php echo date('M d, Y', strtotime($ach['date'])); ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="mt-2">
                                <span class="badge bg-light text-secondary border px-2">
                                    <i class="bi bi-lock-fill"></i> Locked
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="ach-xp-wrapper">
                        <div class="ach-xp-label">Reward</div>
                        <div class="ach-xp-value">+<?php echo $ach['xp_reward']; ?></div>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
