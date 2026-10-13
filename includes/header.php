<?php
/**
 * Shared Header Template
 * COVID-19 Portal & STQA Testing Suite
 */
require_once __DIR__ . '/../db.php';
$currentUser = getCurrentUser();
$activePage = basename($_SERVER['PHP_SELF'], '.php');
$pageTitle = isset($pageTitle) ? $pageTitle . " | COVIDCare Portal" : "COVIDCare | National COVID-19 Information & Vaccine Management Portal";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Dynamic COVID-19 Information and Vaccination Portal with MySQL database management and Software Testing & Quality Assurance (STQA) Regular Expression validation suite.">
  <title><?php echo e($pageTitle); ?></title>
  
  <link rel="stylesheet" href="css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>

  <!-- Top System & Helpline Status Bar -->
  <div class="status-bar">
    <div class="container">
      <div class="status-indicator">
        <span class="status-dot <?php echo ($db_driver === 'mysql') ? '' : 'amber'; ?>"></span>
        <span>Database: <strong><?php echo e($db_status_message); ?></strong></span>
      </div>
      <div>
        <span>24x7 Emergency Helpline: <strong class="mono" style="color: var(--primary);">1075</strong> | Toll-Free: <strong class="mono" style="color: var(--primary);">+91-11-23978046</strong></span>
      </div>
    </div>
  </div>

  <!-- Primary Navigation -->
  <nav class="navbar" id="mainNavbar">
    <div class="container nav-container">
      <a href="index.php" class="brand-logo" id="nav-brand">
        <span class="brand-badge">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
          </svg>
        </span>
        <span>COVID<strong>Care</strong></span>
        <span class="brand-tag">STQA Mini Project</span>
      </a>

      <ul class="nav-links">
        <li>
          <a href="index.php" class="nav-link <?php echo ($activePage === 'index') ? 'active' : ''; ?>" id="nav-home">
            Live Tracker & Guidelines
          </a>
        </li>
        <li>
          <a href="register_vaccine.php" class="nav-link <?php echo ($activePage === 'register_vaccine') ? 'active' : ''; ?>" id="nav-register">
            Vaccine / RT-PCR Booking
          </a>
        </li>
        <li>
          <a href="comments.php" class="nav-link <?php echo ($activePage === 'comments') ? 'active' : ''; ?>" id="nav-comments">
            Community Forum
          </a>
        </li>
        <li>
          <a href="stqa_tests.php" class="nav-link stqa-badge <?php echo ($activePage === 'stqa_tests') ? 'active' : ''; ?>" id="nav-stqa">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path d="m9 11 3 3L22 4"></path>
              <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
            Regex Test Suite
          </a>
        </li>
      </ul>

      <div class="nav-actions">
        <?php if ($currentUser): ?>
          <a href="dashboard.php" class="btn btn-outline btn-sm" id="nav-dashboard">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <?php echo e(explode(' ', $currentUser['full_name'])[0]); ?>
          </a>
          <a href="logout.php" class="btn btn-outline btn-sm" id="nav-logout" style="border-color: rgba(244,63,94,0.4); color: #fb7185;">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline btn-sm" id="nav-login">Sign In</a>
          <a href="signup.php" class="btn btn-primary btn-sm" id="nav-signup">Register Account</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <main style="flex: 1 0 auto;">
