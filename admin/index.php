<?php
  require_once '../core/init.php';
  session_start();
  include 'includes/head.php';
  include 'includes/navigation.php';
 ?>

<?php
  //Login
  if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    //Sanitization
    $username = sanitize($username);
    $password = sanitize($password);

    $authquery = "SELECT * FROM admin WHERE username = '$username'";
    $authresult = mysqli_query($db, $authquery);
    $auth = mysqli_num_rows($authresult);
    if($auth == 1)
    {
      $authrow = mysqli_fetch_assoc($authresult);
      $id = $authrow["id"];
      if(password_verify($password, $authrow['password']))
      {
        $_SESSION["admin_id"] = $id;
        header("Location: index.php");
      }
      else
      {
          echo "<h4>Incorrect password</h4>";
          echo password_hash($password, PASSWORD_DEFAULT);
      }
    }
    else
    {
      echo "<h4>Incorrect username<h4>";
    }
  }

  //Logout
  if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
  }
 ?>

<?php if (!isset($_SESSION['admin_id'])) { ?>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h4>You need to login to continue!</h4>
      </div>
      <div class="col-md-6">
        <h2>Admin Login</h2>
        <form class="form" action="index.php" method="post">
          <div class="form-group">
            <label for="username">Username: </label>
            <input class="form-control" type="text" name="username" id="username">
          </div>
          <div class="form-group">
            <label for="password">Password: </label>
            <input class="form-control" type="password" name="password" id="password">
          </div>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </form>
      </div>
    </div>
  </div>
<?php } else { ?>
  <div class="container">
    <h1>Administrator Home</h1>
  </div>
<?php } ?>


<?php
  include 'includes/footer.php';
?>
