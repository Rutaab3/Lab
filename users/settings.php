<?php
include "../config/db.php"; 
include "../config/auth.php"; 

// Role check - must be before any output
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id LIMIT 1");
$user = mysqli_fetch_assoc($res);
if (!$user) die("User not found.");

$message = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id=$user_id";
        if (mysqli_query($conn, $sql)) {
            $message = "Profile updated successfully!";
            $_SESSION['username'] = $username;
            $user['username'] = $username;
            $user['email'] = $email;
        } else {
            $error = "Error updating profile: " . mysqli_error($conn);
        }
    }
    
    if ($action === 'update_language') {
        $language = mysqli_real_escape_string($conn, $_POST['language']);
        $allowed_languages = ['en', 'ur', 'hi', 'ar', 'fa', 'fr', 'de', 'es', 'zh-CN', 'pt', 'ru', 'ja'];
        
        if (in_array($language, $allowed_languages)) {
            $sql = "UPDATE users SET preferred_language='$language' WHERE id=$user_id";
            if (mysqli_query($conn, $sql)) {
                $message = "Language preference updated successfully!";
                $user['preferred_language'] = $language;
            } else {
                $error = "Error updating language preference.";
            }
        } else {
            $error = "Invalid language selected.";
        }
    }
    
    if ($action === 'change_password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                if (strlen($new_password) >= 6) {
                    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET password='$hashed' WHERE id=$user_id";
                    if (mysqli_query($conn, $sql)) {
                        $message = "Password changed successfully!";
                    } else {
                        $error = "Error changing password.";
                    }
                } else {
                    $error = "Password must be at least 6 characters.";
                }
            } else {
                $error = "New passwords do not match.";
            }
        } else {
            $error = "Current password is incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Settings - Lab Automation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include "../xtras/link.php";?>
  <link rel="stylesheet" href="alert.css">
  <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  <style>
    /* Board.css Theme Variables */
    :root {
        --primary-color: #6366f1;
        --primary-dark: #4f46e5;
        --secondary-color: #f8fafc;
        --text-dark: #1e293b;
        --text-light: #64748b;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.1);
        --border-radius: 8px;
        --transition: all 0.2s ease-out;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        background-color: #f0f4f8;
        color: var(--text-dark);
        min-height: 100vh;
    }

    .settings-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .settings-header {
        background: white;
        padding: 30px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        margin-bottom: 20px;
    }

    .settings-header h1 {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
    }

    .settings-header p {
        color: var(--text-light);
        font-size: 0.95rem;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 20px;
    }

    .settings-sidebar {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        padding: 20px;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .settings-nav {
        list-style: none;
    }

    .settings-nav li {
        margin-bottom: 5px;
    }

    .settings-nav a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        color: var(--text-dark);
        text-decoration: none;
        border-radius: 6px;
        transition: var(--transition);
        font-weight: 500;
    }

    .settings-nav a:hover {
        background: #f0f4f8;
        color: var(--primary-color);
    }

    .settings-nav a.active {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
    }

    .settings-content {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        padding: 30px;
    }

    .settings-section {
        display: none;
        animation: fadeIn 0.3s ease-in;
    }

    .settings-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-description {
        color: var(--text-light);
        margin-bottom: 30px;
        font-size: 0.95rem;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: var(--transition);
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-group small {
        display: block;
        color: var(--text-light);
        margin-top: 5px;
        font-size: 0.85rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: var(--shadow);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-secondary {
        background: white;
        color: var(--text-dark);
        border: 2px solid #e2e8f0;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition);
        margin-left: 10px;
    }

    .btn-secondary:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 24px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .preference-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .preference-item:last-child {
        border-bottom: none;
    }

    .preference-info h4 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 5px;
    }

    .preference-info p {
        font-size: 0.85rem;
        color: var(--text-light);
    }

    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .settings-sidebar {
            position: static;
        }
    }

    /* FAQ Styles */
    .faq-item {
        border-bottom: 1px solid #e2e8f0;
    }
    .faq-item:last-child {
        border-bottom: none;
    }
    .faq-question {
        padding: 15px 0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: var(--text-dark);
        transition: color 0.2s;
    }
    .faq-question:hover {
        color: var(--primary-color);
    }
    .faq-answer {
        display: none;
        padding-bottom: 15px;
        color: var(--text-light);
        line-height: 1.6;
        font-size: 0.9rem;
    }
    .faq-item.active .faq-answer {
        display: block;
    }
    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }
  </style>
