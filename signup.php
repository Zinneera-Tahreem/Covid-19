<?php
$pageTitle = "Create Citizen Health Account";
require_once __DIR__ . '/db.php';

$errors = [];
$successMsg = '';

// Handle Registration POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // STQA Server-Side Regular Expression Validations
    if (!preg_match(REGEX_FULL_NAME, $full_name)) {
        $errors['full_name'] = "STQA Regex Failed: Name must contain only letters and spaces (3-50 chars).";
    }

    if (!preg_match(REGEX_EMAIL, $email)) {
        $errors['email'] = "STQA Regex Failed: Please provide a valid email format (e.g., user@domain.com).";
    }

    if (!preg_match(REGEX_PHONE, $phone)) {
        $errors['phone'] = "STQA Regex Failed: Mobile number must be 10 digits starting with 6, 7, 8, or 9.";
    }

    if (!preg_match(REGEX_PASSWORD, $password)) {
        $errors['password'] = "STQA Regex Failed: Password must be 8-20 characters with at least 1 uppercase, 1 lowercase, 1 number, and 1 special symbol (@$!%*?&).";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Password confirmation does not match.";
    }

    // Check if email already registered
    if (empty($errors)) {
        $stmtCheck = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmtCheck->execute([$email]);
        if ($stmtCheck->fetch()) {
            $errors['email'] = "An account with this email address already exists. Please log in.";
        } else {
            // Insert new user
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmtInsert = $db->prepare("INSERT INTO users (full_name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, 'user')");
            $stmtInsert->execute([$full_name, $email, $phone, $hashed]);

            $newUserId = $db->lastInsertId();
            $_SESSION['user'] = [
                'id' => $newUserId,
                'full_name' => $full_name,
                'email' => $email,
                'phone' => $phone,
                'role' => 'user'
            ];
            header('Location: dashboard.php?welcome=1');
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 2.5rem 1.5rem;">
  <div class="form-card">
    <div style="text-align: center; margin-bottom: 2rem;">
      <span class="hero-pill" style="margin-bottom: 0.5rem;">User Registration</span>
      <h2 style="font-size: 1.85rem; margin-bottom: 0.5rem;">Create Your COVIDCare Account</h2>
      <p style="color: var(--text-muted); font-size: 0.9rem;">
        Protected with client-side & server-side STQA Regular Expression verification.
      </p>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <div>
          <strong>Validation Notice:</strong>
          <ul style="margin-left: 1.25rem; margin-top: 0.4rem; font-size: 0.85rem;">
            <?php foreach ($errors as $err): ?>
              <li><?php echo e($err); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <form id="signupForm" method="POST" action="signup.php" novalidate>
      <div class="form-group">
        <label for="full_name" class="form-label">
          <span>Full Legal Name *</span>
          <span class="regex-hint">/^[a-zA-Z\s]{3,50}$/</span>
        </label>
        <input type="text" id="full_name" name="full_name" class="form-control" placeholder="e.g. John Alexander Doe" value="<?php echo e($_POST['full_name'] ?? ''); ?>" required>
        <div id="full_name-feedback" class="feedback-msg"></div>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">
          <span>Email Address *</span>
          <span class="regex-hint">RFC 5322 Email Regex</span>
        </label>
        <input type="email" id="email" name="email" class="form-control" placeholder="e.g. citizen@domain.org" value="<?php echo e($_POST['email'] ?? ''); ?>" required>
        <div id="email-feedback" class="feedback-msg"></div>
      </div>

      <div class="form-group">
        <label for="phone" class="form-label">
          <span>Mobile Phone Number *</span>
          <span class="regex-hint">/^[6-9]\d{9}$/</span>
        </label>
        <input type="tel" id="phone" name="phone" class="form-control" placeholder="e.g. 9876543210" value="<?php echo e($_POST['phone'] ?? ''); ?>" required>
        <div id="phone-feedback" class="feedback-msg"></div>
      </div>

      <div class="form-group">
        <label for="password" class="form-label">
          <span>Create Password *</span>
          <span class="regex-hint">Min 8 chars, Aa1@ rule</span>
        </label>
        <input type="password" id="password" name="password" class="form-control" placeholder="e.g. Strong@2024" required>
        <div class="password-meter-wrap">
          <div class="password-meter-bar">
            <div id="password-meter-fill" class="password-meter-fill"></div>
          </div>
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.25rem;">
            <span id="password-meter-text" style="font-size: 0.72rem; color: var(--text-dim);">Strength: None</span>
            <span style="font-size: 0.72rem; color: var(--text-dim);">Requires: uppercase, lowercase, digit, symbol</span>
          </div>
        </div>
        <div id="password-feedback" class="feedback-msg"></div>
      </div>

      <div class="form-group">
        <label for="confirm_password" class="form-label">
          <span>Confirm Password *</span>
        </label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Re-enter your password" required>
        <div id="confirm_password-feedback" class="feedback-msg"></div>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.75rem; padding: 0.85rem;" id="btnSignupSubmit">
        Create Verified Account
      </button>

      <div style="text-align: center; margin-top: 1.5rem; font-size: 0.88rem; color: var(--text-muted);">
        Already have a citizen account? <a href="login.php" style="font-weight: 600;">Sign in here</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
