<?php
include 'config.php';
if(!isset($_SESSION['user'])){
  header("Location: login.php");
  exit();
}
include 'header.php';
if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0){
  echo '<div class="alert alert-info">Your cart is empty.</div>';
  include 'footer.php';
  exit();
}
$total = 0;
foreach($_SESSION['cart'] as $item){
  $total += $item['price'] * $item['quantity'];
}
if(isset($_POST['confirm_payment'])){
  $user_id = $_SESSION['user']['id'];
  $stmt = $conn->prepare("INSERT INTO orders (user_id, total, created_at) VALUES (?, ?, NOW())");
  $stmt->bind_param("id", $user_id, $total);
  if($stmt->execute()){
    $order_id = $stmt->insert_id;
    $stmt->close();
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    foreach($_SESSION['cart'] as $item){
      $stmt_item->bind_param("iii", $order_id, $item['id'], $item['quantity']);
      $stmt_item->execute();
    }
    $stmt_item->close();
    unset($_SESSION['cart']);
    echo '<div class="alert alert-success mt-4">Pembayaran berhasil! Pesanan Anda telah dilakukan.</div>';
    echo '<a href="index.php" class="btn btn-primary mt-3 w-100">Continue Shopping</a>';
    include 'footer.php';
    exit();
  } else {
    echo '<div class="alert alert-danger mt-4">Pembayaran gagal. Silakan coba lagi.</div>';
  }
}
?>
<h2 class="mb-4 text-center">Checkout</h2>
<div class="card mb-4">
    <div class="card-header">
        Ringkasan Pesanan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kuantitas</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($_SESSION['cart'] as $item): 
            $subtotal = $item['price'] * $item['quantity']; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-end fw-bold">Total</td>
                        <td class="fw-bold">$<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<form method="post" action="payment.php">
    <button type="submit" name="confirm_payment" class="btn btn-success w-100">Konfirmasi Pembayaran</button>
</form>
<?php include 'footer.php'; ?>