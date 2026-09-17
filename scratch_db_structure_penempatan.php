<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=web_simgang', 'root', '');
$stmt = $pdo->query('SHOW TABLES');
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    if (strpos($table, 'penempatan') !== false) {
        echo "=== TABLE: $table ===\n";
        $cols = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo " - {$col['Field']} ({$col['Type']})\n";
        }
    }
}
