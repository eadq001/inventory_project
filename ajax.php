<?php
require_once ('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index.php', false);
}
?>

<?php
// Auto suggetion
$html = '';
if (isset($_POST['product_name']) && strlen($_POST['product_name'])) {
  $products = find_product_by_title($_POST['product_name']);
  if ($products) {
    foreach ($products as $product):
      $html .= '<li class="list-group-item">';
      $html .= $product['name'];
      $html .= '</li>';
    endforeach;
  } else {
    $html .= '<li onClick=\"fill(\'' . addslashes() . '\')\" class=\"list-group-item\">';
    $html .= 'Not found';
    $html .= '</li>';
  }

  echo json_encode($html);
}
?>
 <?php
// find all product
if (isset($_POST['p_name']) && strlen($_POST['p_name'])) {
  $product_title = remove_junk($db->escape($_POST['p_name']));
  if ($results = find_all_product_info_by_title($product_title)) {
    foreach ($results as $result) {
      $html .= '<tr>';

      $html .= '<td id="s_name">' . $result['name'] . '</td>';
      $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['id']}\">";
      // Fetch category name by ID
      $category_name = '';
      if (!empty($result['categorie_id'])) {
        $category = find_by_id('categories', $result['categorie_id']);
        if ($category) {
          $category_name = $category['name'];
        }
      }
      $html .= '<td>';
      $html .= "<input type=\"text\" class=\"form-control\" name=\"category\" value=\"{$category_name}\" readonly>";
      $html .= '</td>';
      $html .= '<td>';
      $html .= "<input type=\"text\" class=\"form-control\" name=\"sizes\" value=\"{$result['sizes']}\" readonly>";
      $html .= '</td>';
      $html .= '<td>';
      $html .= "<input type=\"text\" class=\"form-control\" name=\"price\" value=\"{$result['sale_price']}\" readonly>";
      $html .= '</td>';
      $html .= '<td id="s_qty">';
      $html .= '<input type="text" class="form-control" name="quantity" value="1">';
      $html .= '</td>';
      $html .= '<td>';
      $html .= "<input type=\"text\" class=\"form-control\" name=\"total\" value=\"{$result['sale_price']}\" readonly>";
      $html .= '</td>';


      $html .= '<td>';
      // Format date as 'July 20, 2025 5:14 pm'
      $formatted_datetime = '';
      if (!empty($result['date'])) {
        $timestamp = strtotime($result['date']);
        $formatted_datetime = date('F j, Y g:i a', $timestamp);
      }
      $html .= '<input type="text" name="product_added" class="form-control" style="background-color:#e9ecef; cursor:not-allowed;" value="'. htmlspecialchars($formatted_datetime) .'">'  ;
      $html .= '</td>';
      $html .= '<td>';
      
      // Format date as 'mm-dd-yyyy h:i am/pm'
      // Display the exact date and time when the 'find it' button is clicked in Hong Kong timezone
      $dt = new DateTime('now', new DateTimeZone('Asia/Hong_Kong'));
      $current_datetime = $dt->format('m-d-Y g:i a');
      $html .= '<input type="text" class="form-control" name="date" style="cursor:not-allowed;" value="' . htmlspecialchars($current_datetime) . '" readonly>';
      $html .= '</td>';
      $remarks_value = '';
      if (!empty($result['categorie_id'])) {
        $remarks = find_by_id('products', $result['id']);
        if ($remarks) {
          $remarks_value = $remarks['remarks'];
        }
      }
      

       
      // Fetch or_number, date_purchased, and supplier from the product table
      $or_value = isset($result['or_number']) ? htmlspecialchars($result['or_number']) : '';
      $date_value = isset($result['date_purchased']) ? htmlspecialchars($result['date_purchased']) : '';
      $supplier_value = isset($result['supplier']) ? htmlspecialchars($result['supplier']) : '';

      $html .= '<td>';
      // Format date_purchased as 'mm-dd-yyyy'
      $formatted_date_purchased = '';
      if (!empty($date_value)) {
        $timestamp = strtotime($date_value);
        $formatted_date_purchased = date('m-d-Y', $timestamp);
      }
      $html .= "<input type=\"text\" class=\"form-control\" name=\"date_purchased\" value=\"{$formatted_date_purchased}\" readonly style=\"background-color:#e9ecef; cursor:not-allowed;\">";
      $html .= '</td>';
      $html .= '<td>';
      $html .= "<input type=\"text\" class=\"form-control\" name=\"supplier\" value=\"{$supplier_value}\" readonly>";
      $html .= '</td>';

      $html .= '<td>';
      $html .= "<input type=\"text\" class=\"form-control\" name=\"or_number\" value=\"{$or_value}\" readonly>";
      $html .= '</td>';

       $html .= '<td>';
      $html .= "<input type=\"text\" class=\"form-control\" name=\"remarks\" value=\"{$remarks_value}\"readonly>";
      $html .= '</td>';

      $html .= '<td>';
      $html .= '<button type="submit" name="add_sale" class="btn btn-primary">Add sale</button>';
      $html .= '</td>';
      $html .= '</tr>';
    }
  } else {
    $html = '<tr><td>product name not register in database</td></tr>';
  }

  echo json_encode($html);
}
?>
