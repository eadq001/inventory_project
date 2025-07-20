<?php
$page_title = 'Daily Sales';
require_once('includes/load.php');
page_require_level(3);

// Get current month and year
$year = date('Y');
$month = date('m');

// Ensure database connection is established
if (!isset($db)) {
  global $db;
  require_once('includes/load.php');
}

// Query sales for the current month (use DATE_FORMAT for robust matching)
$sql = "SELECT s.*, p.name, p.categorie_id, c.name AS category FROM sales s LEFT JOIN products p ON s.product_id = p.id LEFT JOIN categories c ON p.categorie_id = c.id WHERE DATE_FORMAT(s.date, '%Y-%m') = '{$year}-{$month}' ORDER BY s.date DESC";
$sales = find_by_sql($sql);

// If $sales is false or not an array, set it to an empty array to avoid errors in foreach
if (!$sales || !is_array($sales)) {
  $sales = [];
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Monthly Sales</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Item Description</th>
              <th class="text-center" style="width: 10%;">Type of Merch</th>
              <th class="text-center" style="width: 10%;">Sizes</th>
              <th class="text-center" style="width: 10%;">Quantity</th>
              <th class="text-center" style="width: 10%;">Total</th>
              <th class="text-center" style="width: 10%;">Price</th>
              <th class="text-center" style="width: 10%;">Sell Date</th>
              <th class="text-center" style="width: 10%;">Product Added</th>
              <th class="text-center" style="width: 10%;">Date Purchased</th>
              <th class="text-center" style="width: 10%;">Supplier</th>
              <th class="text-center" style="width: 10%;">OR Number</th>
              <th class="text-center" style="width: 10%;">Remarks</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sales as $sale):?>
            <tr>
              <td class="text-center"><?php echo count_id();?></td>
              <td><?php echo remove_junk($sale['name']); ?></td>
              <td><?php echo remove_junk($sale['category'] ?? ''); ?></td>
              <td><?php echo remove_junk($sale['sizes'] ?? ''); ?></td>
              <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
              <td class="text-center"><?php echo remove_junk($sale['price'] * $sale['qty']); ?></td>
              <td class="text-center"><?php echo remove_junk($sale['price']); ?></td>
              <td class="text-center"><?php echo$sale['date']; ?></td>
              <td class="text-center"><?php echo remove_junk($sale['product_added'] ?? ''); ?></td>
              <td class="text-center"><?php echo remove_junk($sale['date_purchased'] ?? ''); ?></td>
              <td class="text-center"><?php echo remove_junk($sale['supplier'] ?? ''); ?></td>
              <td class="text-center"><?php echo remove_junk($sale['or_number'] ?? ''); ?></td>
              <td class="text-center"><?php echo remove_junk($sale['remarks'] ?? ''); ?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
