<?php
  require_once '../core/init.php';
  session_start();
  include 'includes/head.php';
  include 'includes/navigation.php';

  //Authentication
  if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
  }

  //get brands from database
  $query = "SELECT * FROM brands ORDER BY brand";
  $result = mysqli_query($db, $query);

  $errors = array();

  //if form for new brand is submited
  if (isset($_POST['add_brand'])) {
    $new_brand = sanitize($_POST['brand']);

    if ($new_brand == '') {
      $errors[] .= 'Enter a brand!';
    }
    //Check if brand already exists
    $check_brand_query = "SELECT * FROM brands WHERE brand='$new_brand'";
    $check_brand_result = mysqli_query($db, $check_brand_query);
    //$check_brand_number = mysqli_num_rows((mysqli_query($db, $check_brand_query)));
    $check_brand_number = mysqli_num_rows($check_brand_result);
    if($check_brand_number > 0){
      $errors[] .= 'Brand already exists';
      echo "<h1>already</h1>";
    }
    //display errors
    if (!empty($errors)) {
      echo display_errors($errors);
    }
    else {
      $add_brand_query = "INSERT INTO brands (brand) VALUES ('$new_brand')";
      mysqli_query($db, $add_brand_query);
      header ('Location: brands.php');
    }
  }

    //Delete brand
    if(isset($_GET['delete']) && !empty($_GET['delete'])){
      $delete_id = (int)$_GET['delete'];
      $delete_id = sanitize($delete_id);
      $delete_brand = "DELETE FROM brands WHERE id = '$delete_id'";
      mysqli_query($db, $delete_brand);
      header ('Location: brands.php');
    }

    //Edit brands
    if (isset($_GET['edit'])) {
      $edit_id = (int)$_GET['edit'];
      $edit_id = sanitize($edit_id);
      $get_brand = "SELECT brand FROM brands WHERE id = '$edit_id'";
      $get_brand_res = mysqli_query($db, $get_brand);
      $get_brand_row = mysqli_fetch_assoc($get_brand_res);
      $brand_value = $get_brand_row['brand'];
    }

    //Cancel Edit
    if (isset($_POST['canceledit'])) {
      echo "<h1>Cancel Edit</h1>";
    }

    if (isset($_POST['edit_brand'])) {
      $new_brand = sanitize($_POST['brand']);

      if ($new_brand == '') {
        $errors[] .= 'Enter a brand!';
      }

      if (!empty($errors)) {
        echo display_errors($errors);
      }

      else {
        $edit_query = "UPDATE brands SET brand='$new_brand' WHERE id = '$edit_id'";
        mysqli_query($db, $edit_query);
        header("Location: brands.php");
      }
  }
?>
<div class="container-fluid">
  <h1 class="text-center">Brands</h1>
  <hr>

  <div class="text-center">
    <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))? '?edit='.$edit_id:'');?>" method="post">
      <div class="form-group">
        <label for="brand">Brand: </label>
        <!-- If edit brand is clicked then placeholder and value both are the name of brand to be edited -->
        <input placeholder="<?=((isset($_GET['edit']))? $brand_value : 'Enter new brand name');?>" type="text" id="brand" name="brand" class="form-control" <?= ((isset($_GET['edit']))?'value="'.$brand_value.'"':'');?>>
      </div>
      <button type="submit" name="<?=((isset($_GET['edit']))? 'edit_brand':'add_brand');?>" class="btn btn-success"><?=((isset($_GET['edit']))? 'Edit':'Add');?> Brand</button>
      <?=((isset($_GET['edit']))?'<a href="brands.php" class="btn btn-md btn-danger""><span class="glyphicon glyphicon-remove"></span></a>':'');?>
    </form>
  </div>
  <hr>
   <table class="table table-bordered table-striped table-auto table-condensed">
     <thead>
       <th></th>
       <th>Brand</th>
       <th></th>
     </thead>
     <tbody>
         <?php while ($brand = mysqli_fetch_assoc($result)): ?>
           <tr>
             <td><a href="brands.php?edit=<?=$brand['id']?>" class="btn btn-xs"><span class="glyphicon glyphicon-pencil"></span></a></td>
             <td><?= $brand['brand']; ?></td>
             <td><a href="brands.php?delete=<?=$brand['id']?>" class="btn btn-xs text-danger"><span class="glyphicon glyphicon-trash"></span></a></td>
           </tr>
          <?php endwhile; ?>
     </tbody>
   </table>
</div>
<?php
  include 'includes/footer.php';
?>
