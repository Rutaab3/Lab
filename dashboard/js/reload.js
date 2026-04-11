document.addEventListener("DOMContentLoaded", function () {
  // Fade in when page loads
  document.body.style.opacity = 0;
  document.body.style.transition = "opacity 0.5s ease-in-out";
  requestAnimationFrame(() => {
    document.body.style.opacity = 1;
  });

  // Fade out before navigating away
  document.querySelectorAll('a[href]').forEach(link => {
    if (
      link.hostname === window.location.hostname &&
      !link.target &&
      !link.href.includes('#') &&
      !link.hasAttribute('download') &&
      !link.hasAttribute('onclick') // Exclude links with onclick handlers (e.g., delete confirmations)
    ) {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const href = this.getAttribute('href');
        document.body.style.opacity = 0;
        setTimeout(() => {
          window.location.href = href;
        }, 500); // Match this delay with transition duration
      });
    }
  });
});

