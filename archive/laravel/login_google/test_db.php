<?php
/**
 * File untuk test koneksi database
 * Akses file ini melalui browser untuk memastikan koneksi database berfungsi
 */

require_once __DIR__ . '/db_config.php';

echo "<h2>Test Koneksi Database</h2>";

// Test koneksi
$conn = getDBConnection();

if ($conn) {
    echo "<p style='color: green;'>✅ Koneksi database berhasil!</p>";
    
    // Test query
    $result = mysqli_query($conn, "SELECT DATABASE() as db_name");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "<p><strong>Database yang digunakan:</strong> " . $row['db_name'] . "</p>";
    }
    
    // Cek apakah tabel users ada
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
    if (mysqli_num_rows($result) > 0) {
        echo "<p style='color: green;'>✅ Tabel 'users' ditemukan!</p>";
        
        // Tampilkan struktur tabel
        $result = mysqli_query($conn, "DESCRIBE users");
        echo "<h3>Struktur Tabel Users:</h3>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Hitung jumlah user
        $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
        $row = mysqli_fetch_assoc($result);
        echo "<p><strong>Total user di database:</strong> " . $row['total'] . "</p>";
    } else {
        echo "<p style='color: red;'>❌ Tabel 'users' tidak ditemukan!</p>";
        echo "<p>Silakan import file <code>database.sql</code> untuk membuat tabel.</p>";
    }
    
    closeDBConnection($conn);
} else {
    echo "<p style='color: red;'>❌ Koneksi database gagal!</p>";
    echo "<p>Periksa konfigurasi di file <code>db_config.php</code></p>";
}

echo "<hr>";
echo "<p><a href='index.php'>Kembali ke Halaman Utama</a></p>";

