<?php
// Delete SQLite database file
$sqliteFile = __DIR__ . '/database/database.sqlite';
if (file_exists($sqliteFile)) {
    if (unlink($sqliteFile)) {
        echo "✓ SQLite database file deleted successfully\n";
    } else {
        echo "✗ Failed to delete SQLite database file\n";
    }
} else {
    echo "SQLite database file not found (may already be deleted)\n";
}

// Clear Laravel cache
echo "\nClearing Laravel cache...\n";
exec('cd ' . __DIR__ . ' && php artisan config:clear 2>&1');
echo "Config cache cleared\n";
exec('cd ' . __DIR__ . ' && php artisan cache:clear 2>&1');
echo "Cache cleared\n";

echo "\n✓ All done! Your application is now configured to use MySQL only.\n";
?>
