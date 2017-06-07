<?php
  function display_errors($errors){
    $display = '<ul class="bg-danger">';
    foreach ($errors as $key) {
      $display .="<li class='text-danger'>".$key;
    }
    $display .='</ul>';
    return $display;
  }

  function sanitize($dirty)
  {
    return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
  }
?>
