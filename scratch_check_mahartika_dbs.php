<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
$dbs = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
foreach ($dbs as $db) {
    if (in_array($db, ['information_schema', 'mysql', 'performance_schema', 'sys'])) continue;
    try {
        $pdo->exec("USE `$db`");
        $stmt = $pdo->query("SELECT id_sertifikat, nama_lengkap FROM tbl_sertifikat_pkl WHERE id_sertifikat = 55 OR nama_lengkap LIKE '%Mahartika%'");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            echo "DB: $db | ID 55: " . $row['nama_lengkap'] . "\n";
        }
    } catch (Exception $e) {}
}
