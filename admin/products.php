<?php
  require_once '../core/init.php';
  session_start();
  include 'includes/head.php';
  include 'includes/navigation.php';

  //Authentication
  if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
  }
?>

<?php
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $get_edit_query = "SELECT * FROM products WHERE id = '$id'";
    $get_edit_result = mysqli_query($db, $get_edit_query);
    $get_edit_row = mysqli_fetch_assoc($get_edit_result);

    $edittitle = $get_edit_row['title'];
    $editprice = $get_edit_row['price'];
    $editlistprice = $get_edit_row['list_price'];
    $editimage = $get_edit_row['image'];
    $editdescription = $get_edit_row['description'];
    $editcategory = $get_edit_row['categories'];
    $editbrand = $get_edit_row['brand'];
    $editsize = $get_edit_row['size'];


    //Sanitization
    $edittitle = sanitize($edittitle);
    $editprice = sanitize($editprice);
    $editlistprice = sanitize($editlistprice);
    $editbrand = sanitize($editbrand);
    $editcategory = sanitize($editcategory);
    $editimage = sanitize($editimage);
    $editdescription = sanitize($editdescription);
    $editsize = sanitize($editsize);

    $query = "SELECT * FROM brands WHERE id = '$editbrand'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    $editbrand = $row['brand'];
  }
 ?>

<?php
  if (isset($_POST['editprod'])) {
    $editthisid = $_POST['editid'];
    $title = $_POST['title'];
    $title = sanitize($title);
    $price = $_POST['price'];
    $price = sanitize($price);
    $list_price = $_POST['list_price'];
    $list_price = sanitize($list_price);
    $brand_name = $_POST['brand'];
    $brand_name = sanitize($brand_name);
    $categories = $_POST['category'];
    $categories = sanitize($categories);
    // $image = $_POST['image'];
    // $image = sanitize($image);
    $size = $_POST['size'];
    $size = sanitize($size);
    $description = $_POST['description'];
    $description = sanitize($description);

    // Image Upload Shenegains
    if (!empty($_FILES))
    {
      $image = $_FILES['image'];
      $name = $image['name'];
      $nameArray = explode('.', $name);
      $fileName = $nameArray[0];
      $fileExt = $nameArray[1];
      $mime = explode("/",$image['type']);
      $mimeType = $mime[0];
      $mimeExt = $mime[1];
      $tmpLoc = $image['tmp_name'];
      $fileSize = $image['size'];
      $allowed = array("jpg", "jpeg", "png", "gif");
      $uploadName = md5(microtime()).'.'.$fileExt;
      $uploadPath = BASEURL.'images/products/'.$uploadName;

      if ($mimeType != "image") {
        $errors[] = "The file must be image!";
      }
      if (!in_array($fileExt, $allowed)) {
        $errors[] = "Only jpg, jpeg, png and gif are allowed!";
      }
      if ($fileSize > 5000000) {
        $errors[] = "File size mult be under 5MB";
      }
      if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
        $errors[] = "File is corrupt!";
      }

      if (!empty($errors)) {
        echo display_errors($errors);
      }  else {
        move_uploaded_file($tmpLoc, $uploadPath);
        $image = 'images/products/'.$uploadName;
      }
    }

    $brand_query = "SELECT * FROM brands WHERE brand = '$brand_name'";
    $brand_result = mysqli_query($db, $brand_query);
    $brand_row = mysqli_fetch_assoc($brand_result);
    $brand = $brand_row['id'];
    $query = "UPDATE products SET title='$title', price='$price', list_price='$list_price', brand='$brand', categories='$categories', image='$image', size='$size', description='$description' WHERE id='$editthisid'";
    mysqli_query($db, $query);
  }
 ?>

