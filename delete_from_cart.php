<?php
  session_start();
  $id = $_GET['id'];

  unset($_SESSION['cart'][$id]);
  header('Location: https://tbcmerchantservices.com/cart?action=removed&product=' . $id);
 ?>