/**
 * STQA Regular Expression Validation Engine
 * Software Testing & Quality Assurance Mini Project
 * COVID-19 Information & Management Portal
 */

// Centralized STQA Regex Rules Matrix
const STQA_REGEX = {
  fullName: {
    pattern: /^[a-zA-Z\s]{3,50}$/,
    description: "Alphabetic characters and spaces only (Length: 3 to 50 characters)",
    error: "Name must contain only letters and spaces (3-50 chars). Numbers and special symbols not permitted."
  },
  email: {
    pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    description: "Standard RFC 5322 compliant email address",
    error: "Please enter a valid email address (e.g., citizen@health.org)."
  },
  phone: {
    pattern: /^(\+91[\-\s]?)?[6-9]\d{9}$/,
    description: "10-digit mobile number starting with 6, 7, 8, or 9 (optional +91 prefix)",
    error: "Enter a valid 10-digit mobile number starting with 6-9."
  },
  password: {
    pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/,
    description: "Strong security rule: 8-20 chars, min 1 uppercase, 1 lowercase, 1 digit, 1 special character (@$!%*?&)",
    error: "Password must be 8-20 characters with at least 1 uppercase, 1 lowercase, 1 number, and 1 special symbol (@$!%*?&)."
  },
  govId: {
    pattern: /^[2-9]{1}[0-9]{3}\s?[0-9]{4}\s?[0-9]{4}$/,
    description: "12-digit Indian Aadhaar ID format (first digit 2-9, optional spaces)",
    error: "Invalid Government ID. Must be a 12-digit number (cannot start with 0 or 1)."
  },
  pincode: {
    pattern: /^[1-9][0-9]{5}$/,
    description: "6-digit Indian Postal PIN Code (first digit 1-9)",
    error: "Pincode must be exactly 6 digits and cannot begin with 0."
  },
  dob: {
    pattern: /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/,
    description: "ISO Date format YYYY-MM-DD",
    error: "Date must follow YYYY-MM-DD format."
  },
  comment: {
    pattern: /^[a-zA-Z0-9\s.,!?\'"()\-]{5,500}$/,
    description: "Safe text message (5 to 500 chars), sanitizing script tags and HTML injection",
    error: "Feedback message must be between 5 and 500 characters without illegal script code."
  }
};

/**
 * Validates an input field against a regex and renders visual status
 */
function validateInput(input, ruleKey, customError = null) {
  if (!input) return false;
  const value = input.value.trim();
  const rule = STQA_REGEX[ruleKey];
  if (!rule) return true;

  const errorContainer = document.getElementById(input.id + '-feedback');
  const isValid = rule.pattern.test(value);

  if (isValid) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
    if (errorContainer) {
      errorContainer.textContent = '✓ Valid format verified';
      errorContainer.className = 'feedback-msg success';
    }
    return true;
  } else {
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');
    if (errorContainer) {
      errorContainer.textContent = customError || rule.error;
      errorContainer.className = 'feedback-msg error';
    }
    return false;
  }
}

/**
 * Password Strength Meter Evaluator
 */
function evaluatePasswordStrength(password) {
  let score = 0;
  if (!password) return { score: 0, text: 'None', color: 'transparent', width: '0%' };

  if (password.length >= 8) score += 1;
  if (/[a-z]/.test(password)) score += 1;
  if (/[A-Z]/.test(password)) score += 1;
  if (/[0-9]/.test(password)) score += 1;
  if (/[@$!%*?&]/.test(password)) score += 1;

  if (score <= 2) {
    return { score, text: 'Weak', color: '#f43f5e', width: '25%' };
  } else if (score === 3 || score === 4) {
    return { score, text: 'Moderate', color: '#f59e0b', width: '65%' };
  } else {
    return { score, text: 'Strong (Compliant)', color: '#10b981', width: '100%' };
  }
}

/**
 * Form Initialization & Event Listeners
 */
