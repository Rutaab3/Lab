<?php
/**
 * Global Translation Initialization
 * Include this file in page headers to enable site-wide Google Translate
 */

// Get user's preferred language
$preferred_language = 'en'; // default
if (isset($_SESSION['user_id']) && isset($conn)) {
    $user_id = $_SESSION['user_id'];
    $lang_query = mysqli_query($conn, "SELECT preferred_language FROM users WHERE id=$user_id LIMIT 1");
    if ($lang_row = mysqli_fetch_assoc($lang_query)) {
        $preferred_language = $lang_row['preferred_language'] ?? 'en';
    }
}
?>

<!-- Google Translate Script -->
<!-- Google Translate Script -->
<script type="text/javascript">
(function() {
    // Helper to get cookie
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    // Immediate language sync (Before Widget Init)
    const savedLang = '<?= $preferred_language ?>';
    const currentCookie = getCookie('googtrans');
    // Expected format: /en/code, or just /en/en for English
    const expectedCookie = '/en/' + savedLang;

    // Logic: If the current cookie doesn't match the DB preference, force update.
    // This handles switching FROM Urdu TO English, not just English TO Urdu.
    if (currentCookie !== expectedCookie) {
        // If savedLang is 'en', we explicitly set it to /en/en to override any existing translation
        // or we could clear it, but setting it to /en/en is safer to ensure consistency.
        
        const domain = window.location.hostname;
        document.cookie = "googtrans=" + expectedCookie + "; path=/";
        document.cookie = "googtrans=" + expectedCookie + "; path=/; domain=" + domain;
        document.cookie = "googtrans=" + expectedCookie + "; path=/; domain=." + domain; // legacy support
        
        console.log('Antigravity: Synced language to ' + savedLang);
        
        // If we changed the cookie, we might need to reload to clear the translation visually
        // especially if going from Urdu -> English
        // We reload ONLY if there was already a cookie set (meaning a previous session existed)
        if (currentCookie) {
             location.reload();
        }
    }
})();

// Initialize Google Translate
function googleTranslateElementInit() {
    new google.translate.TranslateElement(
        {
            pageLanguage: 'en',
            includedLanguages: 'ur,hi,ar,fa,fr,de,es,zh-CN,pt,ru,ja',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        },
        'google_translate_element'
    );
}

// Function to set the Google Translate Cookie manually (called by Settings)
function setGoogleTranslateLanguage(langCode) {
    const cookieValue = '/en/' + langCode;
    const domain = window.location.hostname;
    
    document.cookie = "googtrans=" + cookieValue + "; path=/";
    document.cookie = "googtrans=" + cookieValue + "; path=/; domain=" + domain;
    document.cookie = "googtrans=" + cookieValue + "; path=/; domain=." + domain;

    // Save preference to DB
    if (typeof saveLanguagePreference === 'function') {
        saveLanguagePreference(langCode);
    }
    
    // Reload to apply
    location.reload();
}

// Banner Hider Logic (User Customization)
setInterval(function() {
    const banner = document.querySelector('.goog-te-banner-frame');
    if (banner) {
        banner.style.display = 'none';
        document.body.style.top = '0 !important';
        document.body.style.position = 'static !important';
    }
}, 500);
</script>

<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<!-- Hidden Google Translate Widget -->
<div id="google_translate_element" style="display: none;"></div>

<style>
  /* Hide the banner iframe (target multiple variations) */
  .goog-te-banner-frame {
    display: none !important;
    visibility: hidden !important;
  }
  
  iframe.goog-te-banner-frame,
  .goog-te-banner-frame.skiptranslate,
  .skiptranslate > iframe {
    display: none !important;
    visibility: hidden !important;
  }
  
  /* Hide the parent skiptranslate container safely */
  .skiptranslate {
    display: none !important;
    visibility: hidden !important; /* Added visibility hidden to container too */
    font-size: 0 !important; /* Shrink text if possible */
  }
  
  /* Critical: Prevent page shift/jump by resetting Google's forced styles */
  body {
    top: 0 !important;
    position: static !important;
  }
  
  /* Optional: Hide any floating tooltips or balloons that might appear */
  #goog-gt-tt,
  .goog-te-balloon-frame,
  .goog-tooltip,
  .goog-text-highlight {
    display: none !important;
  }
  
  /* Ensure main widget container is hidden */
  #google_translate_element {
      display: none !important;
  }
</style>
