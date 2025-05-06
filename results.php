<div class="results-summary-area">
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
    <a href="index.php?action=reset" class="action-button play-again-link">Igraj Znova</a>
</div>