<?php
include 'config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1){
  header("Location: index.php");
  exit();
}

if(!isset($_GET['id'])){
  header("Location: admin.php");
  exit();
}

$product_id = intval($_GET['id']);
$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $image = $_POST['image'];

    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image, $product_id);
    if($stmt->execute()){
       $success = "Produk berhasil diperbarui!!";
    } else {
       $error = "Gagal memperbarui produk.";
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows == 0){
    echo "Produk tidak ditemukan.";
    exit();
}
$product = $result->fetch_assoc();
$stmt->close();

include 'header.php';
?>
<h2 class="mb-4">Edit Produk</h2>
<?php if($error): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if($success): ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<form method="post" action="edit_product.php?id=<?php echo $product_id; ?>">
    <div class="form-floating mb-3">
        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>"
            placeholder="Nama Produk" required>
        <label>Nama Produk</label>
    </div>
    <div class="form-floating mb-3">
        <textarea name="description" class="form-control" placeholder="Deskripsi" required
            style="height: 100px;"><?php echo htmlspecialchars($product['description']); ?></textarea>
        <label>Deskripsi</label>
    </div>
    <div class="form-floating mb-3">
        <input type="number" step="0.01" name="price" class="form-control"
            value="<?php echo htmlspecialchars($product['price']); ?>" placeholder="Harga" required>
        <label>Harga</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($product['image']); ?>"
            placeholder="URL Gambar">
        <label>URL Gambar</label>
    </div>
    <button type="submit" class="btn btn-primary w-100">Update Produk</button>
    <a href="admin.php" class="btn btn-secondary w-100 mt-2">Kembali ke Admin</a>
</form>
<?php include 'footer.php'; ?>