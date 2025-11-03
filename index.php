<?php
// index.php

// load translations
require_once __DIR__ . '/lang/trad.php';

// detect mailer setup
$autoloadPath   = __DIR__ . '/vendor/autoload.php';
$mailConfigPath = __DIR__ . '/config/mail.php';
$mailEnabled    = false;
$mailConfig     = [];
$mailEncryption = 'starttls';

if (is_readable($autoloadPath) && is_readable($mailConfigPath)) {
  require_once $autoloadPath;
  $mailConfig = require $mailConfigPath;

  $requiredKeys = ['host', 'port', 'username', 'password', 'from', 'to'];
  $mailEnabled  = true;

  foreach ($requiredKeys as $key) {
    if (!isset($mailConfig[$key]) || $mailConfig[$key] === '') {
      $mailEnabled = false;
      break;
    }
  }

  if ($mailEnabled) {
    $mailEncryption = strtolower((string) ($mailConfig['encryption'] ?? 'starttls'));
  }
}

// load db and fetch projects
require_once __DIR__ . '/db/database.php';
$db           = Database::getInstance();
$allProjects  = $db
  ->query("SELECT * FROM projects ORDER BY id ASC")
  ->fetchAll(PDO::FETCH_ASSOC);

// handle GET “q” to filter by title
$search    = trim($_GET['q'] ?? '');
$projects  = $allProjects;
$noResults = false;

if ($search !== '') {
  $projects = array_filter($allProjects, function($p) use($search, $lang) {
    return stripos($p["title_$lang"], $search) !== false;
  });
  if (count($projects) === 0) {
    $noResults = true;
  }
}

// prepare contact form defaults
$name    = $_POST['name']    ?? '';
$email   = $_POST['email']   ?? '';
$message = $_POST['message'] ?? '';
$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // trim & validate
  $n = trim($name);
  $e = trim($email);
  $m = trim($message);

  if ($n && filter_var($e, FILTER_VALIDATE_EMAIL) && $m) {
    // show the success message
    $successMessage = sprintf(
      $t['contact_success'] ?? 'Thank you, %s!',
      htmlspecialchars($n)
    );

    // into SQLite
    $stmt = $db->prepare(
      'INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)'
    );
    $stmt->execute([$n, $e, $m]);

    $successExtras = [];

    if ($mailEnabled) {
      try {
        $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host       = $mailConfig['host'];
        $mailer->SMTPAuth   = true;
        $mailer->Username   = $mailConfig['username'];
        $mailer->Password   = $mailConfig['password'];
        switch ($mailEncryption) {
          case 'none':
          case 'plain':
          case 'false':
            $mailer->SMTPAutoTLS = false;
            $mailer->SMTPSecure  = false;
            break;
          case 'smtps':
          case 'ssl':
          case 'implicit':
          case 'implicit_tls':
            $mailer->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            break;
          case 'starttls':
          case 'tls':
          default:
            $mailer->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            break;
        }

        $mailer->Port = (int) $mailConfig['port'];

        $fromAddress = $mailConfig['from'];
        $fromName    = $mailConfig['from_name'] ?? 'Portfolio Contact';
        $safeName    = preg_replace("/[\r\n]+/", ' ', $n);
        $safeEmail   = filter_var($e, FILTER_SANITIZE_EMAIL);
        $mailer->setFrom($fromAddress, $fromName);
        $mailer->addReplyTo($safeEmail, $safeName);
        $mailer->addAddress($mailConfig['to']);
        $mailer->Subject = sprintf('Portfolio message from %s', $safeName);
        $mailer->Body    = sprintf(
          "Name: %s\nEmail: %s\n\n%s",
          $safeName,
          $safeEmail,
          str_replace(["\r\n", "\r"], "\n", $m)
        );

        $mailer->send();

        if (!empty($t['contact_mail_sent'])) {
          $successExtras[] = htmlspecialchars($t['contact_mail_sent']);
        }
      } catch (\Throwable $mailException) {
        error_log('Contact form mail failed: ' . $mailException->getMessage());
        if (!empty($t['contact_mail_failed'])) {
          $successExtras[] = htmlspecialchars($t['contact_mail_failed']);
        }
      }
    } elseif (!empty($t['contact_mail_disabled'])) {
      $successExtras[] = htmlspecialchars($t['contact_mail_disabled']);
    }

    if ($successExtras) {
      $successMessage .= ' ' . implode(' ', $successExtras);
    }

    $success = $successMessage;

    // clear form fields
    $name = $email = $message = '';
  } else {
    $error = $t['contact_error'] ?? 'Please complete all fields correctly.';
  }

  
  $search    = '';
  $projects  = $allProjects;
  $noResults = false;

}
?><!DOCTYPE html>
<html lang="<?=htmlspecialchars($lang)?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?=htmlspecialchars($t['welcome'])?></title>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="assets/css/style.css">
  <script>
    // labels for JS validation
  const i18n = {
    view_desc:   "<?= addslashes($t['view_desc']) ?>",
    hide_desc:   "<?= addslashes($t['hide_desc']) ?>",
    loading:     "<?= addslashes($t['loading']) ?>",
    theme_dark_label: "<?= addslashes($t['theme_dark_label']) ?>",
    theme_light_label:"<?= addslashes($t['theme_light_label']) ?>",
    name_req:    "<?= addslashes($t['form']['name_req']) ?>",
    email_req:   "<?= addslashes($t['form']['email_req']) ?>",
    message_req: "<?= addslashes($t['form']['message_req']) ?>",
    no_results:   "<?= addslashes($t['no_results']) ?>",
    match_one:    "<?= addslashes($t['match_one']) ?>",
    match_other:  "<?= addslashes($t['match_other']) ?>",
    search_empty:     "<?= addslashes($t['search_empty']) ?>",
    reset_search:     "<?= addslashes($t['reset_search']) ?>",
  };
