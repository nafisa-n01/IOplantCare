<?php
session_start();
include '../connection/connect.php';

$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id) {
    echo '<div class="alert alert-warning">Please login to view levels.</div>';
    exit;
}

// 1. GET USER'S TOTAL XP
$sql_xp = "SELECT SUM(a.xp_reward) as total_xp 
           FROM user_achievements ua
           JOIN achievements a ON ua.achievement_id = a.achievement_id
           WHERE ua.user_id = $user_id";
$res_xp = mysqli_query($con, $sql_xp);
$row_xp = mysqli_fetch_assoc($res_xp);
$current_xp = (int)$row_xp['total_xp'];

// 2. DETERMINE CURRENT LEVEL
$levels = [];
$res_lvl = mysqli_query($con, "SELECT * FROM game_levels ORDER BY level_num ASC");
while ($r = mysqli_fetch_assoc($res_lvl)) {
    $levels[] = $r;
}

$current_level = 1;
$next_level_threshold = 100; 
$prev_level_threshold = 0;

foreach ($levels as $index => $lvl) {
    if ($current_xp >= $lvl['xp_required']) {
        $current_level = $lvl['level_num'];
        $prev_level_threshold = $lvl['xp_required'];
        
        if (isset($levels[$index + 1])) {
            $next_level_threshold = $levels[$index + 1]['xp_required'];
        } else {
            $next_level_threshold = $current_xp; 
        }
    } else {
        break;
    }
}

// 3. CALCULATE CIRCLE PROGRESS
$range = $next_level_threshold - $prev_level_threshold;
$gained_in_level = $current_xp - $prev_level_threshold;
$percentage = ($range > 0) ? ($gained_in_level / $range) * 100 : 100;
$percentage = min(100, max(0, $percentage));

// 4. HANDLE EQUIPPING
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['equip_title'])) {
    $title_name = mysqli_real_escape_string($con, $_POST['equip_title']);
    $check = mysqli_query($con, "SELECT * FROM game_titles WHERE title_name = '$title_name' AND min_level <= $current_level");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($con, "UPDATE users SET equipped_title = '$title_name' WHERE user_id = $user_id");
        $_SESSION['equipped_title'] = $title_name;
    }
}

// 5. FETCH DATA
$u_res = mysqli_query($con, "SELECT equipped_title FROM users WHERE user_id = $user_id");
$u_row = mysqli_fetch_assoc($u_res);
$equipped_title = $u_row['equipped_title'];
$titles_res = mysqli_query($con, "SELECT * FROM game_titles ORDER BY min_level ASC");

// --- HELPER FUNCTION FOR VISUALS ---
function getTierVisuals($level) {
    if ($level >= 90) return ['class' => 'tier-mythic',    'icon' => 'bi-stars',          'name' => 'MYTHIC'];
    if ($level >= 70) return ['class' => 'tier-legendary', 'icon' => 'bi-gem',            'name' => 'LEGENDARY'];
    if ($level >= 40) return ['class' => 'tier-epic',      'icon' => 'bi-patch-check-fill','name' => 'EPIC'];
    if ($level >= 20) return ['class' => 'tier-rare',      'icon' => 'bi-droplet-fill',   'name' => 'RARE'];
    return                   ['class' => 'tier-common',    'icon' => 'bi-flower1',        'name' => 'COMMON'];
}
?>

<div class="container-fluid py-4">
    <div class="row align-items-center mb-5">
        
        <div class="col-md-5 text-center">
            <div class="level-progress-wrapper" 
                 style="background: conic-gradient(var(--green-main) <?php echo $percentage; ?>%, #e9ecef 0deg);">
                <div class="level-progress-inner">
                    <span class="level-label">Level</span>
                    <span class="level-number"><?php echo $current_level; ?></span>
                </div>
            </div>
            
            <div class="mt-3 w-75 mx-auto">
                <div class="d-flex justify-content-between small text-muted fw-bold">
                    <span><?php echo $current_xp; ?> XP</span>
                    <span><?php echo $next_level_threshold; ?> XP</span>
                </div>
                <div class="xp-detail-bar">
                    <div class="xp-detail-fill" style="width: <?php echo $percentage; ?>%"></div>
                </div>
                <div class="text-center small mt-2 text-success">
                    <?php echo floor($next_level_threshold - $current_xp); ?> XP to next level
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <h2 class="fw-bold" style="color: var(--green-dark);">Hall of Titles</h2>
            <p class="text-muted">
                As your gardening skills grow, so does your reputation.
                Higher levels unlock <strong>Rarer</strong> and <strong>Grander</strong> titles.
            </p>
            <div class="card bg-light border-0 p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="me-3 display-6 text-warning"><i class="bi bi-crown"></i></div>
                    <div>
                        <small class="text-uppercase text-muted fw-bold">Currently Equipped</small>
                        <h4 class="mb-0 text-dark"><?php echo $equipped_title ?: 'None'; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="text-muted my-4">

    <h5 class="mb-4 text-muted fw-bold">Progression Rewards</h5>
    <div class="row g-3">
        <?php 
        while ($t = mysqli_fetch_assoc($titles_res)): 
            $is_unlocked = ($current_level >= $t['min_level']);
            $is_equipped = ($equipped_title === $t['title_name']);
            
            // Get Visuals based on Level
            $visuals = getTierVisuals($t['min_level']);
            
            // Base Class
            $card_class = $visuals['class'];
            // If locked, override visuals slightly (handled in CSS via .locked)
            if (!$is_unlocked) $card_class .= ' locked';
            if ($is_equipped) $card_class .= ' active';
        ?>
            <div class="col-md-6 col-lg-4">
                <div class="card title-card h-100 p-3 <?php echo $card_class; ?>">
                    <div class="d-flex justify-content-between align-items-start h-100">
                        <div class="d-flex flex-column justify-content-between h-100 w-100">
                            
                            <div>
                                <div class="d-flex justify-content-between w-100 align-items-start">
                                    <h5 class="card-title mb-1">
                                        <?php echo htmlspecialchars($t['title_name']); ?>
                                    </h5>
                                    <i class="bi <?php echo $visuals['icon']; ?> tier-icon fs-4"></i>
                                </div>
                                
                                <div class="d-flex gap-2 mt-2">
                                    <span class="badge tier-badge">
                                        <?php echo $visuals['name']; ?>
                                    </span>
                                    <span class="badge bg-light text-dark border">
                                        Lvl <?php echo $t['min_level']; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <?php if ($is_unlocked): ?>
                                    <?php if ($is_equipped): ?>
                                        <button class="btn btn-sm btn-success w-100 disabled" style="opacity:1;">
                                            <i class="bi bi-check2-circle"></i> Equipped
                                        </button>
                                    <?php else: ?>
                                        <form method="POST" onsubmit="equipTitle(event, '<?php echo htmlspecialchars($t['title_name']); ?>')">
                                            <input type="hidden" name="equip_title" value="<?php echo htmlspecialchars($t['title_name']); ?>">
                                            <button class="btn btn-sm btn-outline-success w-100">Equip</button>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="text-center w-100 py-1 small">
                                        <i class="bi bi-lock-fill"></i> Unlocks at Level <?php echo $t['min_level']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

        <div class="col-12 mt-4">
            <div class="coming-soon-card">
                <h3><i class="bi bi-stars"></i> Level 100+</h3>
                <p class="mb-0">More prestigious titles and rewards will be revealed in future updates.</p>
            </div>
        </div>
    </div>
</div>