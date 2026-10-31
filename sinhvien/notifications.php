
<?php
session_start();

// Kiểm tra quyền sinh viên
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "sinhvien") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Lấy danh sách thông báo
$sql = "SELECT * FROM thong_bao ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$danhSach = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo</title>
</head>
<body>

    <h1>THÔNG BÁO SINH VIÊN</h1>

    <p>
        <a href="index.php">Trang chủ</a> |
        <a href="profile.php">Thông tin cá nhân</a> |
        <a href="change_password.php">Đổi mật khẩu</a> |
        <a href="../auth/logout.php">Đăng xuất</a>
    </p>

    <?php if (count($danhSach) > 0) { ?>

        <?php foreach ($danhSach as $tb) { ?>

            <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">

                <h3>
                    <?php echo htmlspecialchars($tb["tieude"]); ?>
                </h3>

                <p>
                    <?php echo nl2br(htmlspecialchars($tb["noidung"])); ?>
                </p>

                <small>
                    Ngày đăng:
                    <?php echo htmlspecialchars($tb["created_at"]); ?>
                </small>

            </div>

        <?php } ?>

    <?php } else { ?>

        <p>Hiện chưa có thông báo nào.</p>

    <?php } ?>

</body>
</html>