</head>
<body>

<?php
if ($role === 'admin') {
    include '../xtras/adminhead.php';
} elseif ($role === 'tester') {
    include '../xtras/testerhead.php';
} elseif ($role === 'supplier') {
    include '../xtras/supplierhead.php';
} elseif ($role === 'analyst') {
    include '../xtras/analysthead.php';
} elseif ($role === 'user') {
    include '../xtras/usershead.php';
}
?>

<div class="settings-container">
    <div class="settings-header">
        <h1><i class="bi bi-gear"></i> Settings</h1>
        <p>Manage your account settings and preferences</p>
    </div>

    <?php if ($message): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: '<?= addslashes($message) ?>',
          customClass: {
            popup: 'swal2-success'
          }
        });
      });
    </script>
    <?php endif; ?>
    <?php if ($error): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: '<?= addslashes($error) ?>',
          customClass: {
            popup: 'swal2-error'
          }
        });
      });
    </script>
    <?php endif; ?>

    <div class="settings-grid">
        <!-- Sidebar Navigation -->
        <div class="settings-sidebar">
            <ul class="settings-nav">
                <li><a href="#profile" class="nav-link active" onclick="showSection('profile')">
                    <i class="bi bi-person"></i> Account
                </a></li>
                <li><a href="#preferences" class="nav-link" onclick="showSection('preferences')">
                    <i class="bi bi-sliders"></i> Preferences
                </a></li>
                <li><a href="#help" class="nav-link" onclick="showSection('help')">
                    <i class="bi bi-question-circle"></i> Help
                </a></li>
                 <li><a href="#policies" class="nav-link" onclick="showSection('policies')">
                    <i class="bi bi-file-ppt"></i> Policies
                </a></li>
               <li><a href="#terms" class="nav-link" onclick="showSection('terms')">
                    <i class="bi bi-file-font"></i> Terms
                </a></li>
                 <li><a href="#support" class="nav-link" onclick="showSection('support')">
                    <i class="bi bi-headset"></i> Support   
                </a></li>
                 <li><a href="#contact" class="nav-link" onclick="showSection('contact')">
                    <i class="bi bi-envelope"></i> Contact
                </a></li>
                 <li><a href="#about" class="nav-link" onclick="showSection('about')">
                    <i class="bi bi-info-circle"></i> About
                </a></li>
            </ul>
        </div>

        <!-- Content Area -->
        <div class="settings-content">
            <!-- Profile Section -->
            <div id="profile" class="settings-section active">
                <h2 class="section-title">Profile Information</h2>
                <p class="section-description">Update your account profile information and email address.</p>

                <form method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                        <small>This is your public display name.</small>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        <small>Your primary email address for notifications and login.</small>
                    </div>

                    <button type="submit" class="btn-primary">Save Changes</button>
                </form>
            </div>

            <!-- Preferences Section -->
            <div id="preferences" class="settings-section">
                <h2 class="section-title">Display Preferences</h2>
                <p class="section-description">Customize how you view the application.</p>

                <div class="form-group">
                    <label for="language">Language</label>
                    <select id="language" name="language" onchange="changeLanguage(this.value)">
                        <option value="" disabled <?= (!isset($user['preferred_language']) || empty($user['preferred_language'])) ? 'selected' : '' ?>>Select Language</option>
                        <option value="ur" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'ur') ? 'selected' : '' ?>>🇵🇰 Urdu</option>
                        <option value="hi" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'hi') ? 'selected' : '' ?>>🇮🇳 Hindi</option>
                        <option value="ar" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'ar') ? 'selected' : '' ?>>🇸🇦 Arabic</option>
                        <option value="fa" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'fa') ? 'selected' : '' ?>>🇮🇷 Persian</option>
                        <option value="fr" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'fr') ? 'selected' : '' ?>>🇫🇷 French</option>
                        <option value="de" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'de') ? 'selected' : '' ?>>🇩🇪 German</option>
                        <option value="es" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'es') ? 'selected' : '' ?>>🇪🇸 Spanish</option>
                        <option value="zh-CN" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'zh-CN') ? 'selected' : '' ?>>🇨🇳 Chinese</option>
                        <option value="pt" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'pt') ? 'selected' : '' ?>>🇵🇹 Portuguese</option>
                        <option value="ru" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'ru') ? 'selected' : '' ?>>🇷🇺 Russian</option>
                        <option value="ja" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'ja') ? 'selected' : '' ?>>🇯🇵 Japanese</option>
                        <option value="en" <?= (isset($user['preferred_language']) && $user['preferred_language'] === 'en') ? 'selected' : '' ?>>🇬🇧 English</option>
                    </select>
                    <small>Select your preferred language for the entire website.</small>
                </div>
                
                <!-- Hidden Google Translate Element -->
                <div id="google_translate_element" style="display: none;"></div>

                <div class="form-group">
                    <label for="timezone">Timezone</label>
                    <select id="timezone">
                        <option value="UTC">UTC</option>
                        <option value="PST">Pacific Time</option>
                        <option value="EST">Eastern Time</option>
                        <option value="GMT">GMT</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="items_per_page">Items Per Page</label>
                    <select id="items_per_page">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <small>Number of items to display in tables and lists.</small>
                </div>

                <button type="button" class="btn-primary" onclick="location.reload()">Save Changes</button>
            </div>

            <!-- Help Section -->
            <div id="help" class="settings-section">
                <h2 class="section-title">Help Center</h2>
                <p class="section-description">Find answers to common questions and learn how to use the platform.</p>
                
                <div class="form-group">
                    <input type="text" id="faqSearch" onkeyup="filterFaq()" placeholder="Search for help..." style="padding-left: 40px; background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzY0NzQ4YiIgY2xhc3M9ImJpIGJpLXNlYXJjaCIgdmlld0JveD0iMCAwIDE2IDE2Ij4gPHBhdGggZD0iTTExLjc0MiAxMC4zNDRsNC4yNSA0LjI1YTUgMSAwIDEgMC03LjA3MS03LjA3MSA1IDEgMCAxIDAgNy4wNzEgNy4wNzF6Ii8+IDwvc3ZnPg=='); background-repeat: no-repeat; background-position: 12px center;">
                </div>

                <div class="faq-list" style="margin-top: 20px;">
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            How do I reset my password?
                            <i class="bi bi-chevron-down transition-transform"></i>
                        </div>
                        <div class="faq-answer">
                            To reset your password, go to the login page and click on "Forgot Password". Follow the instructions sent to your email. Alternatively, if you are logged in, you can go to the "Account" section in Settings and change it there.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            How do I change my language?
                            <i class="bi bi-chevron-down transition-transform"></i>
                        </div>
                        <div class="faq-answer">
                            You can change your preferred language in the "Preferences" tab of the Settings page. Select your language from the dropdown menu, and the application will update immediately.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            Can I export my data?
                            <i class="bi bi-chevron-down transition-transform"></i>
                        </div>
                        <div class="faq-answer">
                            Yes, depending on your role, you may have access to export features in the Reports section. If you need a full data dump, please contact support.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            How do I contact support?
                            <i class="bi bi-chevron-down transition-transform"></i>
                        </div>
                        <div class="faq-answer">
                            You can contact support via the "Support" tab in Settings. We offer documentation, live chat, and a ticket submission system.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            How do I update my profile picture?
                            <i class="bi bi-chevron-down transition-transform"></i>
                        </div>
                        <div class="faq-answer">
                            Navigate to the "Account" tab in Settings. Click on the camera icon on your current profile picture to upload a new image.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            Which browsers are supported?
                            <i class="bi bi-chevron-down transition-transform"></i>
                        </div>
                        <div class="faq-answer">
                            We support the latest versions of Chrome, Firefox, Safari, and Edge. For the best experience, please keep your browser updated.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            Is my data secure?
                            <i class="bi bi-chevron-down transition-transform"></i>
                        </div>
                        <div class="faq-answer">
                            Yes, we use industry-standard encryption for data in transit and at rest. We also perform regular security audits to ensure your data remains safe.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            How do I report a bug?
                            <i class="bi bi-chevron-down transition-transform"></i>
                        </div>
                        <div class="faq-answer">
                            If you encounter an issue, please go to the "Support" tab and submit a ticket describing the problem. Screenshots are very helpful!
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            Can I upgrade my account plan?
                            <i class="bi bi-chevron-down transition-transform"></i>
                        </div>
                        <div class="faq-answer">
                            Account upgrades are handled by your organization's administrator. Please contact them to request additional features or higher limits.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Policies Section -->
            <div id="policies" class="settings-section">
                <h2 class="section-title">Policies</h2>
                <p class="section-description">Read about our privacy policy and data usage guidelines.</p>
                
                <div style="background: #fff; padding: 25px; border-radius: var(--border-radius); border: 1px solid #e2e8f0; height: 500px; overflow-y: auto;">
                    <h3 style="color: var(--primary-color); margin-bottom: 15px;">Privacy Policy</h3>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;"><strong>Last Updated: December 2025</strong></p>
                    
                    <h4 style="margin-bottom: 10px;">1. Information We Collect</h4>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        We collect information you provide directly to us, such as when you create an account, update your profile, or communicate with us. This includes your name, email address, and any other information you choose to provide.
                    </p>

                    <h4 style="margin-bottom: 10px;">2. How We Use Your Information</h4>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        We use the information we collect to provide, maintain, and improve our services, to develop new ones, and to protect our users and ourselves. We also use this information to offer you tailored content – like giving you more relevant search results.
                    </p>

                    <h4 style="margin-bottom: 10px;">3. Information Sharing</h4>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        We do not share your personal information with companies, organizations, or individuals outside of Lab Automation Inc. except in the following cases: with your consent, for external processing, or for legal reasons.
                    </p>

                    <h4 style="margin-bottom: 10px;">4. Data Security</h4>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        We work hard to protect Lab Automation Inc. and our users from unauthorized access to or unauthorized alteration, disclosure, or destruction of information we hold.
                    </p>
                    
                    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #e2e8f0;">

                    <h3 style="color: var(--primary-color); margin-bottom: 15px;">Data Usage Policy</h3>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        This policy describes how we process your data. By using our services, you agree to the collection and use of information in accordance with this policy.
                    </p>
                </div>
            </div>

            <!-- Terms Section -->
            <div id="terms" class="settings-section">
                <h2 class="section-title">Terms of Service</h2>
                <p class="section-description">Please review our terms of service agreement.</p>
                
                <div style="background: #fff; padding: 25px; border-radius: var(--border-radius); border: 1px solid #e2e8f0; height: 500px; overflow-y: auto;">
                    <h3 style="color: var(--primary-color); margin-bottom: 15px;">Terms of Service</h3>
                    <p style="margin-bottom: 20px; color: var(--text-light);">Please read these Terms of Service carefully before using the Lab Automation website operated by Lab Auto Inc.</p>
                    
                    <h4 style="margin-bottom: 10px;">1. Acceptance of Terms</h4>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        By accessing or using the Service you agree to be bound by these Terms. If you disagree with any part of the terms then you may not access the Service.
                    </p>

                    <h4 style="margin-bottom: 10px;">2. Accounts</h4>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        When you create an account with us, you must provide us information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account on our Service.
                    </p>

                    <h4 style="margin-bottom: 10px;">3. Intellectual Property</h4>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        The Service and its original content, features and functionality are and will remain the exclusive property of Lab Auto Inc. and its licensors.
                    </p>

                    <h4 style="margin-bottom: 10px;">4. Termination</h4>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        We may terminate or suspend access to our Service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.
                    </p>

                    <h4 style="margin-bottom: 10px;">5. Limitation of Liability</h4>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        In no event shall Lab Auto Inc. be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your access to or use of or inability to access or use the Service.
                    </p>
                    
                    <h4 style="margin-bottom: 10px;">6. Governing Law</h4>
                    <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                        These Terms shall be governed and construed in accordance with the laws of the country, without regard to its conflict of law provisions.
                    </p>
                </div>
            </div>

            <!-- Support Section -->
            <div id="support" class="settings-section">
                <h2 class="section-title">Support</h2>
                <p class="section-description">Need help? Get in touch with our support team.</p>
                
                <div class="settings-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 30px; gap: 20px;">
                    <div style="padding: 20px; border: 1px solid #e2e8f0; border-radius: var(--border-radius); text-align: center;">
                        <i class="bi bi-book" style="font-size: 2rem; color: var(--primary-color);"></i>
                        <h3 style="margin: 10px 0;">Documentation</h3>
                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 15px;">Detailed guides and API references.</p>
                        <button class="btn-secondary" onclick="window.open('../docs/User_Manual.php')">View Docs</button>
                    </div>
                    <div style="padding: 20px; border: 1px solid #e2e8f0; border-radius: var(--border-radius); text-align: center;">
                        <i class="bi bi-chat-dots" style="font-size: 2rem; color: var(--primary-color);"></i>
                        <h3 style="margin: 10px 0;">Live Chat</h3>
                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 15px;">Chat with our support agents.</p>
                        <button class="btn-secondary">Start Chat</button>
                    </div>
                </div>

                <form>
                    <h3 style="margin-bottom: 15px;">Open a Ticket</h3>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" placeholder="Briefly describe your issue">
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea rows="4" placeholder="Describe your issue in detail..."></textarea>
                    </div>
                    <button type="button" class="btn-primary">Submit Ticket</button>
                </form>
            </div>

            <!-- Contact Section -->
            <div id="contact" class="settings-section">
                <h2 class="section-title">Contact Us</h2>
                <p class="section-description">We'd love to hear from you. Send us a message.</p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div style="width: 40px; height: 40px; background: #eff6ff; color: var(--primary-color); display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">Email</div>
                            <div style="font-size: 0.9rem; color: var(--text-light);">contact@labauto.com</div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div style="width: 40px; height: 40px; background: #eff6ff; color: var(--primary-color); display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">Phone</div>
                            <div style="font-size: 0.9rem; color: var(--text-light);">+1 (555) 123-4567</div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div style="width: 40px; height: 40px; background: #eff6ff; color: var(--primary-color); display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">Office</div>
                            <div style="font-size: 0.9rem; color: var(--text-light);">123 Lab St, Tech City</div>
                        </div>
                    </div>
                </div>

                <form>
                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" value="<?= htmlspecialchars($user['username']) ?>" readonly style="background: #f1f5f9;">
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea rows="5" placeholder="How can we help?"></textarea>
                    </div>
                    <button type="button" class="btn-primary">Send Message</button>
                </form>
            </div>

            <!-- About Section -->
            <div id="about" class="settings-section">
                <h2 class="section-title">About</h2>
                <p class="section-description">Information about the Lab Automation platform.</p>
                
                <div style="text-align: center; padding: 40px 20px;">
                    <img src="../logo/logo.png" alt="Lab Automation" style="height: 60px; margin-bottom: 20px; onerror=this.style.display='none'">
                    <!-- If logo fails, fallback icon -->
                    <i class="bi bi-hexagon-fill" style="font-size: 4rem; color: var(--primary-color); display: none;"></i>
                    
                    <h3 style="font-size: 1.5rem; margin-bottom: 10px;">Lab Automation System</h3>
                    <p style="color: var(--text-light); margin-bottom: 20px;">Version 2.5.0</p>
                    
                    <p style="max-width: 600px; margin: 0 auto 30px; line-height: 1.6;">
                        A comprehensive solution for managing laboratory resources, workflows, and automation. 
                        Streamlining research and development with cutting-edge technology.
                    </p>
                    
                    <div style="display: flex; justify-content: center; gap: 15px;">
                        <a href="#" style="color: var(--text-light); font-size: 1.2rem;"><i class="bi bi-github"></i></a>
                        <a href="#" style="color: var(--text-light); font-size: 1.2rem;"><i class="bi bi-twitter"></i></a>
                        <a href="#" style="color: var(--text-light); font-size: 1.2rem;"><i class="bi bi-linkedin"></i></a>
                        <a href="#" style="color: var(--text-light); font-size: 1.2rem;"><i class="bi bi-globe"></i></a>
                    </div>
                </div>

                <div style="margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 30px;">
                    <div style="display: flex; justify-content: space-between; color: var(--text-light); font-size: 0.9rem;">
                        <span>&copy; <?= date('Y') ?> Lab Auto Inc. All rights reserved.</span>
                        <div>
                            <a href="#terms" onclick="showSection('terms')" style="color: inherit; text-decoration: none; margin-left: 15px;">Terms</a>
                            <a href="#policies" onclick="showSection('policies')" style="color: inherit; text-decoration: none; margin-left: 15px;">Privacy</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Google Translate Initialization
