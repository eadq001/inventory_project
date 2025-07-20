<?php
  $page_title = 'Edit sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
$sale = find_by_id('sales',(int)$_GET['id']);
if(!$sale){
  $session->msg("d","Missing product id.");
  redirect('sales.php');
}
?>
<?php $product = find_by_id('products',$sale['product_id']); ?>
<?php

  if(isset($_POST['update_sale'])){
    $req_fields = array('category','sizes','quantity','price','total', 'date', 'remarks','date_purchased','product_added', 'supplier', 'or_number' );
    validate_fields($req_fields);
    if(empty($errors)){
      $p_id = (int)$sale['product_id'];
      $s_qty = $db->escape((int)$_POST['quantity']);
      // Check product stock before proceeding
      $product = find_by_id('products', $p_id);
      if(!$product) {
        $session->msg('d','Product not found!');
        redirect('edit_sale.php?id='.(int)$_GET['id'], false);
      }
      // Get the original sale quantity
      $sale_id = (int)$_GET['id'];
      $sale = find_by_id('sales', $sale_id);
      $original_qty = $sale ? (int)$sale['qty'] : 0;
      $available_qty = $product['quantity'] + $original_qty;
      if($s_qty > $available_qty) {
        $session->msg('d','Not enough stock! Only ' . $available_qty . ' left (including original sale quantity).');
        redirect('edit_sale.php?id='.(int)$_GET['id'], false);
      }

      // Calculate the difference and update product quantity accordingly
      $qty_diff = $s_qty - $original_qty;
      if ($qty_diff > 0) {
        // Deduct from product quantity if selling more
        update_product_qty($qty_diff, $p_id);
      } elseif ($qty_diff < 0) {
        // Add back to product quantity if selling less
        $add_qty = abs($qty_diff);
        $sql_update = "UPDATE products SET quantity = quantity + {$add_qty} WHERE id = '{$p_id}'";
        $db->query($sql_update);
      }

      $p_id      = $db->escape((int)$product['id']);
      $s_qty     = $db->escape((int)$_POST['quantity']);
      $s_total   = $db->escape($_POST['total']);
      $date      = $db->escape($_POST['date']);

      $sql  = "UPDATE sales SET";
      $sql .= " product_id= '{$p_id}',qty={$s_qty},price='{$s_total}',date='{$date}'";
      $sql .= " WHERE id ='{$sale['id']}'";
      $result = $db->query($sql);
      if( $result && $db->affected_rows() === 1){
        $session->msg('s',"Sale updated.");
        redirect('edit_sale.php?id='.$sale['id'], false);
      } else {
        $session->msg('d',' Sorry failed to updated!');
        redirect('sales.php', false);
      }
        } else {
           $session->msg("d", $errors);
           redirect('edit_sale.php?id='.(int)$sale['id'],false);
        }
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Edit Sale</span>
        </strong>
        <div class="pull-right">
          <a href="sales.php" class="btn btn-primary">Show all sales</a>
        </div>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']; ?>">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Item Description</th>
                <th>Type of Merch</th>
                <th>Sizes</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Product Added</th>
                <th>Sell Date</th>
                <th>Date Purchased</th>
                <th>Supplier</th>
                <th>OR Number</th>
                <th>Remarks</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="product_info">
              <tr>
                <td><input type="text" class="form-control" name="title" title="<?php echo remove_junk($product['name']); ?>" value="<?php echo remove_junk($product['name']); ?>" readonly></td>
                <td><input type="text" class="form-control" name="category" value="<?php echo isset($sale['category']) ? remove_junk($sale['category']) : ''; ?>" readonly></td>
                <td><input type="text" class="form-control" name="sizes" value="<?php echo isset($sale['sizes']) ? remove_junk($sale['sizes']) : ''; ?>" readonly></td>
                <td><input type="text" class="form-control" name="price" value="<?php echo remove_junk($sale['price']); ?>" readonly></td>
                <td><input type="text" class="form-control" name="quantity" value="<?php echo (int)$sale['qty']; ?>"></td>
                <td><input type="text" class="form-control" name="total" value="<?php echo remove_junk($sale['price'] * $sale['qty']); ?>" readonly></td>
                <td><input type="text" class="form-control" name="product_added" value="<?php echo isset($sale['product_added']) ? remove_junk($sale['product_added']) : ''; ?>"></td>
                <?php
                  // Set timezone to Hong Kong
                  date_default_timezone_set('Asia/Hong_Kong');
                  // Get current date/time in mm-dd-yyyy hh:mm am/pm format
                  $current_datetime = date('m-d-Y h:i A');
                ?>
                <td>
                  <input type="text" class="form-control" name="date" value="<?php echo $current_datetime; ?>" readonly>
                </td>
               
                <td><input type="text" class="form-control" name="date_purchased" value="<?php echo isset($sale['date_purchased']) ? remove_junk($sale['date_purchased']) : ''; ?>"></td>
                <td><input type="text" class="form-control" name="supplier" value="<?php echo isset($sale['supplier']) ? remove_junk($sale['supplier']) : ''; ?>"></td>
                <td><input type="text" class="form-control" name="or_number" value="<?php echo isset($sale['or_number']) ? remove_junk($sale['or_number']) : ''; ?>"></td>
                <td><input type="text" class="form-control" name="remarks" value="<?php echo isset($sale['remarks']) ? remove_junk($sale['remarks']) : ''; ?>"></td>
                <td><button type="submit" name="update_sale" class="btn btn-primary">Update sale</button></td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
