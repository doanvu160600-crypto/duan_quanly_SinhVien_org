
<?php
session_start();

// Kiểm tra quyền sinh viên
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "sinhvien") {
    header("Location: ../auth/login.php");
    exit();
}

$hoten = $_SESSION["hoten"];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ sinh viên</title>
</head>
<body>

    <h1>HỆ THỐNG QUẢN LÝ SINH VIÊN</h1>

    <h2>
        Xin chào, <?php echo htmlspecialchars($hoten); ?>!
    </h2>

    <p>Chào mừng bạn đến với khu vực sinh viên.</p>

    <hr>

    <h3>CHỨC NĂNG SINH VIÊN</h3>

    <ul>
        <li>
            <a href="profile.php">Thông tin cá nhân</a>
        </li>

        <li>
            <a href="change_password.php">Đổi mật khẩu</a>
        </li>

        <li>
            <a href="notifications.php">Xem thông báo</a>
        </li>

        <li>
            <a href="../auth/logout.php">Đăng xuất</a>
        </li>
    </ul>

</body>
</html>
