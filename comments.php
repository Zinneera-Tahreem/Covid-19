<?php
$pageTitle = "Community Feedback & Experience Forum";
require_once __DIR__ . '/db.php';

$currentUser = getCurrentUser();
$errors = [];
$successMessage = '';

// Handle Comment Post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author_name = trim($_POST['author_name'] ?? '');
    $author_email = trim($_POST['author_email'] ?? '');
    $category = trim($_POST['category'] ?? 'General Feedback');
    $rating = intval($_POST['rating'] ?? 5);
    $comment_text = trim($_POST['comment_text'] ?? '');

    // STQA Server-Side Regex Validations
    if (!preg_match(REGEX_FULL_NAME, $author_name)) {
        $errors['author_name'] = "STQA Regex Failed: Author Name must contain only letters and spaces (3-50 chars).";
    }

    if (!preg_match(REGEX_EMAIL, $author_email)) {
        $errors['author_email'] = "STQA Regex Failed: Please provide a valid email format.";
    }

    if (!preg_match(REGEX_COMMENT, $comment_text)) {
        $errors['comment_text'] = "STQA Regex Failed: Feedback must be 5 to 500 characters and cannot contain script tags or illegal characters.";
    }

    if ($rating < 1 || $rating > 5) {
        $rating = 5;
    }

    if (empty($errors)) {
        $userId = $currentUser ? $currentUser['id'] : null;
        $stmt = $db->prepare("INSERT INTO comments (user_id, author_name, author_email, category, rating, comment_text) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $author_name, $author_email, $category, $rating, $comment_text]);
        $successMessage = "Thank you! Your feedback has been verified via STQA regular expressions and posted to the community board.";
        
        // Reset post values
        $_POST = [];
    }
}

