<!-- Edit Modal -->
<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';

  if (isset($_POST['edit'])) {
    $edit_id = $_POST['edit'];
    $edit_get_query = "SELECT * FROM categories WHERE id = '$edit_id'";
    $edit_get_result = mysqli_query($db, $edit_get_query);
    $edit_get_row = mysqli_fetch_assoc($edit_get_result);
    $edit_name = $edit_get_row['category'];
    echo $edit_name;

    ob_start();
    ?>
      <div class="modal fade editmodal" id="editmodal" data-keyboard="true" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title text-center">Edit Category</h4>
            </div>
            <div class="text-center">
              <form class="form-inline" action="categories.php" method="post">
                <div class="form-group">
                  <label for="category"></label>
                  <input aria-hidden='true' name='edit_id' hidden='true' value="<?=$edit_id;?>">
                  <input placeholder="<?=$edit_name;?>" type="text" id="brand" name="category" class="form-control" value="<?=$edit_name;?>">
                </div>
                <button type="submit" name="editcat" class="btn btn-success">Confirm</button>
                <a href="categories.php" class="btn btn-md btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" onclick="closeModal()">Close</button>
            </div>
          </div>
        </div>
      </div>
      <script>
      </script>
<?php echo ob_get_clean(); }?>
