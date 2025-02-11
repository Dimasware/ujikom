<?php
include 'config.php';

if(isset($_SESSION['user'])){
  header("Location: index.php");
  exit();
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $username         = $_POST['username'];
  $email            = $_POST['email'];
  $password         = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  
  if($password !== $confirm_password){
    $error = "Kata sandi tidak cocok.";
  } else {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
      $error = "Email sudah terdaftar.";
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, 0)");
      $stmt->bind_param("sss", $username, $email, $hashed_password);
      if($stmt->execute()){
        header("Location: login.php");
        exit();
      } else {
        $error = "Pendaftaran gagal.";
      }
    }
    $stmt->close();
  }
}
include 'header.php';
?>
<h2 class="mb-4 text-center">Register</h2>
<?php if($error): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post" action="register.php">
    <div class="form-floating mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <label>Username</label>
    </div>
    <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" placeholder="Alamat Email" required>
        <label>Alamat Email</label>
    </div>
    <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <label>Password</label>
    </div>
    <div class="form-floating mb-3">
        <input type="password" name="confirm_password" class="form-control" placeholder="Konfirmasi Password" required>
        <label>Konfirmasi Password</label>
    </div>
    <button type="submit" class="btn btn-primary w-100">Register</button>
</form>
<?php include 'footer.php'; ?>