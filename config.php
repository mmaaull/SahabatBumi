<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Connection Configuration
$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$database = "sahabat_bumi";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset immediately
$conn->set_charset("utf8mb4");

// Compatibility helper: fetch single assoc row from prepared statement
// Some PHP builds (without mysqlnd) don't have mysqli_stmt::get_result()
if (!function_exists('stmt_get_assoc')) {
    function stmt_get_assoc($stmt) {
        // If native get_result is available, use it
        if (method_exists($stmt, 'get_result')) {
            $res = $stmt->get_result();
            return $res ? $res->fetch_assoc() : null;
        }

        // Fallback: bind_result approach
        $stmt->store_result();
        $meta = $stmt->result_metadata();
        if (!$meta) return null;
        $fields = [];
        $row = [];
        while ($field = $meta->fetch_field()) {
            $fields[] = &$row[$field->name];
        }
        call_user_func_array([$stmt, 'bind_result'], $fields);
        if ($stmt->fetch()) {
            // Convert references to values
            foreach ($row as $k => $v) $row[$k] = $v;
            return $row;
        }
        return null;
    }
}

// Create database if not exists
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql_create_db) === TRUE) {
    // Database created or already exists
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
if (!$conn->select_db($database)) {
    die("Error selecting database: " . $conn->error);
}

// Create users table if not exists
$sql_create_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (!$conn->query($sql_create_users)) {
    die("Error creating users table: " . $conn->error);
}

// Create aksi (events) table if not exists
$sql_create_aksi = "CREATE TABLE IF NOT EXISTS aksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_aksi VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    kategori VARCHAR(100) DEFAULT NULL,
    tanggal_mulai DATE DEFAULT NULL,
    tanggal_selesai DATE DEFAULT NULL,
    tanggal_mulai_jam TIME DEFAULT NULL,
    tanggal_selesai_jam TIME DEFAULT NULL,
    lokasi VARCHAR(255) DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (!$conn->query($sql_create_aksi)) {
    die("Error creating aksi table: " . $conn->error);
}

// Create pendaftaran_aksi table if not exists
$sql_create_pendaftaran = "CREATE TABLE IF NOT EXISTS pendaftaran_aksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aksi_id INT NOT NULL,
    user_id INT NOT NULL,
    nama VARCHAR(100) DEFAULT NULL,
    jenis_kelamin VARCHAR(20) DEFAULT NULL,
    umur INT DEFAULT NULL,
    asal_instansi VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_aksi_user (aksi_id, user_id),
    FOREIGN KEY (aksi_id) REFERENCES aksi(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (!$conn->query($sql_create_pendaftaran)) {
    die("Error creating pendaftaran_aksi table: " . $conn->error);
}

// Create laporan table if not exists
$sql_create_laporan = "CREATE TABLE IF NOT EXISTS laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    aksi_id INT DEFAULT NULL,
    title VARCHAR(200) NOT NULL,
    kategori VARCHAR(100) DEFAULT NULL,
    description TEXT,
    image_path VARCHAR(255) DEFAULT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_user_id (user_id),
    KEY idx_aksi_id (aksi_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (aksi_id) REFERENCES aksi(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (!$conn->query($sql_create_laporan)) {
    die("Error creating laporan table: " . $conn->error);
}

// Ensure expected columns exist in `aksi` table (useful if table was created earlier)
function ensure_column_exists($conn, $database, $table, $column, $definition) {
    $db = $conn->real_escape_string($database);
    $tbl = $conn->real_escape_string($table);
    $col = $conn->real_escape_string($column);
    $checkSql = "SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='".$db."' AND TABLE_NAME='".$tbl."' AND COLUMN_NAME='".$col."'";
    $res = $conn->query($checkSql);
    if ($res) {
        $r = $res->fetch_assoc();
        if (isset($r['cnt']) && (int)$r['cnt'] === 0) {
            $alter = "ALTER TABLE `".$tbl."` ADD COLUMN `".$col."` ".$definition;
            if (!$conn->query($alter)) {
                error_log("Failed to add column $col to $tbl: " . $conn->error);
            }
        }
    }
}

// Columns used by daftar-event.php — add them if missing
$expectedCols = [
    'kategori' => "VARCHAR(100) DEFAULT NULL",
    'tanggal_mulai' => "DATE DEFAULT NULL",
    'tanggal_selesai' => "DATE DEFAULT NULL",
    'tanggal_mulai_jam' => "TIME DEFAULT NULL",
    'tanggal_selesai_jam' => "TIME DEFAULT NULL",
    'lokasi' => "VARCHAR(255) DEFAULT NULL",
    'image_path' => "VARCHAR(255) DEFAULT NULL"
];
foreach ($expectedCols as $col => $def) {
    ensure_column_exists($conn, $database, 'aksi', $col, $def);
}

// Ensure `role` column exists in `users` table (added here for backward compatibility)
ensure_column_exists($conn, $database, 'users', 'role', "ENUM('user','admin') DEFAULT 'user'");

// Create uploads folder if not exists
$uploadsDir = __DIR__ . '/uploads';
if (!is_dir($uploadsDir)) {
    if (!mkdir($uploadsDir, 0777, true)) {
        // Folder creation failed, but don't die - log it
        // Users can still try to upload, and unggah.php will handle the error
    }
}

// Make sure uploads folder is writable
if (is_dir($uploadsDir) && !is_writable($uploadsDir)) {
    chmod($uploadsDir, 0777);
}
?>
