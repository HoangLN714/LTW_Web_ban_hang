<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'config/db.php';
include 'header.php';
$message = "";
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE username=?"
    );
    $stmt->bind_param( "s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ( password_verify( $password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] == 'admin') { header( "Location: admin/index.php" );
            } else { header( "Location: index.php" ); }
            exit();
        } else {
            $message = "Sai mật khẩu!";
        }
    } else {
        $message = "Tài khoản không tồn tại!";
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mt-4"> Đăng nhập </h3>
        <?php if(!empty($message)): ?>
            <div class="alert alert-danger"> <?= $message ?> </div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label> Tên đăng nhập </label>
                <input 
                type="text" 
                name="username" 
                class="form-control" 
                required>
            </div>
            <div class="mb-3">
                <label> Mật khẩu </label>
                <input type="password"  name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary"> Đăng nhập </button>
            <a href="register.php" class="btn btn-link"> Chưa có tài khoản? </a>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>