<?php
session_start();


function roll_dice_set($num_dice) {
    $rolls = [];
    $sum = 0;
    for ($i = 0; $i < $num_dice; $i++) {
        $roll = rand(1, 6);
        $rolls[] = $roll;
        $sum += $roll;
    }
    return ['rolls' => $rolls, 'sum' => $sum];
}

function get_dice_face_char($value) {
    $faces = [' ', '⚀', '⚁', '⚂', '⚃', '⚄', '⚅'];
    return isset($faces[$value]) ? $faces[$value] : '';
}


if (isset($_GET['action']) && $_GET['action'] == 'reset') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// --- INICIALIZACIJA SEJE (če še ni nastavljena) ---
if (!isset($_SESSION['game_stage'])) {
    $_SESSION['game_stage'] = 'setup'; // 'setup', 'playing', 'results'
    $_SESSION['players'] = [];
    $_SESSION['player_total_scores'] = [];
    $_SESSION['current_round_data'] = [];
    $_SESSION['num_dice_per_player'] = 1;
    $_SESSION['total_games_to_play'] = 1;
    $_SESSION['current_game_count'] = 0;
    $_SESSION['error_msg'] = '';
    $_SESSION['player_defaults'] = ['Igralec1', 'Igralec2', 'Igralec3'];
}

// --- OBDELAVA POST ZAHTEVKOV ---

// 1. Začetek igre iz nastavitvenega zaslona
if ($_SESSION['game_stage'] === 'setup' && isset($_POST['start_game_action'])) {
    $_SESSION['players'] = [];
    if (!empty(trim($_POST['player_name_1']))) $_SESSION['players'][] = htmlspecialchars(trim($_POST['player_name_1']));
    if (!empty(trim($_POST['player_name_2']))) $_SESSION['players'][] = htmlspecialchars(trim($_POST['player_name_2']));
    if (!empty(trim($_POST['player_name_3']))) $_SESSION['players'][] = htmlspecialchars(trim($_POST['player_name_3']));

    if (empty($_SESSION['players'])) {
        $_SESSION['error_msg'] = "Vnesite vsaj enega igralca.";
    } else {
        $_SESSION['num_dice_per_player'] = (int)$_POST['num_dice_select'];
        $_SESSION['total_games_to_play'] = (int)$_POST['num_games_select'];
        $_SESSION['player_total_scores'] = array_fill_keys($_SESSION['players'], 0);
        $_SESSION['current_game_count'] = 0;
        $_SESSION['current_round_data'] = []; // Počisti za vsak slučaj
        $_SESSION['game_stage'] = 'playing';
        $_SESSION['error_msg'] = '';

        // Samodejno izvedi prvo rundo takoj po nastavitvah
        // Ta logika je zdaj del "play_next_round_action"
        // da se ne podvaja koda, bomo simulirali klik
        $_POST['play_next_round_action'] = true; // Da se zažene spodnji blok
    }
}

// 2. Igranje naslednje runde (ali prve runde)
if ($_SESSION['game_stage'] === 'playing' && isset($_POST['play_next_round_action'])) {
    if ($_SESSION['current_game_count'] < $_SESSION['total_games_to_play']) {
        $_SESSION['current_game_count']++;
        $_SESSION['current_round_data'] = [];
        foreach ($_SESSION['players'] as $player) {
            $dice_data = roll_dice_set($_SESSION['num_dice_per_player']);
            $_SESSION['current_round_data'][$player] = $dice_data;
            $_SESSION['player_total_scores'][$player] += $dice_data['sum'];
        }
    }
}

// 3. Prikaz rezultatov
if ($_SESSION['game_stage'] === 'playing' && isset($_POST['show_results_action'])) {
    if ($_SESSION['current_game_count'] >= $_SESSION['total_games_to_play']) {
        arsort($_SESSION['player_total_scores']);
        $_SESSION['game_stage'] = 'results';
    }
}

// --- VKLJUČEVANJE POGLEDOV ---
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gambling Igra</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <h1 class="game-title">
            <?php
            if ($_SESSION['game_stage'] === 'setup') echo 'GAMBLING';
            elseif ($_SESSION['game_stage'] === 'playing') echo 'GAMBLING';
            elseif ($_SESSION['game_stage'] === 'results') echo 'REZULTATI';
            ?>
        </h1>

        <?php if (!empty($_SESSION['error_msg'])): ?>
            <p class="error-display"><?php echo $_SESSION['error_msg']; $_SESSION['error_msg'] = ''; /* Clear after display */ ?></p>
        <?php endif; ?>

        <?php
        // Vključi ustrezen pogled
        if ($_SESSION['game_stage'] === 'setup') {
            include 'setup.php';
        } elseif ($_SESSION['game_stage'] === 'playing') {
            include 'game.php';
        } elseif ($_SESSION['game_stage'] === 'results') {
            include 'results.php';
        }
        ?>
    </div>

    <?php if (file_exists('assets/dice_pile.png')): ?>
    <div class="dice-image-footer">
        <img src="assets/dice_pile.png" alt="Kocke">
    </div>
    <?php endif; ?>
</body>
</html>