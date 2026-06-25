<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$message = "";
if (isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $stmt = $conn->prepare(
        "UPDATE users
         SET fullname = ?, email = ?
         WHERE id = ?"
    );
    $stmt->bind_param(
        "ssi",
        $fullname,
        $email,
        $user_id
    );
    if ($stmt->execute()) {
        $_SESSION['username'] = $fullname;
        $message = "
        <div class='alert alert-success'>
            🎉 Cập nhật thông tin thành công!
        </div>";
    } else {
        $message = "
        <div class='alert alert-danger'>
            Có lỗi xảy ra!
        </div>";
    }
}
$stmt = $conn->prepare(
    "SELECT username, fullname, email
     FROM users
     WHERE id = ?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4"> 👤 Thông tin cá nhân </h2>
            <?= $message ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label"> Username </label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled >
                        </div>
                        <div class="mb-3">
                            <label class="form-label"> Họ và tên </label>
                            <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user['fullname']) ?>" required >
                        </div>
                        <div class="mb-4">
                            <label class="form-label"> Email </label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>"  required >
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <a href="index.php"  class="btn btn-secondary w-100" > Quay lại </a>
                            </div>
                            <div class="col-6">
                                <button type="submit" name="update_profile" class="btn btn-primary w-100" > Lưu thay đổi </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>