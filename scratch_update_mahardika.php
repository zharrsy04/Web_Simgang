<?php
foreach (['web_simgang', 'simgang_kominfo'] as $db) {
    echo "=== DB: $db ===\n";
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=$db", 'root', '');
    
    // Select the record first
    $stmt = $pdo->prepare("SELECT id_sertifikat, nama_lengkap FROM tbl_sertifikat_pkl WHERE id_sertifikat = 55 OR nama_lengkap LIKE '%Mahartika%' OR nama_lengkap LIKE '%Mahardika%'");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        echo "Found ID: " . $row['id_sertifikat'] . " | Name: " . $row['nama_lengkap'] . "\n";
    }

    // Update
    $stmt = $pdo->prepare("UPDATE tbl_sertifikat_pkl SET nama_lengkap = 'Elang Mahardika H' WHERE id_sertifikat = 55 OR nama_lengkap LIKE '%Mahartika%'");
    $stmt->execute();
    echo "Updated rows: " . $stmt->rowCount() . "\n";
}
