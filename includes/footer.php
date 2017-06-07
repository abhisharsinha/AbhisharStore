</div>

<!-- Cart Modal -->
<div id="cartModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Shopping Cart</h4>
      </div>
      <div class="modal-body">
        <table class="table table-hover">
          <thead>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th></th>
          </thead>
          <?php
            $id = $_SESSION['id'];
            $query = "SELECT * FROM cart WHERE id = '$id'";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $product = $row['products'];
            $quantity = $row['quantity'];
            $productArray = explode(",", $product);
            $quantityArray = explode(',', $quantity);
            $total = 0;
           ?>
           <tbody>

           <?php for ($i=0; $i<count($productArray)-1;$i++) { ?>
             <?php
                $query = "SELECT * FROM products WHERE id = '$productArray[$i]'";
                $result = mysqli_query($db, $query);
                $row = mysqli_fetch_assoc($result);
                $pname = $row['title'];
                $pcost = $row['price'];
                $qarry = explode(":", $quantityArray[$i]);
                $total += intval($pcost)*intval($qarry[1]);
              ?>
              <tr onclick="jQuery(this).style('background-color: green;');">
                <td><?= $pname; ?></td>
                <td><?= $qarry[1]; ?></td>
                <td>₹<?= $pcost*$qarry[1]; ?></td>
                <td>
                  <a href="#"><span class="glyphicon glyphicon-pencil text-warning"></span></a>
                  <a href="#"><span class="glyphicon glyphicon-remove text-danger"></span></a>
                  <!-- <a href="#" onclick="delPro(<?= $productArray[$i]; ?>);"><span class="glyphicon glyphicon-remove text-danger"></span></a> -->
                </td>
              </tr>
           <?php } ?>
           <tr>
             <td></td>
             <td><strong>Total:</strong></td>
             <td>₹<?= $total; ?></td>
             <td></td>
           </tr>
         </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Continue Shopping</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Checkout</button>
      </div>
    </div>
  </div>
</div>

<!-- This is same as div -->
<footer class="text-center" id="footer">
  &copy; Copyright 2017-18 Abhishar's Store
</footer>
<!-- scripts -->
<script>

  //MOdal
  function detailsmodal(id) {
    var data = {'id':id };
    jQuery.ajax({
      url: 'includes/details_modal.php',
      method: 'post',
      data: data,
      success: function(data){
        $('body').append(data);
        $('#details-modal').modal('toggle');
      },
      error: function(){
        alert("Oops, something went wrong!");
      }
    });
  }

  $(document).ready(function(){
    $('.dropdown-submenu a.test').on("click", function(e){
      $(this).next('ul').toggle();
      e.stopPropagation();
      e.preventDefault();
    });
  });

  $(document).ready(function(){
    if ($(window).width() < 600) {
      $(".details-btn").removeClass('pull-right');
      $(".details-btn").addClass("form-control");
    }
    else if ($(window).width() < 800) {
      $(".details-btn").removeClass('pull-right');
      $(".details-btn").removeClass("form-control");
      $(".details-btn").addClass("text-center");
    }
  });

  $(window).resize(function(){
    if ($(window).width() < 400) {
      $(".details-btn").removeClass('pull-right');
      $(".details-btn").removeClass("text-center");
      $(".details-btn").addClass("form-control");
    }
    else if ($(window).width() < 800) {
      $(".details-btn").removeClass('pull-right');
      $(".details-btn").removeClass("form-control");
      $(".details-btn").addClass("text-center");
    }
    else {
      $(".details-btn").removeClass("form-control");
      $(".details-btn").removeClass("text-center");
      $(".details-btn").addClass('pull-right');
    }
  });

  function delPro(id) {
    var data = {'id':id };
    console.log(id);
    jQuery.ajax({
      url: 'includes/cart_delete.php',
      method: 'post',
      data: data,
      success: function(data){
        $('body').append(data);
        $('#details-modal').modal('toggle');
      },
      error: function(){
        alert("Oops, something went wrong!");
      }
    });
  }
</script>

</body>
</html>
