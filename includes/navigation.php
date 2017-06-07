<!-- Navbar -->
<?php
  $query = "SELECT * FROM categories WHERE parent = 0";
  $result = mysqli_query($db, $query);
 ?>

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mynavbar" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="index.php" class="navbar-brand">Abhishar's Store</a>
    </div>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-target="#" data-toggle='dropdown'>Categories<span class="caret"></a>
          <ul class="dropdown-menu multi-level" role="menu">
            <?php
            while($parent = mysqli_fetch_assoc($result)) :
              ?>
              <?php
              $parent_id = $parent['id'];
              $child_query = "SELECT * FROM categories WHERE parent = '$parent_id'";
              $children = mysqli_query($db, $child_query);
              ?>
            <li class="dropdown-submenu">
              <a class='test' tabindex="-1" href="#"><?php echo $parent['category'];?></a>
              <ul class="dropdown-menu">
                <?php while($child = mysqli_fetch_assoc($children)): ?>
                  <li><a href="?filtercat=<?=$child['id']?>" data-trigger="focus"><?php echo $child['category']; ?></a></li>
                <?php endwhile; ?>
              </ul>
            </li>
          <?php endwhile;  ?>
          </ul>
        </li>
      </ul>
      <div class="col-sm-3">
        <form class="navbar-form" role="search">
          <div class="input-group">
              <input type="text" class="form-control" id="search-input" placeholder="Search" name="q">
              <div class="input-group-btn">
                  <button class="btn btn-default" id="search-button" type="submit"><i class="glyphicon glyphicon-search"></i></button>
              </div>
          </div>
        </form>
      </div>
      <ul class="nav navbar-nav navbar-right">
        <?php if (!isset($_SESSION['id'])) {?>
          <li><a class="btn-xs" href="user.php?login">Login</a></li>
          <li><a class="btn-xs" href="user.php?signup">Sign Up</a></li>
        <?php } else { ?>
          <?php
            $id = $_SESSION['id'];
            $query = "SELECT * FROM cart WHERE id = '$id'";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $quant = $row['quantity'];
            $all = explode(',', $quant);
            $count = count($all)-1;
           ?>
          <li><a data-toggle='modal' data-target='#cartModal'><span class="glyphicon glyphicon-shopping-cart"><span class="badge badge-notify"><?= $count; ?></span></a></li>
          <li><a class="btn-xs" href="user.php?logout">Logout</a></li>
        <?php } ?>
      </ul>
      </div>
    </div>
  </div>
</nav>