</script>
</head>
<body>

  <!-- header/nav -->
  <?php include __DIR__ . '/includes/header.php'; ?>

  <!-- social sidebar -->
  <div class="social-fixed">
    <a href="mailto:sara.andari@icloud.com" aria-label="Email">
      <img src="assets/icons/email.svg" class="icon" alt="">
    </a>
    <a href="https://www.linkedin.com/in/sara-andari" target="_blank" aria-label="LinkedIn">
      <img src="assets/icons/linkedin.svg" class="icon" alt="">
    </a>
    <a href="https://github.com/sara14and" target="_blank" aria-label="GitHub">
      <img src="assets/icons/github.svg" class="icon" alt="">
    </a>
  </div>

  <!-- hero -->
  <section id="hero" class="hero">
    <div class="hero-wrapper">
      <div class="hero-left">
        <h1><?= htmlspecialchars($t['welcome']) ?></h1>
        <p class="hero-subtitle"><?= htmlspecialchars($t['subtitle']) ?></p>
        <p><?= htmlspecialchars($t['hello']) ?></p>
        <a class="btn-cv hero-cv" href="SaraAndariCV2025.pdf" download>
          <?= htmlspecialchars($t['download_cv']) ?>
        </a>
      </div>
      <div class="hero-right">
        <img id="emojiFloat" src="assets/photos/memoji2.png" alt="<?= htmlspecialchars($t['welcome']) ?>">
      </div>
    </div>
  </section>

    <!-- global search bar (GET method) -->
    <section id="search-bar">
    <form id="searchForm" method="GET" action="" aria-label="Global search">
      <input
        type="text"
        name="q"
        id="globalSearch"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="<?= htmlspecialchars($t['search_placeholder'] ?? 'Search…') ?>"
        aria-describedby="matchInfo"
        autocomplete="off"
      >
      <button
        type="button"
        id="resetSearch"
        <?= $search === '' ? 'hidden' : '' ?>
        aria-label="<?= htmlspecialchars($t['reset_search'] ?? 'Reset search') ?>"
      >×</button>
      <span id="matchInfo" class="match-count" aria-live="polite"></span>
    </form>
  </section>


  <!-- projects -->
  <section id="projects">
    <div class="section-wrapper">
      <h2><?= htmlspecialchars($t['nav']['projects']) ?></h2>
      
      <div class="projects-grid">
        <?php foreach ($projects as $p): ?>
          <div class="card" data-id="<?= $p['id'] ?>">
            <div class="card-content">
              <h3><?= htmlspecialchars($p["title_$lang"]) ?></h3>
              <div class="detail" style="display:none"></div>
              <button class="btn-view-desc" type="button">
                <?= htmlspecialchars($t['view_desc']) ?>
              </button>
              
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- experience -->
  <section id="experience">
    <div class="section-wrapper">
      <h2><?=htmlspecialchars($t['nav']['experience'])?></h2>
      <div class="projects-grid">
        <?php foreach($t['experience_data'] as $key => $exp): ?>
          <div class="card" data-key="<?=htmlspecialchars($key)?>">
            <div class="card-content">
              <h3><?=htmlspecialchars($exp['role'])?></h3>
              <p><?=htmlspecialchars($exp['company'])?></p>
              <div class="detail" style="display:none"></div>
              <button class="btn-view-desc-exp" type="button">
                <?=htmlspecialchars($t['view_desc'])?>
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- skills -->
  <section id="skills">
    <div class="section-wrapper">
      <h2><?=htmlspecialchars($t['skills'])?></h2>
      <div class="projects-grid">
        <?php foreach($t['skills_data'] as $grp): ?>
          <div class="card">
            <div class="card-content">
              <h3><?=htmlspecialchars($grp['label'])?></h3>
              <ul>
                <?php foreach($grp['items'] as $it): ?>
                  <li><?=htmlspecialchars($it)?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- contact (POST nethod) -->
  <section id="contact" class="contact">
    <div class="section-wrapper contact-wrapper">
      <h2><?= htmlspecialchars($t['nav']['contact']) ?></h2>
      <p class="contact-sub"><?= htmlspecialchars($t['contact_message']) ?></p>

      <?php if ($success): ?>
        <div class="form-message success"><?= $success ?></div>
      <?php elseif ($error): ?>
        <div class="form-message error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form id="contactForm" action="#contact" method="POST" novalidate>
  <div class="field">
    <label for="name"><?= htmlspecialchars($t['form']['name']) ?></label>
    <input id="name" type="text" name="name" value="<?= htmlspecialchars($name) ?>">
    <span class="error-msg"></span>
  </div>

  <div class="field">
    <label for="email"><?= htmlspecialchars($t['form']['email']) ?></label>
    <input id="email" type="email" name="email" value="<?= htmlspecialchars($email) ?>">
    <span class="error-msg"></span>
  </div>

  <div class="field">
    <label for="message"><?= htmlspecialchars($t['form']['message']) ?></label>
    <textarea id="message" name="message"><?= htmlspecialchars($message) ?></textarea>
    <span class="error-msg"></span>
  </div>

  <button type="submit"><?= htmlspecialchars($t['form']['send']) ?></button>
</form>


  <?php include __DIR__.'/includes/footer.php'; ?>
  <script src="assets/js/script.js"></script>
</body>
</html>
