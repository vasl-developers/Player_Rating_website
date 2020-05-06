<?php include("../PHP/connection.php"); ?>
<?php include("../config.php"); ?>
<?php include(INCLUDE_PATH . '/logic/userSignup.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>UserAccounts - Sign up</title>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <form class="form" action="signup.php" method="post" enctype="multipart/form-data">
          <h2 class="text-center">Sign up</h2>
          <hr>
          <div class="form-group">
            <label class="control-label">Username</label>
            <input type="text" name="username" class="form-control">
          </div>
          <div class="form-group">
            <label class="control-label">Email Address</label>
            <input type="email" name="email" class="form-control">
          </div>
          <div class="form-group">
            <label class="control-label">Password</label>
            <input type="password" name="password" class="form-control">
          </div>
          <div class="form-group">
            <label class="control-label">Password confirmation</label>
            <input type="password" name="passwordConf" class="form-control">
          </div>
          <div class="form-group" style="text-align: center;">
            <img src="http://via.placeholder.com/150x150" id="profile_img" style="height: 100px; border-radius: 50%" alt="">
            <!-- hidden file input to trigger with JQuery  -->
            <input type="file" name="profile_picture" id="profile_input" value="" style="display: none;">
          </div>
          <div class="form-group">
            <button type="submit" name="signup_btn" class="btn btn-success btn-block">Sign up</button>
          </div>
          <p>Aready have an account? <a href="login.php">Sign in</a></p>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="~/js/display_profile_image.js"></script>
</body>
</html>
