<?php
session_start();
require_once 'config/db.php';
include 'header.php';
$message = "";
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashedPassword =
        password_hash( $password, PASSWORD_DEFAULT);
    $check = $conn->prepare( 
        "SELECT id
        FROM users
        WHERE username=?
        OR email=?"
    );
    $check->bind_param( "ss", $username, $email );
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) { $message = "Username hoặc Email đã tồn tại!";} 
    else {
        $stmt = $conn->prepare( "INSERT INTO users
            (
                username,
                password,
                fullname,
                email
            )
            VALUES (?,?,?,?)"
        );
        $stmt->bind_param(
            "ssss",
            $username,
            $hashedPassword,
            $fullname,
            $email
        );
        if ($stmt->execute()) {
            $message = "Đăng ký thành công!";
        } else {
            $message = "Có lỗi xảy ra!";
        }
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mt-4"> Đăng ký tài khoản </h3>
        <?php if(!empty($message)): ?>
            <div class="alert alert-info"> <?= $message ?> </div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label> Tên đăng nhập </label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label> Họ và tên </label>
                <input type="text" name="fullname" class="form-control" required>
            </div>
            <div class="mb-3"> 
                <label> Email </label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label> Mật khẩu </label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="register" class="btn btn-success"> Đăng ký </button>
            <a href="login.php" class="btn btn-link"> Đã có tài khoản? </a>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>