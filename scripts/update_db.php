<?php
require_once __DIR__ . '/../config.php';
try {
    $db = getDBConnection();
    // Add views and isPinned to ads
    $db->exec("ALTER TABLE ads ADD COLUMN views INT DEFAULT 0");
    $db->exec("ALTER TABLE ads ADD COLUMN isPinned TINYINT(1) DEFAULT 0");
    echo "Added views and isPinned columns to ads.\n";
} catch (Exception $e) {
    echo "Notice on ads: " . $e->getMessage() . "\n";
}

try {
    // Rename ad_reports to reports and add status
    $db->exec("RENAME TABLE ad_reports TO reports");
    $db->exec("ALTER TABLE reports ADD COLUMN status VARCHAR(50) DEFAULT 'pending'");
    echo "Renamed ad_reports to reports and added status.\n";
} catch (Exception $e) {
    echo "Notice on reports: " . $e->getMessage() . "\n";
}

echo "DB Update Complete.\n";
