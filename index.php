

<?php
  require_once('includes/load.php');
  ob_start();
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DLSJBC MIS</title>
  <link rel="stylesheet" href="libs/css/index.css" />
</head>
<body>
  <div class="container">
    <img src="libs/images/dlsjbc-logo.png" alt="Logo" class="logo" />
    <h1>MERCHANDISE INVENTORY SYSTEM</h1>
<div class="login-page">
     <?php echo display_msg($msg); ?>
      <form method="post" action="auth.php" class="clearfix">
        <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input type="name" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Password</label>
            <input type="password" name= "password" class="form-control" placeholder="Password">
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-danger" style="border-radius:0%">Login</button>
        </div>
    </form>
</div>
  </div>
</body>
</html>

