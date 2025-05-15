<?php
session_start();

if (!isset($_SESSION['game_initiated']) || isset($_GET['reset'])) {
    if (isset($_GET['reset'])) {
        session_destroy();
        session_start();   
    }
    $_SESSION['players'] = [];
    $_SESSION['player_total_scores'] = [];
    $_SESSION['current_round_data'] = [];
    $_SESSION['num_dice_per_player'] = 1;
    $_SESSION['total_games_to_play'] = 1;
    $_SESSION['current_game_count'] = 0;
    $_SESSION['error_msg'] = '';
    $_SESSION['player_defaults'] = ['Igralec1', 'Igralec2', 'Igralec3'];
    $_SESSION['game_initiated'] = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_game_action'])) {
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
        $_SESSION['current_round_data'] = [];
        $_SESSION['error_msg'] = '';

        header("Location: game.php?action=play_first_round");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gambling - Nastavitve</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <h1 class="game-title">GAMBLING</h1>

        <?php if (!empty($_SESSION['error_msg'])): ?>
            <p class="error-display"><?php echo $_SESSION['error_msg']; $_SESSION['error_msg'] = ''; ?></p>
        <?php endif; ?>

        <form method="POST" action="index.php" class="setup-area">
            <div class="player-setup-inputs">
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="player-input-unit">
                        <h3>UPORABNIK <?php echo $i + 1; ?></h3>
                        <input type="text" id="player_name_<?php echo $i + 1; ?>" name="player_name_<?php echo $i + 1; ?>" placeholder="<?php echo htmlspecialchars($_SESSION['player_defaults'][$i]); ?>"required>
                    </div>
                <?php endfor; ?>
            </div>

            <div class="game-config-options">
                <label for="num_dice_select">Število kock:</label>
                <select id="num_dice_select" name="num_dice_select">
                    <?php foreach ([1, 2, 3] as $opt): ?>
                        <option value="<?php echo $opt; ?>" <?php echo (isset($_SESSION['num_dice_per_player']) && $_SESSION['num_dice_per_player'] == $opt) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="num_games_select">Število iger:</label>
                <select id="num_games_select" name="num_games_select">
                    <?php foreach ([1,2,3,4,5] as $opt): ?>
                        <option value="<?php echo $opt; ?>" <?php echo (isset($_SESSION['total_games_to_play']) && $_SESSION['total_games_to_play'] == $opt) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="start_game_action" class="action-button">Igraj</button>
        </form>
    </div>
</body>
</html>