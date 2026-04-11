<?php mysqli_report(MYSQLI_REPORT_OFF); ?>
<style>
  .modal-body .list-group a {
    background-color: #a2a4ffff;
  }
</style>
<!-- Modal -->
<div class="modal fade" id="notifModal" tabindex="-1" aria-labelledby="notifModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notifModalLabel">New Test Reports</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if ($unseen_count > 0): ?>
            <ul class="list-group">
                <?php while($report = mysqli_fetch_assoc($unseen_res)): ?>
                    <a href="../reports/list_reports.php">
                      <li class="list-group-item">
                        <span class="fw-bold">Report ID:</span> <?= htmlspecialchars($report['test_id']) ?> | <span class="fw-bold">Result:</span> <?= $report['results'] ?>
                    </li>
                    </a>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-center text-muted">No new reports</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>


<script>
// When modal opens, mark reports as seen via AJAX
document.getElementById('notifModal').addEventListener('show.bs.modal', function () {
    fetch('../adminplayground/mark_seen.php', { method: 'POST' })
        .then(() => {
            const badge = document.getElementById('notifBadge');
            if(badge) badge.remove(); // remove the badge instantly
        });
});
</script>

