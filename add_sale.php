<?php
  $page_title = 'Add Sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php

  if(isset($_POST['add_sale'])){
    $req_fields = array('s_id','category','sizes','quantity','price','total', 'date', 'remarks','date_purchased','product_added', 'supplier', 'or_number' );
    validate_fields($req_fields);
        if(empty($errors)){
          $p_id      = $db->escape((int)$_POST['s_id']);
          $s_qty     = $db->escape((int)$_POST['quantity']);
          $s_categorie  = $db->escape($_POST['category']);
          $s_sizes  = $db->escape($_POST['sizes']);
          $s_total   = $db->escape($_POST['total']);
          $s_date      = $db->escape($_POST['date']);
          $s_remarks      = $db->escape($_POST['remarks']);
          $s_date_purchased      = $db->escape($_POST['date_purchased']);
          $s_product_added      = $db->escape($_POST['product_added']);
          $s_supplier     = $db->escape($_POST['supplier']);
          $s_or_number    = $db->escape($_POST['or_number']);

          $sql  = "INSERT INTO sales (";
          $sql .= " product_id,category,sizes,qty,price,date,remarks,date_purchased,product_added,supplier,or_number";
          $sql .= ") VALUES (";
          $sql .= "'{$p_id}','{$s_categorie}','{$s_sizes}','{$s_qty}','{$s_total}','{$s_date}','{$s_remarks}','{$s_date_purchased}','{$s_product_added}','{$s_supplier}','{$s_or_number}' ";
          $sql .= ")";

                if($db->query($sql)){
                  update_product_qty($s_qty,$p_id);
                  $session->msg('s',"Sale added. ");
                  redirect('add_sale.php', false);
                } else {
                  $session->msg('d',' Sorry failed to add!');
                  redirect('add_sale.php', false);
                }
        } else {
           $session->msg("d", $errors);
           redirect('add_sale.php',false);
        }
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Find It</button>
            </span>
            <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Search for product name">
         </div>
         <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Sale Edit</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
         <table class="table table-bordered">
           <thead>
            <th> Item Description</th>
            <th> Type of Merch </th>
            <th> Sizes</th>
            <th> Price </th>
            <th> Qty </th>
            <th> Total </th>
            <th> Product Added</th>
            <th> Sell Date</th>
            <th> Date Purchased</th>
            <th> Supplier</th>
            <th> OR Number</th>
            <th> Remarks</th>
            <th> Action</th>
           </thead>
             <tbody  id="product_info">
              <!-- This row will be dynamically filled by AJAX after clicking 'Find It' -->
             </tbody>
         </table>
       </form>
      </div>
    </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>
