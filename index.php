<?php
$pageTitle = "National COVID-19 Surveillance & Response Hub";
require_once __DIR__ . '/includes/header.php';
?>

<div class="hero">
  <div class="container">
    <div class="hero-grid">
      <div>
        <div class="hero-pill">
          <span style="width: 8px; height: 8px; background: var(--accent-teal); border-radius: 50%;"></span>
          <span>Official COVID-19 Response & Testing Portal</span>
        </div>
        <h1 class="hero-title">
          Combat COVID-19 with <span class="gradient-text">Accurate Data & Timely Vaccination</span>
        </h1>
        <p class="hero-desc">
          Monitor real-time pandemic analytics, book certified vaccine slots, test your symptoms, and experience robust Software Testing & Quality Assurance (STQA) with regular expression validation.
        </p>
        <div class="hero-cta">
          <a href="register_vaccine.php" class="btn btn-primary btn-lg" id="hero-btn-book">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Book Vaccine / RT-PCR
          </a>
          <a href="stqa_tests.php" class="btn btn-purple btn-lg" id="hero-btn-stqa">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 11 3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
            STQA Regex Test Suite
          </a>
          <a href="#symptom-checker" class="btn btn-outline btn-lg" id="hero-btn-symptoms">
            Symptom Checker
          </a>
        </div>

        <div style="display: flex; gap: 2rem; color: var(--text-dim); font-size: 0.85rem;">
          <div><strong style="color: var(--text-main);">100%</strong> STQA Test Coverage</div>
          <div><strong style="color: var(--text-main);">Dual-Layer</strong> Regex Engine</div>
          <div><strong style="color: var(--text-main);">Instant</strong> Digital Slot Passes</div>
        </div>
      </div>

      <div>
        <div class="card" style="border: 1px solid rgba(6,182,212,0.3); background: rgba(14,21,38,0.9);">
          <div class="card-header">
            <div>
              <h3 style="font-size: 1.15rem; margin-bottom: 0.2rem;">Quick Advisory & Triage</h3>
              <span style="font-size: 0.8rem; color: var(--text-muted);">Ministry of Health Guidelines</span>
            </div>
            <span class="badge badge-blue">Updated Daily</span>
          </div>
          <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.9rem;">
            <div style="display: flex; gap: 0.75rem; align-items: flex-start; padding: 0.75rem; background: rgba(255,255,255,0.02); border-radius: var(--radius-sm);">
              <span style="font-size: 1.3rem;">💉</span>
              <div>
                <strong>Booster Dose (Precaution)</strong>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Available for all eligible citizens 6 months post 2nd dose.</p>
              </div>
            </div>
            <div style="display: flex; gap: 0.75rem; align-items: flex-start; padding: 0.75rem; background: rgba(255,255,255,0.02); border-radius: var(--radius-sm);">
              <span style="font-size: 1.3rem;">🧪</span>
              <div>
                <strong>RT-PCR Reports in 24 Hours</strong>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Government recognized QR-coded digital test certificates.</p>
              </div>
            </div>
            <div style="display: flex; gap: 0.75rem; align-items: flex-start; padding: 0.75rem; background: rgba(255,255,255,0.02); border-radius: var(--radius-sm);">
              <span style="font-size: 1.3rem;">🛡️</span>
              <div>
                <strong>Rigorous Input Sanitization</strong>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Protected by STQA tested regex against SQLi and XSS.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<section style="padding: 1.5rem 0 3.5rem;">
  <div class="container">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.75rem;">
      <div>
        <span class="hero-pill" style="margin-bottom: 0.5rem;">Live Telemetry</span>
        <h2 style="font-size: 1.85rem;">National Pandemic Overview</h2>
      </div>
      <span style="font-size: 0.82rem; color: var(--text-dim);" class="mono">Source: National Health Surveillance Bureau</span>
    </div>

    <!-- Stats Counter Grid -->
    <div class="stats-grid">
      <div class="stat-card confirmed">
        <div class="stat-label">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          Total Confirmed
        </div>
        <div class="stat-number" style="color: #38bdf8;">44,998,210</div>
        <div class="stat-meta">↑ +1,240 in last 24 hrs</div>
      </div>

      <div class="stat-card active">
        <div class="stat-label">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
          Active Cases
        </div>
        <div class="stat-number" style="color: #f59e0b;">18,452</div>
        <div class="stat-meta">0.04% of total caseload</div>
      </div>

      <div class="stat-card recovered">
        <div class="stat-label">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
          Recovered
        </div>
        <div class="stat-number" style="color: #10b981;">44,446,512</div>
        <div class="stat-meta">98.81% Recovery Rate</div>
      </div>

      <div class="stat-card fatalities">
        <div class="stat-label">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2.5"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          Total Fatalities
        </div>
        <div class="stat-number" style="color: #f43f5e;">533,246</div>
        <div class="stat-meta">1.18% Case Fatality Rate</div>
      </div>

      <div class="stat-card vaccinated">
        <div class="stat-label">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c084fc" stroke-width="2.5"><path d="m18 2 4 4"></path><path d="m17 7 3-3"></path><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-1.6-1.6c-1-1-1-2.5 0-3.4L14 4"></path></svg>
          Doses Given
        </div>
        <div class="stat-number" style="color: #c084fc;">2,206,750,140</div>
        <div class="stat-meta">Over 94% fully vaccinated</div>
      </div>
    </div>

    <!-- State & Region Breakdown Table with Live Search Filter -->
    <div class="card" style="margin-bottom: 3.5rem;">
      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
          <h3 style="font-size: 1.25rem;">State-wise COVID-19 Surveillance Registry</h3>
          <p style="font-size: 0.85rem; color: var(--text-muted);">Real-time monitoring across major states and healthcare zones</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
          <input type="text" id="stateSearchInput" class="form-control" placeholder="Search state or zone..." style="width: 260px; padding: 0.5rem 0.85rem; font-size: 0.88rem;">
        </div>
      </div>

      <div class="table-responsive">
        <table class="stqa-table" id="statesTable">
          <thead>
            <tr>
              <th>State / UT</th>
              <th>Confirmed</th>
              <th>Active Cases</th>
              <th>Recovered</th>
              <th>Deaths</th>
              <th>Vaccine Coverage</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Maharashtra</strong></td>
              <td class="mono">8,171,940</td>
              <td class="mono" style="color: #f59e0b;">3,420</td>
              <td class="mono" style="color: #10b981;">8,020,012</td>
              <td class="mono" style="color: #f43f5e;">148,508</td>
              <td><span class="badge badge-pass">96.4%</span></td>
              <td><span class="badge badge-pass">Controlled</span></td>
            </tr>
            <tr>
              <td><strong>Kerala</strong></td>
              <td class="mono">6,908,120</td>
              <td class="mono" style="color: #f59e0b;">2,890</td>
              <td class="mono" style="color: #10b981;">6,833,280</td>
              <td class="mono" style="color: #f43f5e;">71,950</td>
              <td><span class="badge badge-pass">98.1%</span></td>
              <td><span class="badge badge-pass">Controlled</span></td>
            </tr>
            <tr>
              <td><strong>Karnataka</strong></td>
              <td class="mono">4,089,720</td>
              <td class="mono" style="color: #f59e0b;">1,640</td>
              <td class="mono" style="color: #10b981;">4,047,780</td>
              <td class="mono" style="color: #f43f5e;">40,300</td>
              <td><span class="badge badge-pass">95.8%</span></td>
              <td><span class="badge badge-pass">Controlled</span></td>
            </tr>
            <tr>
              <td><strong>Tamil Nadu</strong></td>
              <td class="mono">3,610,950</td>
              <td class="mono" style="color: #f59e0b;">1,210</td>
              <td class="mono" style="color: #10b981;">3,571,680</td>
              <td class="mono" style="color: #f43f5e;">38,060</td>
              <td><span class="badge badge-pass">94.7%</span></td>
              <td><span class="badge badge-pass">Controlled</span></td>
            </tr>
            <tr>
              <td><strong>Delhi (NCT)</strong></td>
              <td class="mono">2,040,890</td>
              <td class="mono" style="color: #f59e0b;">940</td>
              <td class="mono" style="color: #10b981;">2,013,270</td>
              <td class="mono" style="color: #f43f5e;">26,680</td>
              <td><span class="badge badge-pass">99.2%</span></td>
              <td><span class="badge badge-pass">Controlled</span></td>
            </tr>
            <tr>
              <td><strong>Uttar Pradesh</strong></td>
              <td class="mono">2,145,300</td>
              <td class="mono" style="color: #f59e0b;">830</td>
              <td class="mono" style="color: #10b981;">2,120,770</td>
              <td class="mono" style="color: #f43f5e;">23,700</td>
              <td><span class="badge badge-pass">93.5%</span></td>
              <td><span class="badge badge-pass">Controlled</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Interactive Symptom Checker Section -->
    <div id="symptom-checker" class="card" style="margin-bottom: 3.5rem; background: linear-gradient(145deg, #121c35, #0c1426); border-color: rgba(6,182,212,0.3);">
      <div style="margin-bottom: 1.5rem;">
        <span class="hero-pill" style="margin-bottom: 0.5rem;">Clinical Triage Tool</span>
        <h2 style="font-size: 1.75rem;">Interactive COVID-19 Risk & Symptom Assessment</h2>
        <p style="color: var(--text-muted); font-size: 0.92rem;">Answer 3 quick questions to calculate your risk category and medical recommendation.</p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
        <div class="quiz-box">
          <h4 style="font-size: 1rem; margin-bottom: 0.75rem; color: #38bdf8;">1. Symptoms Experienced:</h4>
          <label class="option-chip"><input type="checkbox" name="symp" value="fever"> Fever / Chills (100°F+)</label>
          <label class="option-chip"><input type="checkbox" name="symp" value="cough"> Persistent Dry Cough</label>
          <label class="option-chip"><input type="checkbox" name="symp" value="breath"> Shortness of Breath</label>
          <label class="option-chip"><input type="checkbox" name="symp" value="taste"> Loss of Taste or Smell</label>
          <label class="option-chip"><input type="checkbox" name="symp" value="fatigue"> Extreme Fatigue / Body Aches</label>
        </div>

        <div class="quiz-box">
          <h4 style="font-size: 1rem; margin-bottom: 0.75rem; color: #38bdf8;">2. Exposure & Travel:</h4>
          <label class="option-chip"><input type="radio" name="exposure" value="high"> Close contact with a confirmed COVID patient in last 10 days</label>
          <label class="option-chip"><input type="radio" name="exposure" value="mid"> International travel or crowded containment zone visit</label>
          <label class="option-chip"><input type="radio" name="exposure" value="none" checked> No known exposure or travel</label>
        </div>

        <div class="quiz-box">
          <h4 style="font-size: 1rem; margin-bottom: 0.75rem; color: #38bdf8;">3. Co-morbidities:</h4>
          <label class="option-chip"><input type="checkbox" name="comorb" value="diabetes"> Diabetes or Hypertension</label>
          <label class="option-chip"><input type="checkbox" name="comorb" value="resp"> Asthma / Chronic Lung Disease</label>
          <label class="option-chip"><input type="checkbox" name="comorb" value="age"> Age 60 or above</label>
          <label class="option-chip"><input type="checkbox" name="comorb" value="none"> None of the above</label>
        </div>
      </div>

      <div style="display: flex; gap: 1rem; align-items: center; justify-content: space-between; flex-wrap: wrap;">
        <button type="button" class="btn btn-primary" id="btnRunAssessment" onclick="runAssessment()">
          Evaluate My COVID-19 Risk Score
        </button>
        <div id="assessmentResult" style="flex: 1; min-width: 280px; display: none;"></div>
      </div>
    </div>

    <!-- WHO & CDC Prevention Guidelines -->
    <div>
      <div style="text-align: center; max-width: 650px; margin: 0 auto 2.5rem;">
        <span class="hero-pill" style="margin-bottom: 0.5rem;">Safety Protocols</span>
        <h2 style="font-size: 1.85rem;">Clinical Prevention & Safety Standards</h2>
        <p style="color: var(--text-muted); font-size: 0.92rem;">Follow evidence-based prevention guidelines approved by the World Health Organization.</p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
        <div class="card">
          <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">😷</div>
          <h4 style="font-size: 1.1rem; margin-bottom: 0.4rem;">Double Masking</h4>
          <p style="color: var(--text-muted); font-size: 0.85rem;">Wear an N95 or dual layered surgical mask over nose and mouth in crowded or enclosed environments.</p>
        </div>
        <div class="card">
          <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">🧴</div>
          <h4 style="font-size: 1.1rem; margin-bottom: 0.4rem;">Frequent Sanitization</h4>
          <p style="color: var(--text-muted); font-size: 0.85rem;">Sanitize hands with at least 70% alcohol-based disinfectant or wash thoroughly with soap for 20 seconds.</p>
        </div>
        <div class="card">
          <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">📏</div>
          <h4 style="font-size: 1.1rem; margin-bottom: 0.4rem;">Social Distancing</h4>
          <p style="color: var(--text-muted); font-size: 0.85rem;">Maintain at least 6 feet (2 meters) distance from individuals who are coughing, sneezing, or symptomatic.</p>
        </div>
        <div class="card">
          <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">💉</div>
          <h4 style="font-size: 1.1rem; margin-bottom: 0.4rem;">Timely Vaccination</h4>
          <p style="color: var(--text-muted); font-size: 0.85rem;">Complete your primary dosage schedule and receive recommended booster doses to build strong immunity.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
