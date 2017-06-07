<?php
  require_once '../core/init.php';
  $id = $_POST['id'];
  $id = (int)$id;
  $query = "SELECT * FROM products WHERE id = '$id'";
  $rows = mysqli_query($db, $query);
  $product = mysqli_fetch_assoc($rows);

  $brand_id = $product['brand'];
  $brand_query = "SELECT brand FROM brands where id = '$brand_id'";
  $brand_result = mysqli_query($db, $brand_query);
  $brand = mysqli_fetch_assoc($brand_result);

  $sizestring = $product['size'];
  $size_array = explode(',', $sizestring);
 ?>

<!-- Details Modal -->
<?php ob_start(); ?>
<div class="modal fade details-1" id="details-modal" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onclick="closeModal()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title text-center"><?php echo $product['title']; ?></h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <div class="center-block">
                <img src="<?php echo $product['image']; ?>" alt="<?=$product['title'];?>" class="details img-responsive">
              </div>
            </div>
            <div class="col-sm-6">
              <h4>Details</h4>
              <p><?php echo $product['description']; ?></p>
              <hr>
              <p>Price: ₹<?php echo $product['price']; ?></p>
              <p>Brand: <?php echo $brand['brand']; ?></p>
              <form class="form-horizontal" action="includes/add2cart.php" method="post">
                <div class="form-group">
                  <div class="col-xs-2">
                    <label for="quantity" class="control-label">Quantity:</label>
                  </div>
                  <div class="col-xs-2">
                    <input type="text" name="quantity" class="form-control" id="quantity">
                  </div>
                  <br>
                </div>
                <input type="text" name="pid" value="<?=$id;?>" hidden>
                <!-- <p>Available: 7</p> -->
                <div class="form-group">
                  <div class="col-xs-2">
                    <label for="size" class="control-label">Size: </label>
                  </div>
                  <div class="col-xs-3">
                    <select class="form-control" name="size" id="size">
                      <option value=""></option>
                      <?php foreach ($size_array as $size_quant) {
                        $string_array = explode(':', $size_quant);
                        $size = $string_array[0];
                        $quantity = $string_array[1];
                        echo '<option value="'.$size.'">'.$size.' ('.$quantity.' available'.'</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <button type="submit" name="clickme" id="clickme" hidden></button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="closeModal();">Close</button>
        <button type="submit" class="btn btn-warning" onclick="quantize();jQuery('#clickme').click();">Add to Cart <span class="glyphicon glyphicon-shopping-cart"></span></button>
      </div>
    </div>
  </div>
</div>
<script>
// BUG: The modal closes but does not clear the old data when pressed esc key or clicked outside the modal
  function closeModal() {
    jQuery('#details-modal').modal('hide');
    setTimeout(function() {
      jQuery('#details-modal').remove();
    },500);
  }
  function quantize() {
    q = jQuery('#quantity');
    s = jQuery('#size');
    str = "";
    str = s.val() + ':' + q.val();
    q.val(str);
    alert(str);
  }
</script>
<?php echo ob_get_clean(); ?>
