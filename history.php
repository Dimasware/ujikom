<?php
include 'config.php';
if(!isset($_SESSION['user'])){
  header("Location: login.php");
  exit();
}
include 'header.php';
?>
<h2 class="mb-4 text-center">Riwayat Pesanan Anda</h2>
<?php
$user_id = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0){
  while($order = $result->fetch_assoc()){
    ?>
<div class="card mb-4">
    <div class="card-header">
        Order #<?php echo $order['id']; ?>
        <span class="text-muted" style="font-size: 0.9rem;">(<?php echo $order['created_at']; ?>)</span>
    </div>
    <div class="card-body">
        <p class="fw-bold">Total: $<?php echo number_format($order['total'], 2); ?></p>
        <?php
        $stmt_items = $conn->prepare("SELECT oi.quantity, p.name, p.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmt_items->bind_param("i", $order['id']);
        $stmt_items->execute();
        $items_result = $stmt_items->get_result();
        ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = $items_result->fetch_assoc()){
                $subtotal = $item['price'] * $item['quantity'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php
        $stmt_items->close();
        ?>
    </div>
</div>
<?php
  }
} else {
  echo '<div class="alert alert-info">Anda belum memiliki pesanan.</div>';
}
$stmt->close();
include 'footer.php';
?>