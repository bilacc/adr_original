<?php
if (!get_conf('production')) {
    print $page_stats->output_result();
}
?>
    </div>
    <div class="footer">
        <a href="https://adresar.net" target="_blank"><img src="images/virtus-logo.png" alt="Adresar nekretnine"></a>
    </div>
    <div class="version">
        Version: <?= htmlspecialchars(get_conf('cms_version')) ?>
    </div>
</div>
</body>
</html>