// Fetch all comments ordered by newest
$stmt = $db->query("SELECT * FROM comments ORDER BY id DESC LIMIT 50");
$comments = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 2.5rem 1.5rem;">
  <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; margin-bottom: 2.5rem;">
    <div>
      <span class="hero-pill" style="margin-bottom: 0.5rem;">Public Discussion</span>
      <h1 style="font-size: 2.2rem;">Community COVID-19 Forum & Feedback</h1>
      <p style="color: var(--text-muted); font-size: 0.95rem;">
        Share your vaccination experience, center reviews, recovery tips, and clinical queries.
      </p>
    </div>
    <div>
      <a href="#commentForm" class="btn btn-primary" id="btnJumpToPost">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
        Post Public Feedback
      </a>
    </div>
  </div>

  <?php if (!empty($successMessage)): ?>
    <div class="alert alert-success">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
      <span><?php echo e($successMessage); ?></span>
    </div>
  <?php endif; ?>

  <div style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 2.5rem; align-items: flex-start;">
    
    <!-- Left Column: Recent Community Comments -->
    <div>
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.3rem;">Recent Community Posts (<?php echo count($comments); ?>)</h3>
        <span style="font-size: 0.8rem; color: var(--text-dim);" class="mono">MySQL `comments` Table</span>
      </div>

      <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        <?php if (empty($comments)): ?>
          <div class="card" style="text-align: center; padding: 3rem;">
            <p style="color: var(--text-muted);">No comments submitted yet. Be the first citizen to post feedback!</p>
          </div>
        <?php else: ?>
          <?php foreach ($comments as $cmt): ?>
            <div class="card" style="padding: 1.4rem;">
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                  <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, rgba(6,182,212,0.3), rgba(139,92,246,0.3)); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #ffffff; border: 1px solid var(--border-glass);">
                    <?php echo strtoupper(substr($cmt['author_name'], 0, 1)); ?>
                  </div>
                  <div>
                    <strong style="color: #ffffff; font-size: 0.98rem;"><?php echo e($cmt['author_name']); ?></strong>
                    <div style="font-size: 0.75rem; color: var(--text-dim);" class="mono">
                      <?php echo date('M d, Y · h:i A', strtotime($cmt['created_at'])); ?>
                    </div>
                  </div>
                </div>
                <div>
                  <span class="badge badge-purple" style="font-size: 0.72rem;"><?php echo e($cmt['category']); ?></span>
                </div>
              </div>

              <!-- Rating Stars -->
              <div style="color: #f59e0b; font-size: 0.85rem; margin-bottom: 0.6rem;">
                <?php for ($i = 0; $i < $cmt['rating']; $i++): ?>★<?php endfor; ?>
                <?php for ($i = $cmt['rating']; $i < 5; $i++): ?><span style="color: rgba(255,255,255,0.15);">★</span><?php endfor; ?>
              </div>

              <!-- Comment Body -->
              <p style="color: var(--text-main); font-size: 0.92rem; line-height: 1.55;">
                <?php echo nl2br(e($cmt['comment_text'])); ?>
              </p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Column: Submit Comment Form -->
    <div>
      <div class="form-card" style="margin: 0; padding: 2rem;">
        <div style="margin-bottom: 1.5rem;">
          <h3 style="font-size: 1.35rem; margin-bottom: 0.3rem;">Share Your Experience</h3>
          <p style="font-size: 0.85rem; color: var(--text-muted);">
            Validated with client-side & server-side regex sanitization.
          </p>
        </div>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-error">
            <ul style="margin-left: 1.25rem; font-size: 0.85rem;">
              <?php foreach ($errors as $err): ?>
                <li><?php echo e($err); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form id="commentForm" method="POST" action="comments.php" novalidate>
          <div class="form-group">
            <label for="author_name" class="form-label">
              <span>Your Name *</span>
              <span class="regex-hint">/^[a-zA-Z\s]{3,50}$/</span>
            </label>
            <input type="text" id="author_name" name="author_name" class="form-control" placeholder="e.g. Priya Nair" value="<?php echo e($_POST['author_name'] ?? ($currentUser['full_name'] ?? '')); ?>" required>
            <div id="author_name-feedback" class="feedback-msg"></div>
          </div>

          <div class="form-group">
            <label for="author_email" class="form-label">
              <span>Email Address *</span>
              <span class="regex-hint">RFC 5322</span>
            </label>
            <input type="email" id="author_email" name="author_email" class="form-control" placeholder="e.g. priya.nair@example.com" value="<?php echo e($_POST['author_email'] ?? ($currentUser['email'] ?? '')); ?>" required>
            <div id="author_email-feedback" class="feedback-msg"></div>
          </div>

          <div class="form-group">
            <label for="category" class="form-label">
              <span>Topic Category *</span>
            </label>
            <select id="category" name="category" class="form-control">
              <option value="Vaccination Experience">Vaccination Experience</option>
              <option value="Center Cleanliness & Safety">Center Cleanliness & Safety</option>
              <option value="COVID-19 Recovery Story">COVID-19 Recovery Story</option>
              <option value="Medical & Symptom Query">Medical & Symptom Query</option>
              <option value="General Feedback">General Feedback</option>
            </select>
          </div>

          <div class="form-group">
            <label for="rating" class="form-label">
              <span>Rating (1 to 5 Stars) *</span>
            </label>
            <select id="rating" name="rating" class="form-control">
              <option value="5">★★★★★ - Excellent (5 Stars)</option>
              <option value="4">★★★★☆ - Very Good (4 Stars)</option>
              <option value="3">★★★☆☆ - Average (3 Stars)</option>
              <option value="2">★★☆☆☆ - Below Expectations (2 Stars)</option>
              <option value="1">★☆☆☆☆ - Poor (1 Star)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="comment_text" class="form-label">
              <span>Your Feedback / Comment *</span>
              <span class="regex-hint">5-500 chars, no HTML tags</span>
            </label>
            <textarea id="comment_text" name="comment_text" rows="4" class="form-control" placeholder="Describe your experience at the clinic or your recovery journey..." required><?php echo e($_POST['comment_text'] ?? ''); ?></textarea>
            <div id="comment_text-feedback" class="feedback-msg"></div>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem;" id="btnSubmitComment">
            Publish Verified Feedback
          </button>
        </form>
      </div>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
