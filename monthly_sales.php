<?php
$page_title = 'Monthly Sales';
require_once('includes/load.php');
page_require_level(3);

// Get current month and year
$year = date('Y');
$month = date('m');
// Query sales for the current month (date stored as mm-dd-yyyy, so extract month and year from string)
$sql = "SELECT s.*, p.name FROM sales s LEFT JOIN products p ON s.product_id = p.id WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(s.date, ' ', 1), '-', 1) = '{$month}' AND SUBSTRING_INDEX(SUBSTRING_INDEX(s.date, ' ', 1), '-', -1) = '{$year}' ORDER BY STR_TO_DATE(s.date, '%m-%d-%Y') DESC";
$sales = find_by_sql($sql);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>MONTHLY SALES</span>
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
