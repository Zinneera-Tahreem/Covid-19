<?php
$pageTitle = "Citizen Account Dashboard";
require_once __DIR__ . '/db.php';

$currentUser = getCurrentUser();

if (!$currentUser) {
    header('Location: login.php?msg=login_required');
    exit;
}

// Fetch user's registered appointments
$stmtRegs = $db->prepare("SELECT * FROM registrations WHERE user_id = ? OR email = ? ORDER BY id DESC");
$stmtRegs->execute([$currentUser['id'], $currentUser['email']]);
$userRegistrations = $stmtRegs->fetchAll();

// Fetch user's comments
$stmtCmts = $db->prepare("SELECT * FROM comments WHERE user_id = ? OR author_email = ? ORDER BY id DESC");
$stmtCmts->execute([$currentUser['id'], $currentUser['email']]);
$userComments = $stmtCmts->fetchAll();

// If Admin, fetch overall system statistics
$adminStats = null;
if ($currentUser['role'] === 'admin') {
    $totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalRegistrations = $db->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
    $totalComments = $db->query("SELECT COUNT(*) FROM comments")->fetchColumn();
    $adminStats = [
        'users' => $totalUsers,
        'registrations' => $totalRegistrations,
        'comments' => $totalComments
    ];
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 2.5rem 1.5rem;">
  <?php if (isset($_GET['welcome'])): ?>
    <div class="alert alert-success">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
      <span>Welcome to COVIDCare, <strong><?php echo e($currentUser['full_name']); ?></strong>! Your account is active and verified.</span>
    </div>
  <?php endif; ?>

  <!-- Profile Header Card -->
  <div class="card" style="margin-bottom: 2.5rem; background: linear-gradient(145deg, #121c35, #0c1426); border-color: rgba(6,182,212,0.3);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
      <div style="display: flex; align-items: center; gap: 1.25rem;">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #06b6d4, #10b981); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 800; color: #ffffff; box-shadow: 0 4px 20px rgba(6,182,212,0.4);">
          <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
        </div>
        <div>
          <div style="display: flex; align-items: center; gap: 0.6rem;">
            <h1 style="font-size: 1.6rem;"><?php echo e($currentUser['full_name']); ?></h1>
            <span class="badge <?php echo ($currentUser['role'] === 'admin') ? 'badge-purple' : 'badge-pass'; ?>">
              <?php echo strtoupper(e($currentUser['role'])); ?>
            </span>
          </div>
          <div style="font-size: 0.88rem; color: var(--text-muted); margin-top: 0.2rem;">
            <span>Email: <strong class="mono" style="color: #ffffff;"><?php echo e($currentUser['email']); ?></strong></span> · 
            <span>Phone: <strong class="mono" style="color: #ffffff;"><?php echo e($currentUser['phone']); ?></strong></span>
          </div>
        </div>
      </div>

      <div style="display: flex; gap: 0.75rem;">
        <a href="register_vaccine.php" class="btn btn-primary btn-sm">
          + Book New Slot
        </a>
        <a href="comments.php" class="btn btn-outline btn-sm">
          Write Feedback
        </a>
        <a href="logout.php" class="btn btn-outline btn-sm" style="border-color: rgba(244,63,94,0.4); color: #fb7185;">
          Sign Out
        </a>
      </div>
    </div>

    <?php if ($adminStats): ?>
      <div style="margin-top: 1.75rem; padding-top: 1.5rem; border-top: 1px solid var(--border-glass);">
        <div style="font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 0.75rem; letter-spacing: 0.05em;">
          🛡️ Health Officer Administrative Telemetry (MySQL Database Status)
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
          <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-glass);">
            <div style="font-size: 0.75rem; color: var(--text-dim);">Total Registered Accounts</div>
            <div class="mono" style="font-size: 1.5rem; font-weight: 800; color: #38bdf8;"><?php echo $adminStats['users']; ?></div>
          </div>
          <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-glass);">
            <div style="font-size: 0.75rem; color: var(--text-dim);">Total Vaccine Bookings</div>
            <div class="mono" style="font-size: 1.5rem; font-weight: 800; color: #10b981;"><?php echo $adminStats['registrations']; ?></div>
          </div>
          <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-glass);">
            <div style="font-size: 0.75rem; color: var(--text-dim);">Total Community Comments</div>
            <div class="mono" style="font-size: 1.5rem; font-weight: 800; color: #c084fc;"><?php echo $adminStats['comments']; ?></div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- User's Registered Vaccine / RT-PCR Appointments -->
  <div class="card" style="margin-bottom: 2.5rem;">
    <div class="card-header">
      <div>
        <h3 style="font-size: 1.3rem;">My Registered Appointments (<?php echo count($userRegistrations); ?>)</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted);">Vaccination and RT-PCR appointment passes linked to your identity</p>
      </div>
      <a href="register_vaccine.php" class="btn btn-outline btn-sm">+ New Registration</a>
    </div>

    <div class="table-responsive">
      <table class="stqa-table">
        <thead>
          <tr>
            <th>Reference ID</th>
            <th>Service / Dose</th>
            <th>Appointment Date</th>
            <th>Vaccination Center</th>
            <th>Pincode</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($userRegistrations)): ?>
            <tr>
              <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                No appointments booked yet. <a href="register_vaccine.php">Book your first vaccine slot now.</a>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($userRegistrations as $reg): ?>
              <tr>
                <td class="mono" style="color: var(--primary); font-weight: 600;"><?php echo e($reg['registration_ref']); ?></td>
                <td><strong><?php echo e($reg['dose_type']); ?></strong></td>
                <td class="mono"><?php echo e($reg['preferred_date']); ?></td>
                <td><?php echo e($reg['center_name']); ?></td>
                <td class="mono"><?php echo e($reg['pincode']); ?></td>
                <td>
                  <span class="badge <?php echo ($reg['status'] === 'Confirmed') ? 'badge-pass' : 'badge-blue'; ?>">
                    <?php echo e($reg['status']); ?>
                  </span>
                </td>
                <td>
                  <button type="button" class="btn btn-outline btn-sm" onclick="showPassModal(<?php echo htmlspecialchars(json_encode($reg), ENT_QUOTES, 'UTF-8'); ?>)">
                    View Slip
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- User's Submitted Comments -->
  <div class="card">
    <div class="card-header">
      <div>
        <h3 style="font-size: 1.3rem;">My Public Comments & Reviews (<?php echo count($userComments); ?>)</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted);">Your feedback posted to the community board</p>
      </div>
      <a href="comments.php" class="btn btn-outline btn-sm">Community Forum</a>
    </div>

    <div class="table-responsive">
      <table class="stqa-table">
        <thead>
          <tr>
            <th>Category</th>
            <th>Rating</th>
            <th>Message Snippet</th>
            <th>Date Posted</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($userComments)): ?>
            <tr>
              <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                You haven't posted any feedback yet. <a href="comments.php">Share your experience here.</a>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($userComments as $cmt): ?>
              <tr>
                <td><span class="badge badge-purple"><?php echo e($cmt['category']); ?></span></td>
                <td style="color: #f59e0b;"><?php echo str_repeat('★', $cmt['rating']); ?></td>
                <td style="max-width: 400px;"><?php echo e(mb_strimwidth($cmt['comment_text'], 0, 80, '...')); ?></td>
                <td class="mono" style="font-size: 0.8rem;"><?php echo date('M d, Y', strtotime($cmt['created_at'])); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal for Viewing Appointment Pass -->
