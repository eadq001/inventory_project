<?php
$page_title = 'Add Product';
require_once ('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
$all_categories = find_all('categories');
$all_photo = find_all('media');
?>
<?php
if (isset($_POST['add_product'])) {
  $req_fields = array('product-title', 'product-categorie', 'remarks', 'sizes', 'product-quantity', 'buying-price', 'selling-price');
  validate_fields($req_fields);
  if (empty($errors)) {
    $p_name = remove_junk($db->escape($_POST['product-title']));
    $p_cat = remove_junk($db->escape($_POST['product-categorie']));
    $p_qty = remove_junk($db->escape($_POST['product-quantity']));
    $p_buy = remove_junk($db->escape($_POST['buying-price']));
    $p_sale = remove_junk($db->escape($_POST['selling-price']));
    $p_remarks = remove_junk($db->escape($_POST['remarks']));
    $p_sizes = remove_junk($db->escape($_POST['sizes']));
    $p_supplier = remove_junk($db->escape($_POST['supplier']));
    $p_orNumber = remove_junk($db->escape($_POST['or_number']));
    $p_datePurchased = remove_junk($db->escape($_POST['date_purchased']));
    $p_unit = remove_junk($db->escape($_POST['unit']));
    $date = make_date();
    // Check for duplicate product with same name, category, sizes, and remarks
    $dup_check_sql = "SELECT id FROM products WHERE name='{$p_name}' AND categorie_id='{$p_cat}' AND sizes='{$p_sizes}' AND remarks='{$p_remarks}' LIMIT 1";
    $dup_result = $db->query($dup_check_sql);
    if ($db->num_rows($dup_result) > 0) {
      $session->msg('d', 'Product already exists with the same name, category, sizes, and remarks!');
      redirect('add_product.php', false);
    }
    // Remove ON DUPLICATE KEY UPDATE to allow multiple products with same name but different category, sizes, or remarks
    $query = 'INSERT INTO products (';
    $query .= ' name,quantity,buy_price,sale_price,categorie_id,remarks,sizes, date,supplier, or_number, date_purchased,unit';
    $query .= ') VALUES (';
    $query .= " '{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$p_remarks}','{$p_sizes}', '{$date}', '{$p_supplier}', '{$p_orNumber}', '{$p_datePurchased}', '{$p_unit}'";
    $query .= ')';
    if ($db->query($query)) {
      $session->msg('s', 'Product added ');
      redirect('add_product.php', false);
    } else {
      $session->msg('d', ' Sorry failed to added!');
      redirect('product.php', false);
    }
  } else {
    $session->msg('d', $errors);
    redirect('add_product.php', false);
  }
}

?>
<?php include_once ('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Item Description">
               </div>
                
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">Select Type of Merchandise</option>
                      <?php foreach ($all_categories as $cat): ?>
                        <option value="<?php echo (int) $cat['id'] ?>">
                          <?php echo $cat['name'] ?></option>
                          <?php endforeach; ?>
                        </select>
                        <div class="form-group" style="margin-top:15px;">
                         
                          
                            <input type="text" class="form-control" name="sizes" placeholder="Sizes">
                        </div>
                        <div class="form-group" style="margin-top:15px;">
                         
                          
                            <input type="text" class="form-control" name="supplier" placeholder="Supplier">
                        </div>
                          <div class="form-group">
                         
                            <label for="date">Date Purchased</label>
                            <input type="date" class="form-control" name="date_purchased" >
                        </div>
                      </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="remarks">
                        <option value="">-- Remarks --</option>
                        <option value="New">New</option>
                        <option value="Old">Old</option>
                        </select>
                        <div class="form-group" style="margin-top:15px;">
                         
                          
                            <input type="text" class="form-control" name="or_number" placeholder="OR number">
                        </div>
                        <div class="form-group" style="margin-top:15px;">
                         
                          
                            <select class="form-control" name="unit">
                        <option value="Pieces">Pieces</option>
                        </select>
                        </div>
                      
                      </div>
                        </div>
                      </div>
                      
          














              </div>
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity">
                  </div>
                 </div>
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                       &#8369;
                     </span>
                     <input type="number" class="form-control" name="buying-price" placeholder="Buying Price">
                     <span class="input-group-addon">.00</span>
                  </div>
                 </div>
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        &#8369;
                      </span>
                      <input type="number" class="form-control" name="selling-price" placeholder="Selling Price">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
               </div>
              </div>
              <button type="submit" name="add_product" class="btn btn-danger">Add product</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once ('layouts/footer.php'); ?>
