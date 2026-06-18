<?php
session_start();
include '../connection/connect.php';

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
date_default_timezone_set('Asia/Dhaka');
$date = date('Y-m-d');


if ($user_id <= 0) {
    echo '<div class="alert alert-danger">No user selected.</div>';
    exit;
}

// score helper 1–5
function calc_score($ideal, $actual) {
    if ($actual === null || $ideal <= 0) return 1;   // no record or no ideal -> worst
    $diff  = abs($actual - $ideal);
    $ratio = $diff / $ideal;
    if ($ratio <= 0.10) return 5;
    if ($ratio <= 0.25) return 4;
    if ($ratio <= 0.50) return 3;
    if ($ratio <= 1.00) return 2;
    return 1;
}

/*
 * 1) get all user plants, their matched base plant (by name),
 *    and today’s water/sun records in one query
 */
$sql = "
    SELECT
        up.user_plant_id,
        up.user_plant,
        p.plant_id,
        p.ideal_moisture,
        p.ideal_sunlight,
        w.wrecord_id,
        w.calculated_wamount,
        s.srecord_id,
        s.calculated_samount
    FROM user_plant up
    LEFT JOIN plants p
      ON p.plant_name = up.user_plant              -- match by name
    LEFT JOIN water_records w
      ON w.user_plant_id = up.user_plant_id
     AND w.date = ?
    LEFT JOIN sunlight_records s
      ON s.user_plant_id = up.user_plant_id
     AND s.date = ?
    WHERE up.user_id = ?
    ORDER BY up.user_plant_id ASC
";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'ssi', $date, $date, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$rows = [];
while ($row = mysqli_fetch_assoc($res)) {
    $rows[] = $row;
}
mysqli_stmt_close($stmt);

// 2) compute scores + upsert into daily_status
foreach ($rows as &$r) {
    $plant_id     = isset($r['plant_id']) ? (int)$r['plant_id'] : null;
    $idealWater   = isset($r['ideal_moisture'])  ? (int)$r['ideal_moisture']  : 0;
    $idealSun     = isset($r['ideal_sunlight'])  ? (int)$r['ideal_sunlight']  : 0;
    $todayWater   = isset($r['calculated_wamount'])  ? (int)$r['calculated_wamount']  : null;
    $todaySun     = isset($r['calculated_samount'])  ? (int)$r['calculated_samount']  : null;

    $water_score    = calc_score($idealWater, $todayWater);
    $sunlight_score = calc_score($idealSun, $todaySun);

    $r['idealWater']   = $idealWater;
    $r['idealSun']     = $idealSun;
    $r['todayWater']   = $todayWater;
    $r['todaySun']     = $todaySun;
    $r['water_score']  = $water_score;
    $r['sun_score']    = $sunlight_score;

    $water_record_id    = isset($r['wrecord_id']) ? (int)$r['wrecord_id'] : null;
    $sunlight_record_id = isset($r['srecord_id']) ? (int)$r['srecord_id'] : null;
    $user_plant_id      = (int)$r['user_plant_id'];

    // upsert daily_status row – plant_id may be NULL if no match in plants
    $sqlIns = "
        INSERT INTO daily_status
            (user_id, date, water_score, sunlight_score,
             water_record_id, sunlight_record_id, user_plant_id, plant_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            water_score        = VALUES(water_score),
            sunlight_score     = VALUES(sunlight_score),
            water_record_id    = VALUES(water_record_id),
            sunlight_record_id = VALUES(sunlight_record_id),
            plant_id           = VALUES(plant_id)
    ";
    $stmt = mysqli_prepare($con, $sqlIns);
    mysqli_stmt_bind_param(
        $stmt,
        'isiiiiii',
        $user_id,
        $date,
        $water_score,
        $sunlight_score,
        $water_record_id,
        $sunlight_record_id,
        $user_plant_id,
        $plant_id
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
unset($r);
?>
<div class="row">
    <div class="col-12">
        <h3 class="mb-4 text-center">
            Daily Status for <?php echo htmlspecialchars($date); ?>
        </h3>
    </div>

    <?php if (empty($rows)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                You have no plants yet.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($rows as $r): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo htmlspecialchars($r['user_plant']); ?>
                        </h5>

                        <p class="mb-1">
                            <strong>Water score:</strong>
                            <?php echo (int)$r['water_score']; ?> / 5
                        </p>
                        <p class="mb-1">
                            <strong>Sunlight score:</strong>
                            <?php echo (int)$r['sun_score']; ?> / 5
                        </p>

                        <hr>
                        <p class="small text-muted mb-0">
                            Ideal water:
                            <?php echo $r['idealWater'] > 0 ? (int)$r['idealWater'] . 'L,' : 'N/A,'; ?>
                            <?php if ($r['todayWater'] === null): ?>
                                <span class="text-danger">no water records for today</span>
                            <?php else: ?>
                                today: <?php echo (int)$r['todayWater']; ?>L
                            <?php endif; ?>
                        </p>
                        <p class="small text-muted mb-0">
                            Ideal sunlight:
                            <?php echo $r['idealSun'] > 0 ? (int)$r['idealSun'] . 'h,' : 'N/A,'; ?>
                            <?php if ($r['todaySun'] === null): ?>
                                <span class="text-danger">no sunlight records for today</span>
                            <?php else: ?>
                                today: <?php echo (int)$r['todaySun']; ?>h
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
