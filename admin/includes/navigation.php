<?php  ?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mynavbar" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="index.php" class="navbar-brand">Abhishar's Store Admin</a>
    </div>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="nav navbar-nav">
          <li><a href="brands.php">Brands</a></li>
          <li><a href="categories.php">Categories</a></li>
          <li><a href="products.php">Products</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php if (isset($_SESSION['admin_id'])): ?>
          <li><a href="index.php?logout=true">Logout</a></li>
        <?php endif; ?>
       </ul>
    </div>
  </div>
</nav>
