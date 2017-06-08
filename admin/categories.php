<?php
  require_once '../core/init.php';
  session_start();
  include 'includes/head.php';
  include 'includes/navigation.php';

  //Authentication
  if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
  }

  $parent_query = "SELECT * FROM categories WHERE parent = 0 ORDER BY category";
 ?>

<?php
  //Add category
  if (isset($_POST['addcat'])) {
    $cat = $_POST['category'];
    $par = $_POST['parent'];
    $addcatquery = "INSERT INTO categories (category, parent) VALUES ('$cat', '$par')";
    mysqli_query($db, $addcatquery);
  }

  //


  //Edit category
  if (isset($_POST['editcat'])) {
    $editid = $_POST['edit_id'];
    $cat = $_POST['category'];
    $edit_query = "UPDATE categories SET category = '$cat' WHERE id='$editid'";
    mysqli_query($db, $edit_query);
  }

  //if category is a parent category delete all sub-categories
  if (isset($_GET['parentdelete'])) {
    $parentdelete_id = $_GET['parentdelete'];
    //If category is not a parent catgory
    $delete_query = "DELETE FROM categories WHERE parent = '$parentdelete_id'";
    mysqli_query($db, $delete_query);
    $delete_query = "DELETE FROM categories WHERE id = '$parentdelete_id'";
    mysqli_query($db, $delete_query);
  }

  //Delete category
  if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    //If category is not a parent catgory
    $delete_query = "DELETE FROM categories WHERE id = '$delete_id'";
    mysqli_query($db, $delete_query);
  }
 ?>

<div class="container-fluid">
  <h1 class="text-center">Categories</h1>
  <hr>
  <div class="row">
    <!--  Form  -->
    <div class="col-md-6">
      <h4>Add a new category</h4>
      <form class="form" action="categories.php" method="post">
        <div class="form-group">
          <!-- Select Parent -->
          <label for="parent">Select Parent Category</label>
          <select class="form-control" name="parent" id="parent">
            <option value="0"></option>
            <?php
            if (isset($_GET['childof'])) {
              $childParent = $_GET['childof'];
            }
             ?>
            <?php   $parent_result = mysqli_query($db, $parent_query); ?>
             <?php while($row = mysqli_fetch_assoc($parent_result)): ?>
               <option value="<?=$row['id'];?>" <?= ((isset($_GET['childof']))?(($row['id'] == $childParent)?'selected':''):''); ?>><?= $row['category'];?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="category">Category</label>
          <input class='form-control' type="text" name="category" id="category">
        </div>

        <button type="submit" name="addcat" class="btn btn-danger">Add</button>
      </form>
    </div>

    <!-- Table -->
    <div class="col-md-6">
      <table class="table table-bordered">
        <thead>
          <th>Category</th>
          <th>Parent</th>
          <th></th>
        </thead>
        <tbody>
          <?php   $parent_result = mysqli_query($db, $parent_query); ?>
          <?php while($row = mysqli_fetch_assoc($parent_result)): ?>
            <tr class="bg-primary">
              <td><?= $row['category']; ?></td>
              <td>Parent</td>
              <td>
                <a style="color:black;" title="Add" href="categories.php?childof=<?=$row['id'];?>"><span class='glyphicon glyphicon-plus'></span></a>
                <a class='btn btn-xs btn-default' title="Edit" onclick="editmodal(<?= $row['id']; ?>)"><span class="glyphicon glyphicon-pencil"></span></a>
                <a class='btn btn-xs btn-default' title="Delete" href="categories.php?parentdelete=<?=$row['id'];?>"><span class='glyphicon glyphicon-remove'></span></a>

              </td>
            </tr>
            <?php
                $parent_id = $row['id'];
                $child_q= "SELECT * FROM categories WHERE parent='$parent_id' ORDER BY category";
                $child_r = mysqli_query($db, $child_q);
              ?>
              <?php while($child_rows = mysqli_fetch_assoc($child_r)): ?>
                <tr class="bg-info">
                  <td><?= $child_rows['category']; ?></td>
                  <td><?=$row['category'];?></td>
                  <td>
                    <a class='btn btn-xs text-primary' title="Edit" onclick="editmodal(<?= $child_rows['id']; ?>)"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a class='btn btn-xs text-danger' title="Delete" href="categories.php?delete=<?=$child_rows['id'];?>"><span class='glyphicon glyphicon-remove'></span></a>
                  </td>
                </tr>
              <?php endwhile; ?>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function editmodal(id) {
  var data = {'edit':id };
  jQuery.ajax({
    url: 'includes/editmodalcat.php',
    method: 'post',
    data: data,
    success: function(data){
      $('body').append(data);
      $('#editmodal').modal('toggle');
    },
    error: function(){
      alert("Oops, something went wrong!");
    }
  });
}


// BUG: The modal closes but does not clear the old data when pressed esc key or clicked outside the modal
function closeModal() {
  jQuery('#editmodal').modal('hide');
  setTimeout(function() {
    jQuery('#editmodal').remove();
  },500);
}
</script>

<?php
  include 'includes/footer.php';
 ?>