<?php
  if (isset($_POST['addprod'])) {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $list_price = $_POST['list_price'];
    $brand_name = $_POST['brand'];
    $categories = $_POST['category'];
    $description = $_POST['description'];
    $size = $_POST['size'];
    $image = "";

    // if (!isset($_POST['image'])) {
    //   $image = $_POST['image'];
    //   $size = sanitize($size);
    // }
    if (empty($_FILES))
    {
      echo "UPLOAD FAILURE!";
    }
    if (!empty($_FILES))
    {
      $image = $_FILES['image'];
      $name = $image['name'];
      $nameArray = explode('.', $name);
      $fileName = $nameArray[0];
      $fileExt = $nameArray[1];
      $mime = explode("/",$image['type']);
      $mimeType = $mime[0];
      $mimeExt = $mime[1];
      $tmpLoc = $image['tmp_name'];
      $fileSize = $image['size'];
      $allowed = array("jpg", "jpeg", "png", "gif");
      $uploadName = md5(microtime()).'.'.$fileExt;
      $uploadPath = BASEURL.'images/products/'.$uploadName;

      if ($mimeType != "image") {
        $errors[] = "The file must be image!";
      }
      if (!in_array($fileExt, $allowed)) {
        $errors[] = "Only jpg, jpeg, png and gif are allowed!";
      }
      if ($fileSize > 5000000) {
        $errors[] = "File size mult be under 5MB";
      }
      if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
        $errors[] = "File is corrupt!";
      }

      if (!empty($errors)) {
        echo display_errors($errors);
      }  else {
        move_uploaded_file($tmpLoc, $uploadPath);
        $image = 'images/products/'.$uploadName;
      }
    }

    //Sanitization
    $title = sanitize($title);
    $price = sanitize($price);
    $list_price = sanitize($list_price);
    $brand_name = sanitize($brand_name);
    $categories = sanitize($categories);
    $image = sanitize($image);
    $description = sanitize($description);

    $brand_query = "SELECT * FROM brands WHERE brand = '$brand_name'";
    $brand_result = mysqli_query($db, $brand_query);

    //If no brand is matched add a new one
    $check_brand_exist = mysqli_num_rows($brand_result);
    if ($check_brand_exist == 0) {
      $add_brand_query = "INSERT INTO brands (brand) VALUES ('$brand_name')";
      mysqli_query($db, $add_brand_query);
      $brand_result = mysqli_query($db, $brand_query);
    }

    $brand_row = mysqli_fetch_assoc($brand_result);
    $brand = $brand_row['id'];

    $add_query = "INSERT INTO products (title, price, list_price, brand, categories, image, size, description) VALUES('$title', '$price', '$list_price', '$brand', '$categories', '$image', '$size', '$description')";
    mysqli_query($db, $add_query);
  }
 ?>

<!-- Set featured products -->
<?php
  if (isset($_GET['feature'])) {
    $id = $_GET['feature'];
    $query = "UPDATE products SET featured = '1' WHERE id = '$id'";
    mysqli_query($db, $query);
  }
  if (isset($_GET['unfeature'])) {
    $id = $_GET['unfeature'];
    $query = "UPDATE products SET featured = '0' WHERE id = '$id'";
    mysqli_query($db, $query);
  }
 ?>

