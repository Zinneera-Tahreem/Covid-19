<?php
$pageTitle = "Vaccination & Diagnostic Test Registration";
require_once __DIR__ . '/db.php';

$errors = [];
$bookingSuccess = null;
$currentUser = getCurrentUser();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $gov_id = trim($_POST['gov_id'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $dose_type = trim($_POST['dose_type'] ?? 'Dose 1 (Covishield / Covaxin)');
    $preferred_date = trim($_POST['preferred_date'] ?? '');
    $center_name = trim($_POST['center_name'] ?? 'City Central Government Hospital');
    $pincode = trim($_POST['pincode'] ?? '');
    $medical_conditions = trim($_POST['medical_conditions'] ?? 'None');

    // STQA Server-Side Regex Validations
    if (!preg_match(REGEX_FULL_NAME, $full_name)) {
        $errors['full_name'] = "STQA Regex Failed: Full Name must contain 3-50 alphabetic characters only.";
    }

    if (!preg_match(REGEX_GOV_ID, $gov_id)) {
        $errors['gov_id'] = "STQA Regex Failed: Government ID must be a valid 12-digit number (Aadhaar format, starting 2-9).";
    }

    if (!preg_match(REGEX_EMAIL, $email)) {
        $errors['email'] = "STQA Regex Failed: Invalid Email address format.";
    }

    if (!preg_match(REGEX_PHONE, $phone)) {
        $errors['phone'] = "STQA Regex Failed: Phone number must be 10 digits starting with 6, 7, 8, or 9.";
    }

    if (!preg_match(REGEX_PINCODE, $pincode)) {
        $errors['pincode'] = "STQA Regex Failed: Postal Pincode must be exactly 6 numeric digits (starting 1-9).";
    }

    if (!preg_match(REGEX_DOB, $dob)) {
        $errors['dob'] = "STQA Regex Failed: Date of Birth must follow YYYY-MM-DD format.";
    }

    if (empty($preferred_date)) {
        $errors['preferred_date'] = "Please select a preferred appointment date.";
    }

    // If validation passes, insert into DB
    if (empty($errors)) {
        $ref = 'VAC-' . date('Y') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        $userId = $currentUser ? $currentUser['id'] : null;

        $stmt = $db->prepare("
            INSERT INTO registrations 
            (registration_ref, user_id, full_name, gov_id, email, phone, dob, dose_type, preferred_date, center_name, pincode, medical_conditions, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Confirmed')
        ");
        $stmt->execute([
            $ref, $userId, $full_name, $gov_id, $email, $phone, $dob, $dose_type, $preferred_date, $center_name, $pincode, $medical_conditions
        ]);

        $bookingSuccess = [
            'ref' => $ref,
            'name' => $full_name,
            'gov_id' => $gov_id,
            'email' => $email,
            'phone' => $phone,
            'dose' => $dose_type,
            'date' => $preferred_date,
            'center' => $center_name,
            'pincode' => $pincode,
            'conditions' => $medical_conditions
        ];
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 2.5rem 1.5rem;">
  <?php if ($bookingSuccess): ?>
    <!-- Confirmed Booking Appointment Pass -->
    <div style="max-width: 760px; margin: 0 auto;">
      <div class="alert alert-success" style="margin-bottom: 2rem;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <div>
          <strong style="font-size: 1.05rem;">Vaccine / RT-PCR Slot Confirmed Successfully!</strong>
          <p style="font-size: 0.88rem; margin-top: 0.2rem;">All input vectors validated against STQA regular expression test specifications and stored in MySQL database.</p>
        </div>
      </div>

      <div class="vaccine-pass" id="appointmentPass">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1.5rem;">
          <div>
            <span class="badge badge-pass" style="margin-bottom: 0.5rem;">Official Appointment Pass</span>
            <h2 style="font-size: 1.6rem; color: #ffffff;">Ministry of Health COVID-19 Booking</h2>
            <div style="font-size: 0.82rem; color: var(--text-dim);">Reference ID: <strong class="mono" style="color: var(--primary); font-size: 1rem;"><?php echo e($bookingSuccess['ref']); ?></strong></div>
          </div>
          <div style="text-align: right;">
            <!-- Simulated QR Code SVG -->
            <div style="background: #ffffff; padding: 6px; border-radius: 8px; display: inline-block;">
              <svg width="68" height="68" viewBox="0 0 24 24" fill="#0b1121">
                <path d="M2 2h8v8H2zm2 2v4h4V4zm8-2h8v8h-8zm2 2v4h4V4zM2 14h8v8H2zm2 2v4h4v-4zm10-2h2v2h-2zm4 0h2v2h-2zm-4 4h2v4h-2zm4 2h2v2h-2zm-2-2h2v2h-2z"/>
              </svg>
            </div>
            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.3rem;">Digital Verification QR</div>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
          <div>
            <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-dim);">Beneficiary Name</span>
            <div style="font-size: 1.1rem; font-weight: 700; color: #ffffff;"><?php echo e($bookingSuccess['name']); ?></div>
          </div>
          <div>
            <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-dim);">Aadhaar / National ID</span>
            <div class="mono" style="font-size: 1rem; color: var(--text-main);"><?php echo e($bookingSuccess['gov_id']); ?></div>
          </div>
          <div>
            <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-dim);">Dose / Service</span>
            <div style="font-size: 1rem; font-weight: 600; color: var(--accent-teal);"><?php echo e($bookingSuccess['dose']); ?></div>
          </div>
          <div>
            <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-dim);">Appointment Date</span>
            <div class="mono" style="font-size: 1rem; font-weight: 700; color: #38bdf8;"><?php echo e($bookingSuccess['date']); ?></div>
          </div>
          <div>
            <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-dim);">Healthcare Center</span>
            <div style="font-size: 0.95rem; color: #ffffff;"><?php echo e($bookingSuccess['center']); ?> (PIN: <?php echo e($bookingSuccess['pincode']); ?>)</div>
          </div>
          <div>
            <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-dim);">Registered Contact</span>
            <div style="font-size: 0.9rem; color: var(--text-main);"><?php echo e($bookingSuccess['phone']); ?> | <?php echo e($bookingSuccess['email']); ?></div>
          </div>
        </div>

        <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-glass); margin-bottom: 2rem; font-size: 0.82rem; color: var(--text-muted);">
          ⚠️ <strong>Instructions on Appointment Day:</strong> Please carry original Government ID proof (Aadhaar/Passport). Arrive 15 minutes prior to slot timing.
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;" class="no-print">
          <button type="button" class="btn btn-primary" onclick="window.print()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print Appointment Pass
          </button>
          <a href="register_vaccine.php" class="btn btn-outline">Book Another Slot</a>
          <?php if ($currentUser): ?>
            <a href="dashboard.php" class="btn btn-purple">View In Dashboard</a>
          <?php endif; ?>
        </div>
      </div>
    </div>

  <?php else: ?>
    <!-- Vaccine Registration Form Card -->
    <div class="form-card" style="max-width: 820px;">
      <div style="text-align: center; margin-bottom: 2.5rem;">
        <span class="hero-pill" style="margin-bottom: 0.5rem;">Direct Registration</span>
        <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">COVID-19 Vaccination & RT-PCR Booking</h2>
        <p style="color: var(--text-muted); font-size: 0.92rem;">
          Schedule your dose or diagnostic test. Every input is continuously tested via client and server regular expressions.
        </p>
      </div>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
          <div>
            <strong>STQA Input Validation Errors:</strong>
            <ul style="margin-left: 1.25rem; margin-top: 0.4rem; font-size: 0.85rem;">
              <?php foreach ($errors as $err): ?>
                <li><?php echo e($err); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>

      <form id="vaccineForm" method="POST" action="register_vaccine.php" novalidate>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
          <!-- Full Name -->
          <div class="form-group">
            <label for="full_name" class="form-label">
              <span>Full Name *</span>
              <span class="regex-hint">/^[a-zA-Z\s]{3,50}$/</span>
            </label>
            <input type="text" id="full_name" name="full_name" class="form-control" placeholder="e.g. Aarav Sharma" value="<?php echo e($_POST['full_name'] ?? ($currentUser['full_name'] ?? '')); ?>" required>
            <div id="full_name-feedback" class="feedback-msg"></div>
          </div>

          <!-- Government ID / Aadhaar -->
          <div class="form-group">
            <label for="gov_id" class="form-label">
              <span>Aadhaar / National ID *</span>
              <span class="regex-hint">12 Digits (No 0/1 start)</span>
            </label>
            <input type="text" id="gov_id" name="gov_id" class="form-control" placeholder="e.g. 4589 1234 5678" value="<?php echo e($_POST['gov_id'] ?? ''); ?>" required>
            <div id="gov_id-feedback" class="feedback-msg"></div>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
          <!-- Email -->
          <div class="form-group">
            <label for="email" class="form-label">
              <span>Email Address *</span>
              <span class="regex-hint">RFC 5322 Standard</span>
            </label>
            <input type="email" id="email" name="email" class="form-control" placeholder="e.g. aarav@example.com" value="<?php echo e($_POST['email'] ?? ($currentUser['email'] ?? '')); ?>" required>
            <div id="email-feedback" class="feedback-msg"></div>
          </div>

          <!-- Phone -->
          <div class="form-group">
            <label for="phone" class="form-label">
              <span>Mobile Number *</span>
              <span class="regex-hint">/^[6-9]\d{9}$/</span>
            </label>
            <input type="tel" id="phone" name="phone" class="form-control" placeholder="e.g. 9823456781" value="<?php echo e($_POST['phone'] ?? ($currentUser['phone'] ?? '')); ?>" required>
            <div id="phone-feedback" class="feedback-msg"></div>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
          <!-- Date of Birth -->
          <div class="form-group">
            <label for="dob" class="form-label">
              <span>Date of Birth *</span>
              <span class="regex-hint">YYYY-MM-DD</span>
            </label>
            <input type="date" id="dob" name="dob" class="form-control" value="<?php echo e($_POST['dob'] ?? '1996-05-15'); ?>" required>
            <div id="dob-feedback" class="feedback-msg"></div>
          </div>

          <!-- Dose / Service Type -->
          <div class="form-group">
            <label for="dose_type" class="form-label">
              <span>Service / Dose Type *</span>
            </label>
            <select id="dose_type" name="dose_type" class="form-control">
              <option value="Dose 1 (Covishield / Covaxin)">Dose 1 (Primary Vaccine)</option>
              <option value="Dose 2 (Covishield / Covaxin)">Dose 2 (Completion Dose)</option>
              <option value="Precaution Booster Dose" selected>Precaution Booster Dose</option>
              <option value="RT-PCR Diagnostic Lab Test">RT-PCR Diagnostic Lab Test</option>
              <option value="Rapid Antigen Test (RAT)">Rapid Antigen Test (RAT)</option>
            </select>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
          <!-- Preferred Date -->
          <div class="form-group">
            <label for="preferred_date" class="form-label">
              <span>Preferred Appointment Date *</span>
            </label>
            <input type="date" id="preferred_date" name="preferred_date" class="form-control" value="<?php echo e($_POST['preferred_date'] ?? date('Y-m-d', strtotime('+2 days'))); ?>" min="<?php echo date('Y-m-d'); ?>" required>
          </div>

          <!-- Postal Pincode -->
          <div class="form-group">
            <label for="pincode" class="form-label">
              <span>Area Postal PIN Code *</span>
              <span class="regex-hint">/^[1-9][0-9]{5}$/</span>
            </label>
            <input type="text" id="pincode" name="pincode" class="form-control" placeholder="e.g. 400001" value="<?php echo e($_POST['pincode'] ?? '400001'); ?>" required>
            <div id="pincode-feedback" class="feedback-msg"></div>
          </div>
        </div>

        <!-- Center Selection -->
        <div class="form-group">
          <label for="center_name" class="form-label">
            <span>Select Vaccination Center / Diagnostic Lab *</span>
          </label>
          <select id="center_name" name="center_name" class="form-control">
            <option value="City Central Government Hospital, Ward 4">City Central Government Hospital, Ward 4</option>
            <option value="Apex Healthcare Vaccination Hub, North Wing">Apex Healthcare Vaccination Hub, North Wing</option>
            <option value="Suburban Clinical Diagnostics Center">Suburban Clinical Diagnostics Center</option>
            <option value="Metro Memorial Super Specialty Hospital">Metro Memorial Super Specialty Hospital</option>
            <option value="Community Health Center (CHC), Sector 12">Community Health Center (CHC), Sector 12</option>
          </select>
        </div>

        <!-- Medical Conditions -->
        <div class="form-group">
          <label for="medical_conditions" class="form-label">
            <span>Pre-existing Medical Conditions / Allergies</span>
            <span class="regex-hint">Optional</span>
          </label>
          <input type="text" id="medical_conditions" name="medical_conditions" class="form-control" placeholder="e.g. None or Mild Asthma / Diabetes" value="<?php echo e($_POST['medical_conditions'] ?? 'None'); ?>">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1.05rem;" id="btnSubmitVaccine">
          Confirm Appointment & Generate Digital Pass
        </button>
      </form>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
