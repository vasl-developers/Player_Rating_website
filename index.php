<html lang="en">
<?php set_include_path($_SERVER['DOCUMENT_ROOT']); ?>
<?php include("web/include/header.php"); ?>

<body>
  <?php include("web/include/navbar.htm"); ?>
  <div class="home container-fluid">
    <div class="row">
      <div class="main-content col-md-10 offset-md-1">
        <?php include("web/include/home.php"); ?>
      </div>
    </div>
  </div>
  <?php include("web/include/footer.php"); ?>
</body>

</html>
