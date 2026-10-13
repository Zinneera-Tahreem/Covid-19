<?php
/**
 * Database Connection & Global Configuration
 * COVID-19 Information Portal - STQA Mini Project
 * 
 * Supports primary MySQL database with automated SQLite fallback
 * ensuring 100% operational readiness whether MySQL daemon is active or offline.
 */

// Global Regex Constants for Server-Side Validation (STQA Specification)
define('REGEX_FULL_NAME', '/^[a-zA-Z\s]{3,50}$/');
define('REGEX_EMAIL', '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/');
define('REGEX_PHONE', '/^(\+91[\-\s]?)?[6-9]\d{9}$/');
define('REGEX_PASSWORD', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/');
define('REGEX_GOV_ID', '/^[2-9]{1}[0-9]{3}\s?[0-9]{4}\s?[0-9]{4}$/'); // 12-digit Aadhaar / National ID
define('REGEX_PINCODE', '/^[1-9][0-9]{5}$/'); // 6-digit Pincode
define('REGEX_DOB', '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/');
define('REGEX_COMMENT', '/^[a-zA-Z0-9\s.,!?\'"()\-]{5,500}$/u');

// Start PHP Session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global DB Connection Handler
$pdo = null;
$db_driver = 'unknown';
$db_status_message = '';

function getDBConnection() {
    global $pdo, $db_driver, $db_status_message;

    if ($pdo !== null) {
        return $pdo;
    }

    $mysql_host = '127.0.0.1';
    $mysql_port = '3306';
    $mysql_user = 'root';
    $mysql_pass = '';
    $mysql_dbname = 'covid19_db';

    // 1. Attempt MySQL Connection
    try {
        // Connect to server first to verify / create DB
        $dsn_server = "mysql:host={$mysql_host};port={$mysql_port};charset=utf8mb4";
        $server_pdo = new PDO($dsn_server, $mysql_user, $mysql_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 2,
        ]);
        $server_pdo->exec("CREATE DATABASE IF NOT EXISTS `{$mysql_dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

        // Connect to actual database
        $dsn_db = "mysql:host={$mysql_host};port={$mysql_port};dbname={$mysql_dbname};charset=utf8mb4";
        $pdo = new PDO($dsn_db, $mysql_user, $mysql_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $db_driver = 'mysql';
        $db_status_message = "MySQL Online (Database: {$mysql_dbname})";
        initializeMySQLTables($pdo);
        return $pdo;
    } catch (Exception $e) {
        // MySQL unavailable -> Gracefully fall back to SQLite
        try {
            $sqlite_path = __DIR__ . '/covid19_data.sqlite';
            $pdo = new PDO("sqlite:" . $sqlite_path, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $db_driver = 'sqlite';
            $db_status_message = "SQLite Active (MySQL offline / XAMPP not started yet)";
            initializeSQLiteTables($pdo);
            return $pdo;
        } catch (Exception $sqle) {
            die("Database Fatal Error: " . htmlspecialchars($sqle->getMessage()));
        }
    }
}

/**
 * Initializes MySQL Tables and seed records if not present
 */
function initializeMySQLTables($pdo) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `full_name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(120) NOT NULL UNIQUE,
            `phone` VARCHAR(15) NOT NULL,
            `password_hash` VARCHAR(255) NOT NULL,
            `role` ENUM('user', 'admin') DEFAULT 'user',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS `registrations` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `registration_ref` VARCHAR(20) NOT NULL UNIQUE,
            `user_id` INT NULL,
            `full_name` VARCHAR(100) NOT NULL,
            `gov_id` VARCHAR(20) NOT NULL,
            `email` VARCHAR(120) NOT NULL,
            `phone` VARCHAR(15) NOT NULL,
            `dob` DATE NOT NULL,
            `dose_type` VARCHAR(50) NOT NULL,
            `preferred_date` DATE NOT NULL,
            `center_name` VARCHAR(150) NOT NULL,
            `pincode` VARCHAR(10) NOT NULL,
            `medical_conditions` TEXT DEFAULT NULL,
            `status` ENUM('Confirmed', 'Pending', 'Completed', 'Cancelled') DEFAULT 'Confirmed',
            `registered_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS `comments` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NULL,
            `author_name` VARCHAR(100) NOT NULL,
            `author_email` VARCHAR(120) NOT NULL,
            `category` VARCHAR(50) NOT NULL,
            `rating` INT DEFAULT 5,
            `comment_text` TEXT NOT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    seedDemoDataIfEmpty($pdo, 'mysql');
}

/**
 * Initializes SQLite Tables and seed records
 */
function initializeSQLiteTables($pdo) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            full_name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            phone TEXT NOT NULL,
            password_hash TEXT NOT NULL,
            role TEXT DEFAULT 'user',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS registrations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            registration_ref TEXT NOT NULL UNIQUE,
            user_id INTEGER NULL,
            full_name TEXT NOT NULL,
            gov_id TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT NOT NULL,
            dob TEXT NOT NULL,
            dose_type TEXT NOT NULL,
            preferred_date TEXT NOT NULL,
            center_name TEXT NOT NULL,
            pincode TEXT NOT NULL,
            medical_conditions TEXT DEFAULT NULL,
            status TEXT DEFAULT 'Confirmed',
            registered_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NULL,
            author_name TEXT NOT NULL,
            author_email TEXT NOT NULL,
            category TEXT NOT NULL,
            rating INTEGER DEFAULT 5,
            comment_text TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    seedDemoDataIfEmpty($pdo, 'sqlite');
}

/**
 * Seeds demo accounts and data if tables are empty
 */
function seedDemoDataIfEmpty($pdo, $driver) {
    $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($count == 0) {
        $hashed = password_hash('Covid@2024', PASSWORD_DEFAULT);
        
        // Insert sample users
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Admin Health Officer', 'admin@covidcare.org', '9876543210', $hashed, 'admin']);
        $stmt->execute(['Aarav Sharma', 'aarav.sharma@example.com', '9823456781', $hashed, 'user']);
        $stmt->execute(['Priya Nair', 'priya.nair@example.com', '8765432109', $hashed, 'user']);

        // Insert sample registrations
        $stmtReg = $pdo->prepare("INSERT INTO registrations (registration_ref, user_id, full_name, gov_id, email, phone, dob, dose_type, preferred_date, center_name, pincode, medical_conditions, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtReg->execute(['VAC-2024-8921', 2, 'Aarav Sharma', '4589 1234 5678', 'aarav.sharma@example.com', '9823456781', '1995-04-12', 'Booster Dose', '2026-10-20', 'City Central Government Hospital', '400001', 'None', 'Confirmed']);
        $stmtReg->execute(['VAC-2024-3419', 3, 'Priya Nair', '9123 4567 8901', 'priya.nair@example.com', '8765432109', '1998-11-23', 'Dose 2 (Covishield)', '2026-10-22', 'Apex Healthcare Vaccination Hub', '560001', 'Mild asthma', 'Confirmed']);
        $stmtReg->execute(['VAC-2024-7712', null, 'Rohan Verma', '3322 1144 5566', 'rohan.verma@example.com', '7890123456', '1990-07-15', 'RT-PCR Test', '2026-10-18', 'Suburban Diagnostics Lab', '110001', 'Fever & cough since 2 days', 'Pending']);

        // Insert sample comments
        $stmtCom = $pdo->prepare("INSERT INTO comments (user_id, author_name, author_email, category, rating, comment_text) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtCom->execute([2, 'Aarav Sharma', 'aarav.sharma@example.com', 'Vaccination Experience', 5, 'Smooth and seamless process at City Central Hospital! The verification was quick and side effects were minimal.']);
        $stmtCom->execute([3, 'Priya Nair', 'priya.nair@example.com', 'Safety Protocols', 5, 'Great sanitation standards observed at the center. Temperature checks and social distancing were strictly maintained.']);
        $stmtCom->execute([null, 'Dr. Sunita Kulkarni', 'sunita.k@healthline.org', 'Medical Advice', 5, 'Ensure adequate hydration after taking your booster dose and avoid strenuous workouts for 48 hours.']);
    }
}

// Initialize connection on load
$db = getDBConnection();

/**
 * Helper: check if logged in
 */
function getCurrentUser() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

/**
 * Helper: sanitize output
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
