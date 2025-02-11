<?php
include 'config.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1){
  header("Location: index.php");
  exit();
}
include 'header.php';
?>
<div class="container">
    <h2 class="mb-4 text-center">Admin Dashboard</h2>
    <p class="text-center">Selamat datang, admin!</p>

    <!-- Add New Product Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="mb-0">Tambahkan Produk Baru</h3>
        </div>
        <div class="card-body">
            <?php
      if(isset($_POST['add_product'])){
        $name        = $_POST['name'];
        $description = $_POST['description'];
        $price       = floatval($_POST['price']);
        $image       = $_POST['image'];
        
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $image);
        if($stmt->execute()){
          echo '<div class="alert alert-success">Produk berhasil ditambahkan!</div>';
        } else {
          echo '<div class="alert alert-danger">Gagal menambahkan produk.</div>';
        }
        $stmt->close();
      }
      ?>
            <form method="post" action="admin.php">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama produk"
                        required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="3"
                        placeholder="Masukkan deskripsi produk" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control"
                        placeholder="Masukkan harga produk" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">URL Gambar</label>
                    <input type="text" name="image" id="image" class="form-control" placeholder="Masukkan URL gambar">
                </div>
                <button type="submit" name="add_product" class="btn btn-primary w-100">Tambahkan Produk</button>
            </form>
        </div>
    </div>

    <!-- Existing Products Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="mb-0">Produk yang Sudah Ada</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th class="d-none d-sm-table-cell">Deskripsi</th>
                            <th>Harga</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            $sql = "SELECT * FROM products ORDER BY id DESC";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()):
            ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="d-none d-sm-table-cell"><?php echo htmlspecialchars($row['description']); ?></td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <?php if(!empty($row['image'])): ?>
                                <img src="<?php echo $row['image']; ?>"
                                    alt="<?php echo htmlspecialchars($row['name']); ?>" class="img-fluid"
                                    style="max-width: 80px;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>"
                                    class="btn btn-warning btn-sm mb-1">Edit</a>
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Manage Orders Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="mb-0">Transaksi Terbaru</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID Order</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
            $sql = "SELECT o.id, o.total, o.created_at, u.username 
                    FROM orders o 
                    JOIN users u ON o.user_id = u.id 
                    ORDER BY o.created_at DESC";
            $result = $conn->query($sql);
            while($order = $result->fetch_assoc()):
            ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                            <td>$<?php echo number_format($order['total'], 2); ?></td>
                            <td><?php echo $order['created_at']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php include 'footer.php'; ?>