// Live Search for State Table
document.getElementById('stateSearchInput').addEventListener('input', function() {
  const query = this.value.toLowerCase();
  const rows = document.querySelectorAll('#statesTable tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(query) ? '' : 'none';
  });
});

// Interactive Symptom Assessment Engine
function runAssessment() {
  const symptoms = document.querySelectorAll('input[name="symp"]:checked');
  const exposure = document.querySelector('input[name="exposure"]:checked')?.value;
  const comorb = document.querySelectorAll('input[name="comorb"]:checked');
  const resDiv = document.getElementById('assessmentResult');

  let sympCount = symptoms.length;
  let hasSevere = false;
  symptoms.forEach(s => {
    if (s.value === 'breath') hasSevere = true;
  });

  let risk = 'Low';
  let badgeColor = 'badge-pass';
  let recommendation = 'Your risk profile appears normal. Continue following basic hand hygiene and masking protocols.';

  if (hasSevere || (sympCount >= 3 && exposure === 'high')) {
    risk = 'High Risk';
    badgeColor = 'badge-fail';
    recommendation = '🚨 Urgent: Shortness of breath or high symptom convergence detected. Please isolate immediately, book an RT-PCR test, and contact national helpline 1075.';
  } else if (sympCount >= 2 || exposure === 'high' || exposure === 'mid') {
    risk = 'Moderate Risk';
    badgeColor = 'badge-blue';
    recommendation = '⚠️ Precaution: Moderate symptoms or possible exposure. We recommend self-monitoring, home isolation for 5 days, and scheduling a diagnostic test.';
  }

  resDiv.style.display = 'block';
  resDiv.innerHTML = `
    <div style="padding: 1rem; border-radius: var(--radius-sm); background: rgba(255,255,255,0.05); border: 1px solid var(--border-glass);">
      <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.4rem;">
        <strong>Assessment Result:</strong>
        <span class="badge ${badgeColor}">${risk}</span>
      </div>
      <p style="font-size: 0.85rem; color: var(--text-main); margin-bottom: 0.5rem;">${recommendation}</p>
      <a href="register_vaccine.php" class="btn btn-outline btn-sm" style="font-size: 0.78rem;">Book RT-PCR / Vaccine Slot →</a>
    </div>
  `;
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
