<?php
include 'config.php';
include 'header.php';
?>
<h2 class="mb-4 text-center">Keranjang Anda</h2>
<?php
if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0){
  echo '<div class="alert alert-info">Keranjang Anda kosong.</div>';
} else {
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Kuantitas</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
        $total = 0;
        foreach($_SESSION['cart'] as $item):
          $subtotal = $item['price'] * $item['quantity'];
          $total += $subtotal;
        ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <a href="remove_from_cart.php?product_id=<?php echo $item['id']; ?>"
                        class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="text-end fw-bold">Total</td>
                <td colspan="2" class="fw-bold">$<?php echo number_format($total, 2); ?></td>
            </tr>
        </tbody>
    </table>
</div>
<a href="payment.php" class="btn btn-success w-100">Checkout</a>
<?php } ?>
<?php include 'footer.php'; ?>