<?php
  $page_title = 'All User';
  require_once('includes/load.php');
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(1);
//pull out all user form database
 $all_users = find_all_user();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Users</span>
       </strong>
         <a href="add_user.php" class="btn btn-info pull-right">Add New User</a>
      </div>
     <div class="panel-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">#</th>
            <th>Name </th>
            <th>Username</th>
            <th class="text-center" style="width: 15%;">User Role</th>
            <th class="text-center" style="width: 10%;">Status</th>
            <th style="width: 20%;">Last Login</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_users as $a_user): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td><?php echo remove_junk(ucwords($a_user['name']))?></td>
           <td><?php echo remove_junk(ucwords($a_user['username']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_user['group_name']))?></td>
           <td class="text-center">
           <?php if($a_user['status'] === '1'): ?>
            <span class="label label-success"><?php echo "Active"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?php echo "Deactive"; ?></span>
          <?php endif;?>
           </td>
           <td><?php echo read_date($a_user['last_login'])?></td>
           <td class="text-center">
             <div class="btn-group">
                <a href="edit_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
                <!-- Delete Button triggers modal -->
                <?php if(!($a_user['group_name'] === 'admin' && $a_user['id'] == $user['id']) && $a_user['id'] != $user['id']): ?>
                  <button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#deleteUserModal<?php echo (int)$a_user['id'];?>" title="Remove">
                    <i class="glyphicon glyphicon-remove"></i>
                  </button>
                <?php endif; ?>

                <!-- Modal -->
                <div class="modal fade" id="deleteUserModal<?php echo (int)$a_user['id'];?>" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel<?php echo (int)$a_user['id'];?>" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title" id="deleteUserModalLabel<?php echo (int)$a_user['id'];?>">Confirm Delete</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    Are you sure you want to delete user <strong><?php echo remove_junk(ucwords($a_user['name']))?></strong>?
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="delete_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-danger">Delete</a>
                    </div>
                  </div>
                  </div>
                </div>
                </div>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>
  <?php include_once('layouts/footer.php'); ?>
