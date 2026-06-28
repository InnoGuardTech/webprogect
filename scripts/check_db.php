<?php
require_once __DIR__ . '/../config.php';
$db = getDBConnection();

// Check ads columns
$stmt = $db->query("DESCRIBE ads");
echo "=== ADS TABLE COLUMNS ===\n";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo $row['Field'] . " | " . $row['Type'] . "\n";
}

echo "\n=== SAMPLE AD ===\n";
$ad = $db->query("SELECT * FROM ads LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if($ad) print_r(array_keys($ad));

echo "\n=== TOTAL COUNTS ===\n";
echo "Users: " . $db->query("SELECT COUNT(*) FROM users")->fetchColumn() . "\n";
echo "Ads: " . $db->query("SELECT COUNT(*) FROM ads")->fetchColumn() . "\n";
echo "Comments: " . $db->query("SELECT COUNT(*) FROM comments")->fetchColumn() . "\n";
