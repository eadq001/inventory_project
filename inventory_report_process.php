<?php
$page_title = 'Inventory Report';
require_once('includes/load.php');
page_require_level(3);
$results = '';
if(isset($_POST['submit'])){
  $req_dates = array('start-date','end-date');
  validate_fields($req_dates);
  if(empty($errors)){
    $start_date = remove_junk($db->escape($_POST['start-date']));
    $end_date = remove_junk($db->escape($_POST['end-date']));
    // Query for inventory report: get all products added/updated between the dates
    $sql = "SELECT p.quantity, c.name AS category, p.name, p.sizes, p.sale_price, (p.quantity * p.sale_price) AS total, p.remarks, p.date FROM products p LEFT JOIN categories c ON c.id = p.categorie_id WHERE DATE(p.date) BETWEEN '{$start_date}' AND '{$end_date}' ORDER BY p.date DESC";
    $results = find_by_sql($sql);
  } else {
    $session->msg("d", $errors);
    redirect('inventory_report.php', false);
  }
} else {
  $session->msg("d", "Select dates");
  redirect('inventory_report.php', false);
}
?>
<!doctype html>
<html lang="en-US">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Inventory Report</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
  <style>
    @media print {
      html,body{ font-size: 9.5pt; margin: 0; padding: 0; }
      .page-break { page-break-before:always; width: auto; margin: auto; }
    }
    .page-break{ width: 980px; margin: 0 auto; }
    .report-head{ margin: 40px 0; text-align: center; }
    .report-head h1,.report-head strong{ padding: 10px 20px; display: block; }
    .report-head h1{ margin: 0; border-bottom: 1px solid #212121; }
    .table>thead:first-child>tr:first-child>th{ border-top: 1px solid #000; }
    table thead tr th { text-align: center; border: 1px solid #ededed; }
    table tbody tr td{ vertical-align: middle; }
    .report-head,table.table thead tr th,table tbody tr td,table tfoot tr td{ border: 1px solid #212121; white-space: nowrap; }
    .report-head h1,table thead tr th,table tfoot tr td{ background-color: #f8f8f8; }
    tfoot{ color:#000; text-transform: uppercase; font-weight: 500; }
  </style>
</head>
<body>
<?php if($results): ?>
  <div class="page-break">
    <div class="report-head">
      <h1>Merchandise Inventory System - Inventory Report</h1>
      <strong><?php if(isset($start_date)){ echo $start_date;}?> TILL DATE <?php if(isset($end_date)){echo $end_date;}?> </strong>
    </div>
    <table class="table table-border">
      <thead>
        <tr>
          <th>Quantity</th>
          <th>Category</th>
          <th>Product Name</th>
          <th>Sizes</th>
          <th>Selling Price</th>
          <th>Total</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($results as $row): ?>
        <tr>
          <td class="text-center"><?php echo remove_junk($row['quantity']);?></td>
          <td><?php echo remove_junk($row['category']);?></td>
          <td><?php echo remove_junk($row['name']);?></td>
          <td><?php echo remove_junk($row['sizes']);?></td>
          <td class="text-right"><?php echo remove_junk($row['sale_price']);?></td>
          <td class="text-right"><?php echo remove_junk($row['total']);?></td>
          <td><?php echo remove_junk($row['remarks']);?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else:
  $session->msg("d", "Sorry no inventory records found.");
  redirect('inventory_report.php', false);
endif; ?>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>
