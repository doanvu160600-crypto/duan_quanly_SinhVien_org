
<?php
session_start();

// Kiểm tra quyền sinh viên
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "sinhvien") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

$mssv = $_SESSION["user_id"];
$thongBao = "";

// Xử lý khi gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $matKhauCu = $_POST["matkhaucu"];
    $matKhauMoi = $_POST["matkhaumoi"];
    $xacNhan = $_POST["xacnhan"];

    // Lấy mật khẩu hiện tại trong database
    $sql = "SELECT password FROM sinh_vien WHERE mssv = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$mssv]);

    $sv = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sv || !password_verify($matKhauCu, $sv["password"])) {
        $thongBao = "Mật khẩu hiện tại không đúng!";
    } elseif (strlen($matKhauMoi) < 6) {
        $thongBao = "Mật khẩu mới phải có ít nhất 6 ký tự!";
    } elseif ($matKhauMoi != $xacNhan) {
        $thongBao = "Mật khẩu xác nhận không khớp!";
    } else {

        // Mã hóa mật khẩu mới
        $matKhauHash = password_hash($matKhauMoi, PASSWORD_DEFAULT);

        // Cập nhật mật khẩu
        $sql = "UPDATE sinh_vien SET password = ? WHERE mssv = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$matKhauHash, $mssv]);

        $thongBao = "Đổi mật khẩu thành công!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>
</head>
<body>

    <h1>ĐỔI MẬT KHẨU</h1>

    <p>
        <a href="index.php">Trang chủ</a> |
        <a href="profile.php">Thông tin cá nhân</a> |
        <a href="../auth/logout.php">Đăng xuất</a>
    </p>

    <?php if ($thongBao != "") { ?>
        <p><?php echo htmlspecialchars($thongBao); ?></p>
    <?php } ?>

    <form method="POST">

        <p>
            <label>Mật khẩu hiện tại:</label><br>
            <input type="password" name="matkhaucu" required>
        </p>

        <p>
            <label>Mật khẩu mới:</label><br>
            <input type="password" name="matkhaumoi" required>
        </p>

        <p>
            <label>Xác nhận mật khẩu mới:</label><br>
            <input type="password" name="xacnhan" required>
        </p>

        <button type="submit">Đổi mật khẩu</button>

    </form>

</body>
</html>
