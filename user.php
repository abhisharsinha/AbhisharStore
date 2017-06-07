<?php
  require_once 'core/init.php';
  session_start();

  include_once 'helpers/helpers.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
?>
<!-- Logout -->
<?php
  if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
  }
?>
<!-- Login -->
<?php
  if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $username = sanitize($username);
    $password = sanitize($password);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db, $query);
    $row = mysqli_num_rows($result);
    if ($row == 0)
    {
      $error = "Username does not exist!";
      header("Location: user.php?error=$error&login");
    }
    else
    {
      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row['password'])) {
        $_SESSION['id'] = $row['id'];
        header("Location: index.php");
      }
      else {
        $error =  "Incorrect passoword!";
        header("Location: user.php?error=$error&login");
      }
    }
  }
?>
 <!-- Sign Up -->
<?php
  if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = sanitize($name);
    $username = sanitize($username);
    $password = sanitize($password);

    //password encryption
    $enpass = password_hash($password, PASSWORD_DEFAULT);
    $password = $enpass;

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db, $query);
    $row = mysqli_num_rows($result);
    if ($row == 0) {
      $query = "INSERT INTO users (name, username, password) VALUES ('$name', '$username', '$password')";
      mysqli_query($db, $query);


      $query = "SELECT * FROM users WHERE username = '$username'";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);
      $id = $row['id'];
      $_SESSION['id'] = $id;


      $query = "INSERT INTO cart (id) VALUES ('$id')";
      mysqli_query($db, $query);

      header("Location: index.php");
      die();
    }
    else {
      $error = "Username already exists!";
      header("Location: user.php?error=$error&signup");
    }
  }
?>

<!-- Login Form -->
<?php if (isset($_GET['login'])) { ?>
  <div class="container">
    <div class="row">
    <div class="col-md-6">
      <h2>Login</h2>
      <?php if(isset($_GET['error'])) { ?>
        <div class="alert alert-danger">
          <?= $_GET["error"]; ?>
        </div>
      <?php } ?>
      <form class="form" action="user.php" method="post">
        <div class="form-group">
          <label for="username">Username: </label>
          <input class="form-control" type="text" name="username" id="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password: </label>
          <input class="form-control" type="password" name="password" id="password" required>
        </div>
          <button type="submit" class="btn btn-primary" name="login">Login</button>
      </form>
    </div>
  </div>
  </div>
</div>
<?php } ?>
<!-- Signup Form -->
<?php if (isset($_GET['signup'])) { ?>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h2>Sign Up</h2>
        <?php if(isset($_GET['error'])) { ?>
          <div class="alert alert-danger">
            <?= $_GET["error"]; ?>
          </div>
        <?php } ?>
        <form class="form" action="user.php" method="post">
          <div class="form-group">
            <label for="name">Full Name: </label>
            <input class="form-control" type="text" name="name" id="name" required>
          </div>
          <div class="form-group">
            <label for="username">Username: </label>
            <input class="form-control" type="text" name="username" id="username" required>
          </div>
          <div class="form-group">
            <label for="password">Password: </label>
            <input class="form-control" type="password" name="password" id="password" required>
          </div>
            <button type="submit" class="btn btn-primary" name="signup">Sign Up</button>
        </form>
      </div>
    </div>
  </div>
<?php } ?>

<?php
  include 'includes/footer.php';
?>
