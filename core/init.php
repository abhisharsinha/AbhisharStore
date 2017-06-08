<?php
  $db = mysqli_connect('127.0.0.1','root','','ecom');
  if (mysqli_connect_errno()) {
    echo "Database Connection failed with the following errors: ".mysqli_connect_error();
    die();
  }
  require_once 'D:/Applications/DevTools/xampp/htdocs/ecom/helpers/helpers.php';
?>
