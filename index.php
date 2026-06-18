<?php
session_start();
include 'connection/connect.php';

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

$isLoggedIn = isset($_SESSION['username']);
$username   = $isLoggedIn ? $_SESSION['username'] : null;
$user_id    = $isLoggedIn ? $_SESSION['user_id'] : 0;
$user_title = "";
$title_class = "bg-success"; 
$alert_count = 0; // Initialize alert count variable

if ($isLoggedIn) {
    // UPDATED SQL: Join with game_titles to get the level requirement
    $query = "SELECT u.equipped_title, t.min_level 
              FROM users u 
              LEFT JOIN game_titles t ON u.equipped_title = t.title_name 
              WHERE u.user_id = $user_id";
              
    $res = mysqli_query($con, $query);
    if ($row = mysqli_fetch_assoc($res)) {
        $user_title = $row['equipped_title'];
        $level = isset($row['min_level']) ? (int)$row['min_level'] : 0;
        
        // Determine Badge Class based on Level
        if ($level >= 90) { $title_class = "badge-mythic"; }
        elseif ($level >= 70) { $title_class = "badge-legendary"; }
        elseif ($level >= 40) { $title_class = "badge-epic"; }
        elseif ($level >= 20) { $title_class = "badge-rare"; }
        else { $title_class = "badge-common"; }
    }

    // --- ADDED: Check for alerts ---
    $alert_sql = "SELECT COUNT(*) as cnt FROM bad_performance_alerts WHERE user_id = $user_id AND handled = 0";
    $alert_res = mysqli_query($con, $alert_sql);
    if($a_row = mysqli_fetch_assoc($alert_res)){
        $alert_count = $a_row['cnt'];
    }
    // -------------------------------
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IO PLANT CARE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="flash-container"></div>

    <div class="container-fluid p-2 d-flex align-items-center gap-3">
        <img src="./images/logo.png" alt="" class="logo">

        <ul class="nav nav-tabs" id="plantTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="myplants-tab" onclick="loadPanel('myplants')">Myplants</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="journal-tab" onclick="loadPanel('journal')">Journal</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="dailystatus-tab" onclick="loadPanel('dailystatus')">Daily Status</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="achievements-tab" onclick="loadPanel('achievements')">Achievements</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="titles-tab" onclick="loadPanel('titles')">Titles</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="leaderboard-tab" onclick="loadPanel('leaderboard')">Leaderboard</button>
            </li>
        </ul>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container-fluid">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item me-2">
                        <span class="navbar-text text-white">
                            Welcome, 
                            <?php if(!empty($user_title)): ?>
                                <span class="badge <?php echo $title_class; ?> border border-light me-1" id="nav-user-title">
                                    <?php echo htmlspecialchars($user_title); ?>
                                </span>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($username); ?> ٩(◕‿◕｡)
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-outline-light px-3" href="index.php?logout=1">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="./user/user_login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Main Body of My Plants Panel-->

    <div class="main-cards-wrapper">
        <div id="main-panel">
            <div class="d-flex justify-content-center">
                <div class="row justify-content-center g-4 w-100" style="max-width: 900px;">

                <!-- Add Plants card (first, leftmost) -->
<div class="col-6 col-md-3">
    <a href="./user/add_plant.php" class="text-decoration-none text-dark">
        <div class="card h-100">
            <img src="./images/add.png" class="card-img-top" alt="Add plant">
            <div class="card-body">
                <p class="card-text">Add Plant</p>
            </div>
        </div>
    </a>
</div>

<!-- My Plants card (second) -->
<div class="col-6 col-md-3">
    <a href="./user/users_plant.php" class="text-decoration-none text-dark">
        <div class="card h-100">
            <img src="./images/myplant.png" class="card-img-top" alt="My plants">
            <div class="card-body">
                <p class="card-text">My Plants</p>
            </div>
        </div>
    </a>
</div>

<!-- Water card (third) -->
<div class="col-6 col-md-3">
    <a href="./user/water_plant.php" class="text-decoration-none text-dark">
        <div class="card h-100">
            <img src="./images/water.png" class="card-img-top" alt="Water">
            <div class="card-body">
                <p class="card-text">Water My Plants</p>
            </div>
        </div>
    </a>
</div>

<!-- Sunlight card (fourth) -->
<div class="col-6 col-md-3">
    <a href="./user/sunlight_plant.php" class="text-decoration-none text-dark">
        <div class="card h-100">
            <img src="./images/sunlight.png" class="card-img-top" alt="Sunlight">
            <div class="card-body">
                <p class="card-text">Let Them Sunbathe</p>
            </div>
        </div>
    </a>
</div>

<!-- Care tips button -->
<div class="mt-4 d-flex justify-content-center">
    <a href="./database_works/care_tips.php" class="btn care-tips-btn text-center">
        My Plant Care Tips
        <span class="d-block mt-1" style="font-size:0.9rem; font-weight:400; text-transform:none;">
            click here to check how much water or sunlight your plants need
        </span>
        <span class="care-tips-arrow">➜</span>
    </a>
</div>

        </div>
    </div>

                <!-- alert section: by saad-->

    <?php if ($isLoggedIn): ?>
        <a href="alert.php" class="floating-alert-btn <?php echo ($alert_count > 0) ? 'pulse-animation' : ''; ?>">
            <i class="bi bi-bell-fill"></i>
            <?php if ($alert_count > 0): ?>
                <span class="alert-count-badge"><?php echo $alert_count; ?></span>
            <?php endif; ?>
        </a>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function setActiveTab(tabId) {
        document.querySelectorAll('#plantTabs .nav-link').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabId + '-tab').classList.add('active');
    }

    function loadPanel(section) {
        setActiveTab(section);
        const panel = document.getElementById('main-panel');

        if (section === 'myplants') {
            window.location.href = 'index.php';
            return;
        }

        let url = '';
        let bodyData = '';

        if (section === 'journal') {
            url = 'database_works/journal.php';
        } else if (section === 'dailystatus') {
            url = 'database_works/daily_status.php';
            bodyData = 'user_id=<?php echo isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0; ?>&date=<?php echo date('Y-m-d'); ?>';
        } else if (section === 'achievements') {
            url = 'database_works/achievements.php';
        } else if (section === 'titles') {
            url = 'database_works/titles.php';
        } else if (section === 'leaderboard') { // NEW URL
            url = 'database_works/leaderboard.php';
        }

        panel.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-success" role="status"></div></div>';

        fetch(url, {
            method: bodyData ? 'POST' : 'GET',
            headers: bodyData ? {'Content-Type': 'application/x-www-form-urlencoded'} : {},
            body: bodyData || null
        })
        .then(res => res.text())
        .then(html => {
            panel.innerHTML = html;
            if(section === 'achievements') checkForNewAchievements();
        })
        .catch(err => {
            panel.innerHTML = '<div class="alert alert-danger">Error loading panel: ' + err + '</div>';
        });
    }

    function equipTitle(e, titleName) {
        e.preventDefault();
        const params = new URLSearchParams();
        params.append('equip_title', titleName);

        fetch('database_works/titles.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: params.toString()
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('main-panel').innerHTML = html;
            location.reload(); 
        });
    }

    function checkForNewAchievements() {
        const trigger = document.getElementById('new-achievements-trigger');
        if (trigger) {
            try {
                const titles = JSON.parse(trigger.getAttribute('data-titles'));
                titles.forEach((title, index) => {
                    setTimeout(() => { showFlashMessage(title); }, index * 300);
                });
            } catch (e) { console.error("Error parsing achievement data", e); }
        }
    }

    function showFlashMessage(title) {
        const container = document.getElementById('flash-container');
        const toast = document.createElement('div');
        toast.className = 'ach-toast';
        toast.innerHTML = `<div class="ach-toast-icon"><i class="bi bi-trophy-fill"></i></div><div class="ach-toast-body"><h6>Update!</h6><p>${title}</p></div>`;
        container.appendChild(toast);
        requestAnimationFrame(() => { toast.classList.add('show'); });
        setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 600); }, 4000);
    }
    
    function filterAch(type) {
        const items = document.querySelectorAll('.ach-item');
        items.forEach(item => {
            item.classList.remove('d-none');
            if (type === 'earned') {
                if (!item.classList.contains('ach-earned')) item.classList.add('d-none');
            } else if (type === 'locked') {
                if (!item.classList.contains('ach-locked')) item.classList.add('d-none');
            }
        });
    }
 
    //for journal panel 
    function saveJournal(e, userPlantId) {
        e.preventDefault();
        const form = e.target;
        const text = form.comment_text.value.trim();
        if (!text) return;
        const params = new URLSearchParams();
        params.append('user_plant_id', userPlantId);
        params.append('comment_text', text);
        fetch('database_works/journal.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: params.toString()
        }).then(res => res.text()).then(html => { document.getElementById('main-panel').innerHTML = html; });
    }
    </script>
</body>
</html>