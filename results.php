<?php
session_start();


if (!isset($_SESSION['game_initiated']) || $_SESSION['current_game_count'] < $_SESSION['total_games_to_play']) {

    if (isset($_SESSION['players']) && !empty($_SESSION['players'])) {
        header("Location: game.php");
    } else {
        header("Location: index.php");
    }
    exit;
}


if (isset($_SESSION['player_total_scores'])) {
    arsort($_SESSION['player_total_scores']);
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gambling - Rezultati</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <h1 class="game-title">REZULTATI</h1>

        <div class="results-summary-area">
            <?php if (!empty($_SESSION['player_total_scores'])): ?>
            <ol class="results-list">
                <?php
                $place = 1;
                foreach ($_SESSION['player_total_scores'] as $player => $score): ?>
                    <li>
                        <span class="place"><?php echo $place++; ?>.</span>
                        <strong><?php echo strtoupper(htmlspecialchars($player)); ?>:</strong> <?php echo $score; ?> točk
                    </li>
                <?php endforeach; ?>
            </ol>
            <?php else: ?>
                <p>Ni rezultatov za prikaz.</p>
            <?php endif; ?>
            <a href="index.php?reset=true" class="action-button play-again-link">Igraj Znova</a>
        </div>
    </div>
</body>
</html>