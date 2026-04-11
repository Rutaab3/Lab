document.addEventListener('DOMContentLoaded', function () {
  // -----------------
  // Sidebar Toggle
  // -----------------
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');
  const sidebarCollapse = document.getElementById('sidebarCollapse');
  const sidebarCollapsex = document.getElementById('sidebarCollapsex');


  if (sidebarCollapse) {
    sidebarCollapse.addEventListener('click', function () {
      sidebar.classList.toggle('toggled');
      content.classList.toggle('toggled');
    });
  }
  if (sidebarCollapsex) {
    sidebarCollapsex.addEventListener('click', function () {
      sidebar.classList.toggle('toggled');
      content.classList.toggle('toggled');
    });
  }

});

// Notification Bell Click - Mark Reports as Seen
document.getElementById("notifBell").addEventListener("click", function () {
  fetch("../adminplayground/mark_seen.php")
    .then(res => res.text())
    .then(data => {
      if (data.trim() === "ok") {
        const badge = document.querySelector("#notifBell .badge");
        if (badge) badge.remove();
      }
    });
});

document.getElementById("notifBellanalyst").addEventListener("click", function () {
  fetch("../adminplayground/mark_seen_analyst.php")
    .then(res => res.text())
    .then(data => {
      if (data.trim() === "ok") {
        const badge = document.querySelector("#notifBellanalyst .badge");
        if (badge) badge.remove();
      }
    });
});

// Auto-hide message box after 3 seconds
// setTimeout(() => {
//   const box = document.getElementById("msgBox");
//   if (box) {
//     box.classList.add("hide");
//     setTimeout(() => {
//       box.remove();
//       document.getElementById("profileForm").reset();
//     }, 500);
//   }
// }, 3000);