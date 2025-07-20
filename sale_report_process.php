<?php
$page_title = 'Sales Report';
$results = '';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
  if(isset($_POST['submit'])){
    $req_dates = array('start-date','end-date');
    validate_fields($req_dates);

    if(empty($errors)):
      $start_date   = remove_junk($db->escape($_POST['start-date']));
      $end_date     = remove_junk($db->escape($_POST['end-date']));
      $results      = find_sale_by_dates($start_date,$end_date);
    else:
      $session->msg("d", $errors);
      redirect('sales_report.php', false);
    endif;

  } else {
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
  }
?>
<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Sales Report</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
   <style>
   @media print {
     html,body{
        font-size: 9.5pt;
        margin: 0;
        padding: 0;
     }.page-break {
       page-break-before:always;
       width: auto;
       margin: auto;
      }
    }
    .page-break{
      width: 980px;
      margin: 0 auto;
    }
     .sale-head{
       margin: 40px 0;
       text-align: center;
     }.sale-head h1,.sale-head strong{
       padding: 10px 20px;
       display: block;
     }.sale-head h1{
       margin: 0;
       border-bottom: 1px solid #212121;
     }.table>thead:first-child>tr:first-child>th{
       border-top: 1px solid #000;
      }
      table thead tr th {
       text-align: center;
       border: 1px solid #ededed;
     }table tbody tr td{
       vertical-align: middle;
     }.sale-head,table.table thead tr th,table tbody tr td,table tfoot tr td{
       border: 1px solid #212121;
       white-space: nowrap;
     }.sale-head h1,table thead tr th,table tfoot tr td{
       background-color: #f8f8f8;
     }tfoot{
       color:#000;
       text-transform: uppercase;
       font-weight: 500;
     }
   </style>
</head>
<body>
  <?php if($results): ?>
    <div class="page-break">
       <div class="sale-head">
           <h1>Merchandise Inventory System - Sales Report</h1>
           <strong><?php if(isset($start_date)){ echo $start_date;}?> TILL DATE <?php if(isset($end_date)){echo $end_date;}?> </strong>
       </div>
      <table class="table table-border">
        <thead>
          <tr>
              <th>Name</th>
              <th>Categories</th>
              <th>Sizes</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Total</th>
              <th>Date</th>
              <th>Product Added</th>
              <th>Date Purchased</th>
              <th>Supplier</th>
              <th>OR Number</th>
              <th>Remark</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results as $result): ?>
           <tr>
              <td><?php echo remove_junk($result['name']);?></td>
              <td><?php echo remove_junk($result['categories'] ?? $result['category'] ?? ''); ?></td>
              <td><?php echo remove_junk($result['sizes'] ?? ''); ?></td>
              <td class="text-right"><?php echo remove_junk($result['qty'] ?? $result['total_sales'] ?? ''); ?></td>
              <td class="text-right"><?php echo remove_junk(($result['price'] ?? $result['sale_price'] ?? 0) * ($result['qty'] ?? $result['total_sales'] ?? 0)); ?></td>
              <td class="text-right"><?php echo remove_junk($result['price'] ?? $result['sale_price'] ?? ''); ?></td>
              <td class="text-right"><?php echo remove_junk($result['date']);?></td>
              <td class="text-right"><?php echo remove_junk($result['product_added'] ?? ''); ?></td>
              <td class="text-right"><?php echo remove_junk($result['date_purchased'] ?? ''); ?></td>
              <td class="text-right"><?php echo remove_junk($result['supplier'] ?? ''); ?></td>
              <td class="text-right"><?php echo remove_junk($result['or_number'] ?? ''); ?></td>
              <td class="text-right"><?php echo remove_junk($result['remarks'] ?? ''); ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
         <tr class="text-right">
           <td colspan="4"></td>
           <td colspan="1">Grand Total</td>
           <td> &#8369;
           <?php echo number_format(total_price($results)[0], 2);?>
          </td>
         </tr>
         <tr class="text-right">
           <td colspan="4"></td>
           <td colspan="1">Profit</td>
           <td> &#8369; <?php echo number_format(total_price($results)[1], 2);?></td>
         </tr>
        </tfoot>
      </table>
    </div>
  <?php
    else:
        $session->msg("d", "Sorry no sales has been found. ");
        redirect('sales_report.php', false);
     endif;
  ?>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>
