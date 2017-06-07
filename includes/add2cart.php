<?php
  require '../core/init.php';
  session_start();
  echo "WHATUP\n";
  if (isset($_SESSION['id']))
  {
    echo "SESSION ID SET\n";
    $id = $_SESSION['id'];
    if (isset($_POST['quantity']))
    {
      $query = "SELECT * FROM cart WHERE id = '$id'";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);

      $cartq = $row['quantity'];
      $quantity = sanitize($_POST['quantity']);
      $cartq .= $quantity.',';

      $cartp = $row['products'];
      $product = sanitize($_POST['pid']);
      $cartp .= $product.',';

      $query = "UPDATE cart SET products='$cartp', quantity='$cartq' WHERE id='$id'";
      mysqli_query($db, $query);

      header("Location: ..\index.php");
    }
  }
  else {
    echo "SIGININ REQUIRED\n";
  }
 ?>
