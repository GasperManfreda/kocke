<?php
session_start();


if (!isset($_SESSION['game_initiated']) || empty($_SESSION['players'])) {
    header("Location: index.php");
    exit;
}


function roll_dice_set_local($num_dice) {
    $rolls = [];
    $sum = 0;
    for ($i = 0; $i < $num_dice; $i++) {
        $roll = rand(1, 6);
        $rolls[] = $roll;
        $sum += $roll;
    }
    return ['rolls' => $rolls, 'sum' => $sum];
}

function get_dice_face_char_local($value) {
    $faces = [' ', '⚀', '⚁', '⚂', '⚃', '⚄', '⚅'];
    return isset($faces[$value]) ? $faces[$value] : '';
}

function play_round() {
    if ($_SESSION['current_game_count'] < $_SESSION['total_games_to_play']) {
        $_SESSION['current_game_count']++;
        $_SESSION['current_round_data'] = [];
        foreach ($_SESSION['players'] as $player) {
            $dice_data = roll_dice_set_local($_SESSION['num_dice_per_player']);
            $_SESSION['current_round_data'][$player] = $dice_data;
            $_SESSION['player_total_scores'][$player] += $dice_data['sum'];
        }
    }
}


if (isset($_GET['action']) && $_GET['action'] == 'play_first_round' && $_SESSION['current_game_count'] == 0) {
    play_round();
 
    header("Location: game.php"); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['play_next_round_action'])) {
    play_round();
    header("Location: game.php"); 
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['show_results_action'])) {
    if ($_SESSION['current_game_count'] >= $_SESSION['total_games_to_play']) {
        header("Location: results.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gambling - Igra</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <h1 class="game-title">GAMBLING</h1>

        <div class="game-in-progress-area">
            <div class="all-players-display">
                <?php foreach ($_SESSION['players'] as $player): ?>
                    <div class="single-player-info">
                        <h2><?php echo strtoupper(htmlspecialchars($player)); ?></h2>
                        <?php if (isset($_SESSION['current_round_data'][$player])): ?>
                            <div class="dice-visuals">
                                <?php foreach ($_SESSION['current_round_data'][$player]['rolls'] as $roll_val): ?>
                                    <span class="dice-symbol"><?php echo get_dice_face_char_local($roll_val); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <p>Seštevek kock: <?php echo $_SESSION['current_round_data'][$player]['sum']; ?></p>
                        <?php else: ?>
                             <p>Pripravite se na met...</p> 
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="current-game-status">
                <p>Številka igre: <?php echo $_SESSION['current_game_count']; ?> / <?php echo $_SESSION['total_games_to_play']; ?></p>
                <form method="POST" action="game.php">
                    <?php if (empty($_SESSION['players']) || $_SESSION['current_game_count'] == 0 && !isset($_GET['action'])): // Če še ni bilo prvega meta ?>
                         <button type="submit" formaction="game.php?action=play_first_round" name="play_first_round_button" class="action-button">Začni prvo igro</button>
                    <?php elseif ($_SESSION['current_game_count'] < $_SESSION['total_games_to_play']): ?>
                        <button type="submit" name="play_next_round_action" class="action-button">Naslednja igra</button>
                    <?php else: ?>
                        <button type="submit" name="show_results_action" class="action-button">Rezultati</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>