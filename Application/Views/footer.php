</div>

<?php foreach($customStyles["footer"] as $style): ?>
    <link rel="stylesheet" href="<?= $style ?>?<?= $ASSETS_CACHE_ID ?>">
<?php endforeach; ?>

<?php foreach($customScripts["footer"] as $script): ?>
    <script src="<?= $script ?>?<?= $ASSETS_CACHE_ID ?>"></script>
<?php endforeach; ?>

</body>
</html>