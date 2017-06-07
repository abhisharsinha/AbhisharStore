<?php
  require_once 'core/init.php';
  session_start();

  include_once 'helpers/helpers.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/header_full.php';
  include 'includes/sidebar.php';
?>

      <!-- Sort -->
      <!-- Filter -->
      <?php
        if (isset($_GET["brand"])) {
          $brand = $_GET["brand"];
          $brand = sanitize($brand);
          $query = "SELECT * FROM brands WHERE id = '$brand'";
          $result = mysqli_query($db, $query);
          $row = mysqli_fetch_assoc($result);
          $title = $row["brand"];
          // <!-- Sort -->
          if (isset($_GET["sort"])) {
            $order = $_GET["sort"];
            $order = sanitize($order);
            if ($order=="discount") {
              $order = "(list_price - price) DESC";
            }
            $query = "SELECT * FROM products WHERE brand = '$brand' ORDER BY $order";
          }
          else {
            $query = "SELECT * FROM products WHERE brand = '$brand'";
          }
          $result = mysqli_query($db, $query);
        }
        else if (isset($_GET['filtercat'])) {
          $catid = $_GET['filtercat'];
          $catid = sanitize($catid);
          $query = "SELECT * FROM categories WHERE id='$catid'";
          $result = mysqli_query($db, $query);
          $row = mysqli_fetch_assoc($result);
          $title = $row['category'];

          // <!-- Sort -->
          if (isset($_GET["sort"])) {
            $order = $_GET["sort"];
            $order = sanitize($order);
            if ($order=="discount") {
              $order = "(list_price - price) DESC";
            }
            $query = "SELECT * FROM products WHERE categories = '$catid' ORDER BY $order";
          }
          else {
            $query = "SELECT * FROM products WHERE categories = '$catid'";
          }
          // $query = "SELECT * FROM products WHERE categories = '$catid'";
          $result = mysqli_query($db, $query);
        }
        else {
          $title = "Featured Products";
          $title = sanitize($title);
          if (isset($_GET["sort"])) {
            $order = $_GET["sort"];
            $order = sanitize($order);
            if ($order=="discount") {
              $order = "(list_price - price) DESC";
            }
            $query = "SELECT * FROM products WHERE featured = 1 ORDER BY $order";
          }
          else {
            $query = "SELECT * FROM products WHERE featured = 1";
          }
          $result = mysqli_query($db, $query);
        }
      ?>

      <!-- Products -->
      <div class="col-sm-9">
        <div class="row">
          <h2 class="text-center" id="featured-head"><?php echo $title; ?></h2>
          <hr>
          <?=((mysqli_num_rows($result) ==0)?'<h4 class="text-center">No products to show</h4>':'');?>
          <?php
            while ($product = mysqli_fetch_assoc($result)) :
           ?>
          <div class="col-md-4 col-sm-6 col-xs-12 product-tab text-center">
            <h4 class="text-center"><?php echo $product['title'] ?></h4>
            <div>
              <img class="product-img-thumb img-responsive" src='<?php echo $product['image'] ?>'>
            </div>
            <p class="list-price text-danger">List Price ₹<s><?php echo $product["list_price"]; ?></s></p>
            <p class="price">Our Price ₹<?php echo $product["price"]; ?></p>
            <button type="button" class="btn btn-small btn-success pull-right details-btn" onclick="detailsmodal(<?= $product['id']; ?>)">Details</button>
          </div>
          <?php
            endwhile;
           ?>
        </div>
      </div>
    <!-- Close row -->
    </div>
  <!-- Close main-page -->
  </div>
  <?php
    include 'includes/footer.php';
   ?>
