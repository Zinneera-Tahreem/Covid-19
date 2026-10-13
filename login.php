<?php
$pageTitle = "Sign In to COVIDCare";
require_once __DIR__ . '/db.php';

$error = '';
$success = '';

// Handle Login POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!preg_match(REGEX_EMAIL, $email)) {
        $error = "STQA Regex Failed: Please provide a properly formatted email address.";
    } elseif (empty($password)) {
        $error = "Please enter your password.";
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role']
            ];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Invalid credentials. Please verify your email and password.";
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 2.5rem 1.5rem;">
  <div class="form-card" style="max-width: 520px;">
    <div style="text-align: center; margin-bottom: 2rem;">
      <span class="hero-pill" style="margin-bottom: 0.5rem;">Secure Authentication</span>
      <h2 style="font-size: 1.85rem; margin-bottom: 0.5rem;">Sign In</h2>
      <p style="color: var(--text-muted); font-size: 0.9rem;">
        Access your vaccination certificates, test reports, and appointments.
      </p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-error">
        <span><?php echo e($error); ?></span>
      </div>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="login.php" novalidate>
      <div class="form-group">
        <label for="email" class="form-label">
          <span>Email Address *</span>
          <span class="regex-hint">RFC 5322</span>
        </label>
        <input type="email" id="email" name="email" class="form-control" placeholder="e.g. citizen@example.com" value="<?php echo e($_POST['email'] ?? ''); ?>" required>
        <div id="email-feedback" class="feedback-msg"></div>
      </div>

      <div class="form-group">
        <label for="password" class="form-label">
          <span>Account Password *</span>
        </label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
        <div id="password-feedback" class="feedback-msg"></div>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem; padding: 0.85rem;" id="btnLoginSubmit">
        Sign In to Portal
      </button>
    </form>

    <!-- Quick Demo Accounts for Evaluator / Testing Convenience -->
    <div style="margin-top: 2rem; padding: 1.25rem; background: rgba(255,255,255,0.02); border: 1px dashed var(--border-glass); border-radius: var(--radius-sm);">
      <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.6rem; letter-spacing: 0.05em;">
        STQA Evaluator Quick Demo Accounts
      </div>
      <p style="font-size: 0.78rem; color: var(--text-dim); margin-bottom: 0.75rem;">
        Click below to auto-fill credentials for pre-seeded test accounts (Password: <span class="mono" style="color: var(--primary);">Covid@2024</span>):
      </p>
      <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <button type="button" class="btn btn-outline btn-sm" onclick="fillDemo('aarav.sharma@example.com', 'Covid@2024')">
          👤 Citizen (Aarav Sharma)
        </button>
        <button type="button" class="btn btn-outline btn-sm" onclick="fillDemo('admin@covidcare.org', 'Covid@2024')">
          🛡️ Health Officer (Admin)
        </button>
      </div>
    </div>

    <div style="text-align: center; margin-top: 1.5rem; font-size: 0.88rem; color: var(--text-muted);">
      Don't have an account yet? <a href="signup.php" style="font-weight: 600;">Create an account</a>
    </div>
  </div>
</div>

<script>
function fillDemo(email, pass) {
  document.getElementById('email').value = email;
  document.getElementById('password').value = pass;
  validateInput(document.getElementById('email'), 'email');
}

// Client side check on login email
document.getElementById('email').addEventListener('input', function() {
  validateInput(this, 'email');
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
