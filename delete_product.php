<?php
include 'config.php';

// Only allow access if admin.
if(!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1){
  header("Location: index.php");
  exit();
}

if(!isset($_GET['id'])){
  header("Location: admin.php");
  exit();
}

$product_id = intval($_GET['id']);

// Delete the product.
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->close();

header("Location: admin.php");
exit();
?>