function googleTranslateElementInit() {
    new google.translate.TranslateElement(
        {
            pageLanguage: 'en',
            includedLanguages: 'ur,hi,ar,fa,fr,de,es,zh-CN,pt,ru,ja',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        },
        'google_translate_element'
    );
    
    // After Google Translate initializes, set the saved language
    setTimeout(() => {
        const savedLang = document.getElementById('language').value;
        if (savedLang && savedLang !== 'en') {
            setGoogleTranslateLanguage(savedLang);
        }
    }, 1000);
}

// Function to change language
function changeLanguage(langCode) {
    // Set the Google Translate language
    setGoogleTranslateLanguage(langCode);
    
    // Save to database via AJAX
    saveLanguagePreference(langCode);
}

// Set Google Translate language using cookie and reload
function setGoogleTranslateLanguage(langCode) {
    // Google Translate uses the 'googtrans' cookie
    // Format: /sourceLang/targetLang
    const cookieValue = '/en/' + langCode;
    
    // Set cookie for root path and domain
    document.cookie = "googtrans=" + cookieValue + "; path=/; domain=" + window.location.hostname;
    document.cookie = "googtrans=" + cookieValue + "; path=/";
    
    // Also set the legacy cookie just in case
    document.cookie = "googtrans=" + cookieValue + "; path=/; domain=." + window.location.hostname;
    
    // Check if we need to reload to apply changes
    const currentCookie = getCookie('googtrans');
    if (currentCookie !== cookieValue) {
         location.reload();
    }
}

// Helper to get cookie
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

// Save language preference to database
function saveLanguagePreference(langCode) {
    fetch('save_language.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'language=' + encodeURIComponent(langCode)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Language preference saved:', langCode);
        } else {
            console.error('Error saving language:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Remove active class from all nav links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById(sectionId).classList.add('active');
    
    // Add active class to clicked nav link
    event.target.closest('.nav-link').classList.add('active');
    
    // Prevent default anchor behavior
    event.preventDefault();
}

// FAQ Toggle
function toggleFaq(element) {
    const item = element.parentElement;
    item.classList.toggle('active');
}

// FAQ Search
function filterFaq() {
    const input = document.getElementById('faqSearch');
    const filter = input.value.toLowerCase();
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
        
        if (question.includes(filter) || answer.includes(filter)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

</body>
</html>