<div class="container-fluid">
  <h1 class="text-center">Products</h1>
  <hr>
  <div class="row">
    <!--  Form  -->
    <div class="col-md-6">
      <?php
        if (isset($_GET['edit'])) {
          echo "<h3 class='text-center'>Edit Product</h3>";
        }
        else
          echo "<h3 class='text-center'>Add a new Product</h3>";
       ?>
       <hr>
      <form class="form" id="addProductForm" action="products.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <!-- Select Parent -->
          <label for="title">Name: </label>
          <input class="form-control" type="text" name="title" id="title" <?=((isset($_GET['edit']))? 'placeholder="'.$edittitle.'" value="'.$edittitle.'" ':'')?> required>
        </div>
        <div class="form-group">
          <label for="list_price">Cost Price: </label>
          <input class="form-control" type="text" name="list_price" id="list_price" <?=((isset($_GET['edit']))?'placeholder="'.$editlistprice.'" value="'.$editlistprice.'" ':'')?> required>
        </div>
        <div class="form-group">
          <label for="price">Selling Price: </label>
          <input class="form-control" type="text" name="price" id="price" <?=((isset($_GET['edit']))?'placeholder="'.$editprice.'" value="'.$editprice.'" ':'')?> required>
        </div>
        <div class="form-group">
          <label for="brand">Brand: </label>
          <input class="form-control" type="text" name="brand" id="brand" <?=((isset($_GET['edit']))?'placeholder="'.$editbrand.'" value="'.$editbrand.'" ':'')?> required>
        </div>
        <div class="form-group">
          <!-- Select Parent -->
          <label for="category">Category</label>
          <select class="form-control" name="category" id="category" required>
            <option value="">Select Category</option>
            <?php
              $query = "SELECT * FROM categories WHERE parent NOT LIKE 0 ORDER BY parent";
              $result = mysqli_query($db, $query);
            ?>
             <?php while($row = mysqli_fetch_assoc($result)): ?>
               <?php
                  $id = $row['parent'];
                  $parent_query = "SELECT * FROM categories WHERE id = '$id'";
                  $parent_result = mysqli_query($db, $parent_query);
                  $parent_row = mysqli_fetch_assoc($parent_result);
               ?>
               <option value="<?=$row['id'];?>" <?=((isset($_GET['edit']))?(($row['id']==$editcategory)?'selected':''):'')?> <?=((isset($_GET['addincat']))?(($row["id"]==$_GET["addincat"])?'selected':''):'');?> > <?=$parent_row['category'];?>:<?= $row['category'];?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="image">Upload Image: </label>
          <input class="form-control" type="file" name="image" id="image" >
          <p>OR</p>
          <!-- <input class="form-control" type="text" name="image" id="image" <?=((isset($_GET['edit']))?'placeholder="'.$editimage.'" value="'.$editimage.'" ':'placeholder="Enter URL"')?> > -->
        </div>
        <div class="form-group">
          <label for="sizebutton">Size and Quantity</label>
          <button type="button" id="sizebutton" class="btn btn-xs" data-toggle="modal" data-target="#sizeModal">Add</button>
        </div>
        <div class="form-group">
          <label for="size">Size & Quantity Preview</label>
          <input type="text" name="size" class="form-control" id="size" value="<?=((isset($_POST['size']))?$_POST['size']:'')?>" readonly>
        </div>
        <div class="form-group">
          <label for="description">Description: </label>
          <textarea class="form-control" name="description" id="description" <?=((isset($_GET['edit']))?'placeholder="'.$editdescription.'"':'')?>><?=((isset($_GET['edit']))?$editdescription:'')?></textarea>
        </div>
        <input hidden='true' aria-hidden='true' name="editid" id="editid" value="<?=$_GET['edit'];?>">
        <?php
          if (isset($_GET['edit'])) {
            echo '<button type="submit" name="editprod" class="btn btn-success">Confirm</button>';
          }
          else
            echo '<button type="submit" name="addprod" class="btn btn-primary">Add</button>';
         ?>
      </form>
    </div>

    <!-- Table -->
    <div class="col-md-6">
      <table class="table table-bordered">
        <thead>
          <th>Category</th>
          <th>Parent</th>
        </thead>
        <tbody>
          <?php
            $parent_query = "SELECT * FROM categories WHERE parent = 0 ORDER BY category";
            $parent_result = mysqli_query($db, $parent_query);
          ?>
          <?php while($row = mysqli_fetch_assoc($parent_result)): ?>
            <?php
                $parent_id = $row['id'];
                $child_q= "SELECT * FROM categories WHERE parent='$parent_id' ORDER BY category";
                $child_r = mysqli_query($db, $child_q);
            ?>
            <?php while($child_rows = mysqli_fetch_assoc($child_r)): ?>
              <tr class="bg-primary">
                <td>
                  <?= $child_rows['category']; ?>
                  <a class='btn btn-xs text-danger pull-right' title="Add Products" href="products.php?addincat=<?=$child_rows['id'];?>"><span class='glyphicon glyphicon-plus'></span></a>
                </td>
                <td><?=$row['category'];?></td>
              </tr>

              <!-- All products of this category -->
              <?php
                $catid = $child_rows['id'];
                $product_query = "SELECT * FROM products WHERE categories = '$catid'";
                $product_results = mysqli_query($db, $product_query);
              ?>
              <?php while($product_rows = mysqli_fetch_assoc($product_results)): ?>
                <tr class="bg-info">
                  <td><?= $product_rows['title']; ?></td>
                  <td>
                    <a class='btn btn-xs text-primary' title="Edit" href="products.php?edit=<?=$product_rows['id'];?>"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a class='btn btn-xs text-danger' title="Delete" href="products.php?delete=<?=$product_rows['id'];?>"><span class='glyphicon glyphicon-remove'></span></a>
                    <?php
                      if ($product_rows['featured'] == 0) {
                        echo '<a class="btn btn-xs text-danger" title="Feature" href="products.php?feature='.$product_rows["id"].'"><span class="glyphicon glyphicon-star-empty"></span></a>';
                      }
                      else {
                        echo '<a class="btn btn-xs text-danger" title="Unfeature" href="products.php?unfeature='.$product_rows["id"].'"><span class="glyphicon glyphicon-star"></span></a>';
                      }
                     ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php endwhile; ?>
          <?php endwhile; ?>
        </tbody>
      </table>
      <!-- Close col md 6 idv -->
    </div>
    <!-- Close row div -->
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="sizeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Size and Quantity</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <?php for ($i=1; $i <= 12; $i++): ?>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="row">
                <div class="col-xs-6">
                  <div class="input-group">
                    <label for="size<?=$i;?>">Size: </label>
                    <input type="text" name="size<?=$i;?>" class="form-control" id="size<?=$i;?>" value="">
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="input-group">
                    <label for="Quantity<?=$i;?>">Quantity: </label>
                    <input type="number" name="Quantity<?=$i;?>" class="form-control" id="Quantity<?=$i;?>" min="0" value="">
                  </div>
                </div>
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSize();jQuery('#sizeModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>


<?php
  include 'includes/footer.php';
?>
