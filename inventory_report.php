<?php
$page_title = 'Inventory Report';
require_once('includes/load.php');
page_require_level(3);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="inventory_report_process.php" autocomplete="off">
      <div class="form-group">
        <label for="start-date">Start Date</label>
        <input type="text" class="form-control datepicker" name="start-date" id="start-date" required>
      </div>
      <div class="form-group">
        <label for="end-date">End Date</label>
        <input type="text" class="form-control datepicker" name="end-date" id="end-date" required>
      </div>
      <button type="submit" name="submit" class="btn btn-primary">Generate Report</button>
    </form>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      autoclose: true
    });
  });
</script>
<?php include_once('layouts/footer.php'); ?>
