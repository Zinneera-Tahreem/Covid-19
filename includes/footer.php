  </main>

  <footer>
    <div class="container">
      <div class="footer-grid">
        <div class="footer-col">
          <div class="brand-logo" style="margin-bottom: 0.75rem;">
            <span class="brand-badge">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
              </svg>
            </span>
            <span>COVID<strong>Care</strong></span>
          </div>
          <p style="color: var(--text-muted); font-size: 0.85rem; line-height: 1.5; margin-bottom: 1rem;">
            Dynamic COVID-19 Surveillance, Vaccination Scheduling & Quality Assurance Portal developed as a Software Testing & Quality Assurance (STQA) mini project.
          </p>
          <div style="font-size: 0.8rem; color: var(--text-dim);">
            <span>Technologies: HTML5, CSS3, JavaScript, PHP, MySQL, Regular Expressions (PCRE)</span>
          </div>
        </div>

        <div class="footer-col">
          <h5>Quick Portals</h5>
          <ul class="footer-links">
            <li><a href="index.php">COVID-19 Live Stats</a></li>
            <li><a href="register_vaccine.php">Vaccine Appointment</a></li>
            <li><a href="comments.php">Public Feedback & Reviews</a></li>
            <li><a href="dashboard.php">User Profile & Passes</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h5>STQA Testing</h5>
          <ul class="footer-links">
            <li><a href="stqa_tests.php">Regex Test Case Runner</a></li>
            <li><a href="stqa_tests.php#bva-section">Boundary Value Analysis</a></li>
            <li><a href="stqa_tests.php#ecp-section">Equivalence Class Partitioning</a></li>
            <li><a href="stqa_tests.php#playground">Interactive Regex Sandbox</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h5>National Helplines</h5>
          <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.4rem;">
            MoHFW National Toll-Free: <strong class="mono" style="color: var(--primary);">1075</strong>
          </p>
          <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.4rem;">
            Childline Emergency: <strong class="mono" style="color: var(--accent-teal);">1098</strong>
          </p>
          <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.4rem;">
            Mental Health Support: <strong class="mono" style="color: #c084fc;">08046110007</strong>
          </p>
          <p style="font-size: 0.82rem; color: var(--text-muted);">
            Official Email: <span class="mono" style="color: var(--primary);">ncov2019@gov.in</span>
          </p>
        </div>
      </div>

      <div class="footer-bottom">
        <div>
          &copy; <?php echo date('Y'); ?> COVIDCare Portal. Software Testing & Quality Assurance Mini Project.
        </div>
        <div>
          <span>Dual-Layer Validation: Client-side JS + Server-side PHP Regex</span>
        </div>
      </div>
    </div>
  </footer>

  <script src="js/validation.js"></script>
</body>
</html>
