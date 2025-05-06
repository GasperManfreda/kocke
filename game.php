<div class="game-in-progress-area">
    <div class="all-players-display">
        <?php foreach ($_SESSION['players'] as $player): ?>
            <div class="single-player-info">
                <h2><?php echo strtoupper(htmlspecialchars($player)); ?></h2>
                <?php if (isset($_SESSION['current_round_data'][$player])): ?>
                    <div class="dice-visuals">
                        <?php foreach ($_SESSION['current_round_data'][$player]['rolls'] as $roll_val): ?>
                            <span class="dice-symbol"><?php echo get_dice_face_char($roll_val); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <p>Seštevek kock: <?php echo $_SESSION['current_round_data'][$player]['sum']; ?></p>
                <?php else: ?>
                    <p>Pripravljen...</p> <!-- To se ne bi smelo zgoditi, če je prva runda avtomatska -->
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="current-game-status">
        <p>Številka igre: <?php echo $_SESSION['current_game_count']; ?> / <?php echo $_SESSION['total_games_to_play']; ?></p>
        <form method="POST" action="index.php">
            <?php if ($_SESSION['current_game_count'] < $_SESSION['total_games_to_play']): ?>
                <button type="submit" name="play_next_round_action" class="action-button">Naslednja igra</button>
            <?php else: ?>
                <button type="submit" name="show_results_action" class="action-button">Rezultati</button>
            <?php endif; ?>
        </form>
    </div>
</div>