<div id="passModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 2000; align-items: center; justify-content: center; padding: 1.5rem; backdrop-filter: blur(8px);">
  <div style="background: var(--bg-card); max-width: 650px; width: 100%; border: 1px solid var(--border-glass); border-radius: var(--radius-lg); padding: 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.6);" id="modalPassContent">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
      <h3 style="font-size: 1.4rem;">Digital Appointment Slip</h3>
      <button type="button" class="btn btn-outline btn-sm" onclick="closePassModal()">✕ Close</button>
    </div>
    <div id="modalBody" style="margin-bottom: 1.5rem;"></div>
    <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
      <button type="button" class="btn btn-primary" onclick="window.print()">Print Slip</button>
      <button type="button" class="btn btn-outline" onclick="closePassModal()">Close</button>
    </div>
  </div>
</div>

<script>
function showPassModal(reg) {
  const modal = document.getElementById('passModal');
  const body = document.getElementById('modalBody');
  body.innerHTML = `
    <div style="background: rgba(255,255,255,0.03); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--border-glass);">
      <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
        <div>
          <span style="font-size: 0.75rem; color: var(--text-dim);">Reference No.</span>
          <div class="mono" style="font-size: 1.1rem; color: var(--primary); font-weight: 700;">${reg.registration_ref}</div>
        </div>
        <span class="badge badge-pass">${reg.status}</span>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.9rem;">
        <div><strong>Beneficiary:</strong> <div>${reg.full_name}</div></div>
        <div><strong>Aadhaar / ID:</strong> <div class="mono">${reg.gov_id}</div></div>
        <div><strong>Dose Type:</strong> <div style="color: var(--accent-teal);">${reg.dose_type}</div></div>
        <div><strong>Date:</strong> <div class="mono">${reg.preferred_date}</div></div>
        <div><strong>Center:</strong> <div>${reg.center_name}</div></div>
        <div><strong>Pincode:</strong> <div class="mono">${reg.pincode}</div></div>
      </div>
    </div>
  `;
  modal.style.display = 'flex';
}

function closePassModal() {
  document.getElementById('passModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
