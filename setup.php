<form method="POST" action="index.php" class="setup-area">
    <div class="player-setup-inputs">
        <?php for ($i = 0; $i < 3; $i++): ?>
            <div class="player-input-unit">
                <h3>UPORABNIK <?php echo $i + 1; ?></h3>
                <label for="player_name_<?php echo $i + 1; ?>">Ime:</label>
                <input type="text" id="player_name_<?php echo $i + 1; ?>" name="player_name_<?php echo $i + 1; ?>" value="<?php echo htmlspecialchars($_SESSION['player_defaults'][$i]); ?>">
            </div>
        <?php endfor; ?>
    </div>

    <div class="game-config-options">
        <label for="num_dice_select">Število kock:</label>
        <select id="num_dice_select" name="num_dice_select">
            <?php foreach ([1, 2, 3, 4, 5] as $opt): ?>
                <option value="<?php echo $opt; ?>" <?php echo ($_SESSION['num_dice_per_player'] == $opt) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="num_games_select">Število iger:</label>
        <select id="num_games_select" name="num_games_select">
            <?php foreach ([1, 2, 3, 4, 5] as $opt): ?>
                <option value="<?php echo $opt; ?>" <?php echo ($_SESSION['total_games_to_play'] == $opt) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" name="start_game_action" class="action-button">Igraj</button>
</form>