document.addEventListener('DOMContentLoaded', () => {

  // 1. Live Validation Bindings for Vaccine Registration Form
  const vaccineForm = document.getElementById('vaccineForm');
  if (vaccineForm) {
    const nameInput = document.getElementById('full_name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const govIdInput = document.getElementById('gov_id');
    const pincodeInput = document.getElementById('pincode');
    const dobInput = document.getElementById('dob');

    if (nameInput) nameInput.addEventListener('input', () => validateInput(nameInput, 'fullName'));
    if (emailInput) emailInput.addEventListener('input', () => validateInput(emailInput, 'email'));
    if (phoneInput) phoneInput.addEventListener('input', () => validateInput(phoneInput, 'phone'));
    if (govIdInput) govIdInput.addEventListener('input', () => validateInput(govIdInput, 'govId'));
    if (pincodeInput) pincodeInput.addEventListener('input', () => validateInput(pincodeInput, 'pincode'));
    if (dobInput) dobInput.addEventListener('change', () => validateInput(dobInput, 'dob'));

    vaccineForm.addEventListener('submit', (e) => {
      let isAllValid = true;
      if (nameInput && !validateInput(nameInput, 'fullName')) isAllValid = false;
      if (emailInput && !validateInput(emailInput, 'email')) isAllValid = false;
      if (phoneInput && !validateInput(phoneInput, 'phone')) isAllValid = false;
      if (govIdInput && !validateInput(govIdInput, 'govId')) isAllValid = false;
      if (pincodeInput && !validateInput(pincodeInput, 'pincode')) isAllValid = false;
      if (dobInput && !validateInput(dobInput, 'dob')) isAllValid = false;

      if (!isAllValid) {
        e.preventDefault();
        const firstInvalid = vaccineForm.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.focus();
      }
    });
  }

  // 2. Sign Up Form Validation
  const signupForm = document.getElementById('signupForm');
  if (signupForm) {
    const nameInput = document.getElementById('full_name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const meterFill = document.getElementById('password-meter-fill');
    const meterLabel = document.getElementById('password-meter-text');

    if (nameInput) nameInput.addEventListener('input', () => validateInput(nameInput, 'fullName'));
    if (emailInput) emailInput.addEventListener('input', () => validateInput(emailInput, 'email'));
    if (phoneInput) phoneInput.addEventListener('input', () => validateInput(phoneInput, 'phone'));

    if (passwordInput) {
      passwordInput.addEventListener('input', () => {
        validateInput(passwordInput, 'password');
        const strength = evaluatePasswordStrength(passwordInput.value);
        if (meterFill) {
          meterFill.style.width = strength.width;
          meterFill.style.backgroundColor = strength.color;
        }
        if (meterLabel) {
          meterLabel.textContent = 'Strength: ' + strength.text;
          meterLabel.style.color = strength.color;
        }
        // Also re-check confirm password if already entered
        if (confirmInput && confirmInput.value) {
          checkConfirmPassword();
        }
      });
    }

    function checkConfirmPassword() {
      const match = confirmInput.value === passwordInput.value;
      const errorContainer = document.getElementById('confirm_password-feedback');
      if (match && confirmInput.value.length > 0) {
        confirmInput.classList.remove('is-invalid');
        confirmInput.classList.add('is-valid');
        if (errorContainer) {
          errorContainer.textContent = '✓ Passwords match';
          errorContainer.className = 'feedback-msg success';
        }
        return true;
      } else {
        confirmInput.classList.remove('is-valid');
        confirmInput.classList.add('is-invalid');
        if (errorContainer) {
          errorContainer.textContent = 'Passwords do not match.';
          errorContainer.className = 'feedback-msg error';
        }
        return false;
      }
    }

    if (confirmInput) {
      confirmInput.addEventListener('input', checkConfirmPassword);
    }

    signupForm.addEventListener('submit', (e) => {
      let isAllValid = true;
      if (nameInput && !validateInput(nameInput, 'fullName')) isAllValid = false;
      if (emailInput && !validateInput(emailInput, 'email')) isAllValid = false;
      if (phoneInput && !validateInput(phoneInput, 'phone')) isAllValid = false;
      if (passwordInput && !validateInput(passwordInput, 'password')) isAllValid = false;
      if (confirmInput && !checkConfirmPassword()) isAllValid = false;

      if (!isAllValid) {
        e.preventDefault();
        const firstInvalid = signupForm.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.focus();
      }
    });
  }

  // 3. Comments / Feedback Form Validation
  const commentForm = document.getElementById('commentForm');
  if (commentForm) {
    const authorInput = document.getElementById('author_name');
    const emailInput = document.getElementById('author_email');
    const textInput = document.getElementById('comment_text');

    if (authorInput) authorInput.addEventListener('input', () => validateInput(authorInput, 'fullName'));
    if (emailInput) emailInput.addEventListener('input', () => validateInput(emailInput, 'email'));
    if (textInput) textInput.addEventListener('input', () => validateInput(textInput, 'comment'));

    commentForm.addEventListener('submit', (e) => {
      let isAllValid = true;
      if (authorInput && !validateInput(authorInput, 'fullName')) isAllValid = false;
      if (emailInput && !validateInput(emailInput, 'email')) isAllValid = false;
      if (textInput && !validateInput(textInput, 'comment')) isAllValid = false;

      if (!isAllValid) {
        e.preventDefault();
        const firstInvalid = commentForm.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.focus();
      }
    });
  }
});
