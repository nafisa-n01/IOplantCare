<?php
session_start();
include '../connection/connect.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo '<div class="container py-5 text-center">
            <h3>Login to add a journal entry</h3>
          </div>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['user_plant_id'], $_POST['comment_text'])) {

    $user_plant_id = (int)$_POST['user_plant_id'];
    $comment_text  = trim($_POST['comment_text']);

    if ($comment_text !== '') {
        $sql = "INSERT INTO user_journal (user_plant_id, user_id, comment_text, date)
                VALUES (?, ?, ?, CURDATE())";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'iis', $user_plant_id, $user_id, $comment_text);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    // no redirect; index.php will re-fetch this file and replace #main-panel
}

$sql = "SELECT user_plant_id, user_plant
        FROM user_plant
        WHERE user_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$plants_result = mysqli_stmt_get_result($stmt);
$plants = [];
while ($row = mysqli_fetch_assoc($plants_result)) {
    $plants[] = $row;
}
mysqli_stmt_close($stmt);

$journals_by_plant = [];
if (!empty($plants)) {
    $ids = array_column($plants, 'user_plant_id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $sql = "SELECT comment_id, user_plant_id, comment_text, date
            FROM user_journal
            WHERE user_plant_id IN ($placeholders)
              AND user_id = ?
            ORDER BY date DESC, comment_id DESC";

    $stmt = mysqli_prepare($con, $sql);

    $bind_types = $types . 'i';
    $params = $ids;
    $params[] = $user_id;

    mysqli_stmt_bind_param($stmt, $bind_types, ...$params);
    mysqli_stmt_execute($stmt);
    $jr = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($jr)) {
        $journals_by_plant[$row['user_plant_id']][] = $row;
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Journal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h3 class="text-center mb-4">Add a journal for any of your plants ( ́ ◕◞◟◕`)</h3>

    <?php if (empty($plants)): ?>
        <p class="text-center text-muted">You have no plants yet.</p>
    <?php else: ?>
        <div class="row g-3 justify-content-center">
            <?php foreach ($plants as $plant):
                $pid = $plant['user_plant_id'];
                $has_journal = !empty($journals_by_plant[$pid]);
            ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($plant['user_plant']); ?>
                            </h5>

                            <?php if (!$has_journal): ?>
                                <p class="card-text small text-muted mb-2">no journal yet</p>
                            <?php else: ?>
                                <button class="btn btn-link p-0 mb-2" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#journal-<?php echo $pid; ?>">
                                    Check journal
                                </button>
                                <div class="collapse show" id="journal-<?php echo $pid; ?>">
                                    <div class="border rounded p-2 mb-2" style="max-height:150px; overflow:auto;">
                                        <?php foreach ($journals_by_plant[$pid] as $entry): ?>
                                            <div class="small mb-1">
                                                <span class="text-muted">
                                                    <?php echo htmlspecialchars($entry['date']); ?>:
                                                </span>
                                                <?php echo htmlspecialchars($entry['comment_text']); ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <form method="POST" class="mt-auto"
                                  onsubmit="saveJournal(event, <?php echo (int)$pid; ?>)">
                                <div class="mb-2">
                                    <textarea name="comment_text" class="form-control" rows="2"
                                              placeholder="Write or update your notes..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    Save journal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
