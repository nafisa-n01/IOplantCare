<?php
session_start();
include '../connection/connect.php';

$current_user_id = $_SESSION['user_id'] ?? 0;

// 1. FETCH ALL USERS & CALCULATE XP DYNAMICALLY
// COALESCE(SUM(...), 0) ensures users with 0 achievements still show up.
// Group by user_id to sum their specific achievements.
$sql = "SELECT u.user_id, u.username, u.equipped_title, 
               COALESCE(SUM(a.xp_reward), 0) as total_xp
        FROM users u
        LEFT JOIN user_achievements ua ON u.user_id = ua.user_id
        LEFT JOIN achievements a ON ua.achievement_id = a.achievement_id
        GROUP BY u.user_id
        ORDER BY total_xp DESC, u.username ASC";

$res = mysqli_query($con, $sql);

// 2. FETCH LEVEL DEFINITIONS (To calculate level from XP)
$levels = [];
$lvl_res = mysqli_query($con, "SELECT * FROM game_levels ORDER BY level_num ASC");
while ($r = mysqli_fetch_assoc($lvl_res)) {
    $levels[] = $r;
}

// Helper: Calculate Level
function getLevelFromXP($xp, $levels) {
    $lvl_num = 1;
    foreach ($levels as $lvl) {
        if ($xp >= $lvl['xp_required']) {
            $lvl_num = $lvl['level_num'];
        } else {
            break; 
        }
    }
    return $lvl_num;
}

// Helper: Tier Badge Class (Same logic as index.php)
function getBadgeClass($level) {
    if ($level >= 90) return 'badge-mythic';
    if ($level >= 70) return 'badge-legendary';
    if ($level >= 40) return 'badge-epic';
    if ($level >= 20) return 'badge-rare';
    return 'badge-common';
}
?>

<div class="container-fluid py-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: var(--green-dark);">Global Leaderboard</h2>
        <p class="text-muted">See who rules the garden! Rankings are based on total XP earned.</p>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 leaderboard-table">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" class="text-center py-3" style="width: 80px;">Rank</th>
                        <th scope="col" class="py-3">User</th>
                        <th scope="col" class="py-3">Title</th>
                        <th scope="col" class="text-center py-3">Level</th>
                        <th scope="col" class="text-end py-3 pe-4">Total XP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    while ($row = mysqli_fetch_assoc($res)): 
                        $is_me = ($row['user_id'] == $current_user_id);
                        $level = getLevelFromXP($row['total_xp'], $levels);
                        $badge_class = getBadgeClass($level);
                        
                        // Special classes for Top 3
                        $row_class = "";
                        $rank_display = $rank;
                        
                        // Emphasize 1st, 2nd, 3rd with Icons
                        if ($rank == 1) {
                            $row_class = "rank-gold";
                            $rank_display = '<i class="bi bi-trophy-fill text-warning fs-4"></i>';
                        } elseif ($rank == 2) {
                            $row_class = "rank-silver";
                            $rank_display = '<i class="bi bi-trophy-fill text-secondary fs-5"></i>';
                        } elseif ($rank == 3) {
                            $row_class = "rank-bronze";
                            $rank_display = '<i class="bi bi-trophy-fill" style="color: #cd7f32;"></i>';
                        }
                        
                        if ($is_me) $row_class .= " rank-me";
                    ?>
                        <tr class="<?php echo $row_class; ?>">
                            <td class="text-center fw-bold text-secondary">
                                <?php echo $rank_display; ?>
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border" 
                                         style="width: 40px; height: 40px; color: var(--green-muted);">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                    <span class="fw-bold" style="color: var(--green-dark);">
                                        <?php echo htmlspecialchars($row['username']); ?>
                                        <?php if($is_me): ?>
                                            <span class="badge bg-success ms-2" style="font-size: 0.65rem;">YOU</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </td>

                            <td>
                                <?php if (!empty($row['equipped_title'])): ?>
                                    <span class="badge <?php echo $badge_class; ?> border border-light shadow-sm">
                                        <?php echo htmlspecialchars($row['equipped_title']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small fst-italic">No Title</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-light text-dark border rounded-pill px-3">
                                    Lvl <?php echo $level; ?>
                                </span>
                            </td>

                            <td class="text-end pe-4 fw-bold" style="color: var(--green-main);">
                                <?php echo number_format($row['total_xp']); ?> XP
                            </td>
                        </tr>
                    <?php 
                        $rank++; 
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>