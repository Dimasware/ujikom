<?php
include 'config.php';

if(isset($_SESSION['user'])){
  header("Location: index.php");
  exit();
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $email    = $_POST['email'];
  $password = $_POST['password'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows > 0){
    $user = $result->fetch_assoc();
    if(password_verify($password, $user['password'])){
      $_SESSION['user'] = $user;
      header("Location: index.php");
      exit();
    } else {
      $error = "Kata sandi tidak valid.";
    }
  } else {
    $error = "Pengguna tidak ditemukan.";
  }
  $stmt->close();
}
include 'header.php';
?>
<h2 class="mb-4 text-center">Login</h2>
<?php if($error): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post" action="login.php">
    <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" placeholder="Alamat Email" required>
        <label>Alamat Email</label>
    </div>
    <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <label>Password</label>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
</form>
<?php include 'footer.php'; ?>