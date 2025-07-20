<?php
$page_title = 'Edit product';
require_once ('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
?>
<?php
$product = find_by_id('products', (int) $_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
if (!$product) {
  $session->msg('d', 'Missing product id.');
  redirect('product.php');
}
?>
<?php
if (isset($_POST['product'])) {
  $req_fields = array('product-title', 'product-categorie', 'sizes', 'product-quantity', 'buying-price', 'selling-price', 'remarks', 'supplier', 'or_number','date_purchased', 'unit' );
  validate_fields($req_fields);

  if (empty($errors)) {
    $p_name = remove_junk($db->escape($_POST['product-title']));
    $p_cat = (int) $_POST['product-categorie'];
    $p_sizes = remove_junk($db->escape($_POST['sizes']));
    $p_qty = remove_junk($db->escape($_POST['product-quantity']));
    $p_buy = remove_junk($db->escape($_POST['buying-price']));
    $p_sale = remove_junk($db->escape($_POST['selling-price']));
    $p_remarks = remove_junk($db->escape($_POST['remarks']));
    $p_supplier = remove_junk($db->escape($_POST['supplier']));
    $p_orNumber = remove_junk($db->escape($_POST['or_number']));
    $p_datePurchased = remove_junk($db->escape($_POST['date_purchased']));
    $p_unit = remove_junk($db->escape($_POST['unit']));

    $query = 'UPDATE products SET';
    $query .= " name ='{$p_name}', quantity ='{$p_qty}',";
    $query .= " buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}',remarks='{$p_remarks}', sizes ='{$p_sizes}', supplier ='{$p_supplier}', or_number ='{$p_orNumber}', date_purchased ='{$p_datePurchased}', unit ='{$p_unit}'";
    $query .= " WHERE id ='{$product['id']}'";
    $result = $db->query($query);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', 'Product updated ');
      redirect('product.php', false);
    } else {
      $session->msg('d', ' Sorry failed to updated!');
      redirect('edit_product.php?id=' . $product['id'], false);
    }
  } else {
    $session->msg('d', $errors);
    redirect('edit_product.php?id=' . $product['id'], false);
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
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Edit Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_product.php?id=<?php echo (int) $product['id'] ?>">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']); ?>">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                    <option value=""> Select a categorie</option>
                   <?php foreach ($all_categories as $cat): ?>
                     <option value="<?php echo (int) $cat['id']; ?>" <?php if ($product['categorie_id'] === $cat['id']): echo 'selected'; endif; ?> >
                       <?php echo remove_junk($cat['name']); ?></option>
                   <?php endforeach; ?>
                 </select>
                  <div class="form-group" style="margin-top:15px;">
                         
                          
                            <input type="text" class="form-control" name="sizes" placeholder="Sizes" value="<?php echo remove_junk($product['sizes']); ?>">
                        </div>
                         <div class="form-group" style="margin-top:15px;">
                         
                          
                            <input type="text" class="form-control" name="supplier" placeholder="Supplier" value="<?php echo remove_junk($product['supplier']); ?>">
                        </div>
                         <div class="form-group">
                          <label for="date">Date Purchased</label>
                          <input type="date" class="form-control" name="date_purchased" value="<?php echo isset($product['date_purchased']) ? date('Y-m-d', strtotime($product['date_purchased'])) : ''; ?>">
                        </div>
                  </div>
                    <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="remarks">
                        <option value="">-- Remarks --</option>
                        <option value="New" <?php if ($product['remarks'] === 'New') echo 'selected'; ?>>New</option>
                        <option value="Old" <?php if ($product['remarks'] === 'Old') echo 'selected'; ?>>Old</option>
                     
                      </select>
                        <div class="form-group" style="margin-top:15px;">
                         
                          
                            <input type="text" class="form-control" name="or_number" placeholder="OR number" value="<?php echo remove_junk($product['or_number']); ?>">
                        </div>
                        <div class="form-group" style="margin-top:15px;">
                         
                          
                            <select class="form-control" name="unit">
                            <option value="Pieces" <?php if ($product['unit'] === 'Pieces') echo 'selected'; ?>>Pieces</option>

                        </select>
                        </div>
                      
                      </div>
                        </div>
                      </div>


















              
              
              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Quantity</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                       <i class="glyphicon glyphicon-shopping-cart"></i>
                      </span>
                      <input type="number" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>">
                   </div>
                  </div>
                 </div>
                 <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Buying price</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        &#8369;
                      </span>
                      <input type="number" class="form-control" name="buying-price" value="<?php echo remove_junk($product['buy_price']); ?>">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
                 </div>
                  <div class="col-md-4">
                   <div class="form-group">
                     <label for="qty">Selling price</label>
                     <div class="input-group">
                       <span class="input-group-addon">
                         &#8369;
                       </span>
                       <input type="number" class="form-control" name="selling-price" value="<?php echo remove_junk($product['sale_price']); ?>">
                       <span class="input-group-addon">.00</span>
                    </div>
                   </div>
                  </div>
               </div>
              </div>
              <button type="submit" name="product" class="btn btn-danger">Update</button>
          </form>
         </div>
        </div>
      </div>
  </div>

<?php include_once ('layouts/footer.php'); ?>
