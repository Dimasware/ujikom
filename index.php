<?php
include 'config.php';
include 'header.php';

// Handle "Add to Cart" action
if(isset($_GET['add_to_cart'])){
  $product_id = intval($_GET['add_to_cart']);
  $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows > 0){
    $product = $result->fetch_assoc();
    if(!isset($_SESSION['cart'])){
      $_SESSION['cart'] = [];
    }
    if(isset($_SESSION['cart'][$product_id])){
      $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
      $_SESSION['cart'][$product_id] = [
        'id'       => $product['id'],
        'name'     => $product['name'],
        'price'    => $product['price'],
        'quantity' => 1
      ];
    }
    echo '<div class="alert alert-success">Ditambahkan ke keranjang!!</div>';
  }
  $stmt->close();
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<h1 class="mb-4 text-center">Produk Kami</h1>
<div class="row">
    <?php while($row = $result->fetch_assoc()): ?>
    <div class="col-12 col-sm-6 col-md-4 mb-4">
        <div class="card h-100">
            <?php if(!empty($row['image'])): ?>
            <img src="<?php echo $row['image']; ?>" class="card-img-top"
                alt="<?php echo htmlspecialchars($row['name']); ?>">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                <p class="card-text fw-bold">$<?php echo number_format($row['price'], 2); ?></p>
                <a href="index.php?add_to_cart=<?php echo $row['id']; ?>" class="btn btn-primary mt-auto">Add to
                    Cart</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php include 'footer.php'; ?>