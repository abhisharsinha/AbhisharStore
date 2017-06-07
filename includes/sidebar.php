<div class="col-sm-3">
  <nav class="nav navbar-inverse" id="sidebar">
    <a class="btn" data-toggle="collapse" data-target="#sort-list" style="background-color: #222; color: #ccc; border-bottom: 2px solid #520575; width:100%;">
      <h5 class="text-center"><span class="glyphicon glyphicon-sort"></span> Sort By</h5>
    </a>
    <?php
      if (isset($_GET["brand"])) {
        $add = '&brand='.$_GET['brand'];
      } else if (isset($_GET["filtercat"])) {
        $add = '&filtercat='.$_GET['filtercat'];
      } else {
        $add = "";
      }
    ?>
    <ul class="collapse" id="sort-list" aria-expanded="sort-list">
      <li class="text-center"><a href="index.php?sort=title<?=$add;?>">Name</a></li>
      <li class="text-center"><a href="index.php?sort=price<?=$add;?>">Price</a></li>
      <li class="text-center"><a href="index.php?sort=discount<?=$add;?>">Discount</a></li>
    </ul>
    <br>
    <?php
      $query = "SELECT * FROM brands ORDER BY brand";
      $result = mysqli_query($db, $query);
    ?>
    <a class="btn" data-toggle="collapse" data-target="#brand-list" style="background-color: #222; color: #ccc; border-bottom: 2px solid #520575; width:100%;">
      <h5 class="text-center"><span class="glyphicon glyphicon-filter"></span> Brands</h5>
    </a>
    <ul class="collapse" id="brand-list" aria-expanded="false">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <li class="text-center"><a class="btn btn-sm" href="index.php?brand=<?=$row['id']?>"><?php echo $row['brand']; ?></a></li>
      <?php endwhile; ?>
    </ul>
  </nav>
</div>
