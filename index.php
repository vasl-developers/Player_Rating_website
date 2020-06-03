<html lang="en">
<?php set_include_path($_SERVER['DOCUMENT_ROOT']); ?>
<?php include("web/include/header.php"); ?>
<body>
<?php include("web/include/navbar.htm"); ?>
<div class="home container-fluid">
  <div class="row">
    <?php include("web/include/left-sidebar.php"); ?>
    <div class="main-content col-md-8">

      <?php include("web/include/home.php"); ?>

    </div>
    <?php include("web/include/right-sidebar.php"); ?>
  </div>
</div>
<?php include("web/include/footer.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="web/include/ready.js"></script>
</body>
</html>
