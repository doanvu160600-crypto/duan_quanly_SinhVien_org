
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

$thongBao = "";

// Xử lý khi gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tieude = trim($_POST["tieude"]);
    $noidung = trim($_POST["noidung"]);

    if ($tieude == "" || $noidung == "") {

        $thongBao = "Vui lòng nhập đầy đủ tiêu đề và nội dung!";

    } else {

        // Thêm thông báo vào database
        $sql = "INSERT INTO thong_bao (tieude, noidung, created_at)
                VALUES (?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$tieude, $noidung]);

        header("Location: notification.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm thông báo</title>
</head>
<body>

    <h1>THÊM THÔNG BÁO</h1>

    <p>
        <a href="notification.php">Quay lại danh sách thông báo</a>
    </p>

    <p style="color:red;">
        <?php echo htmlspecialchars($thongBao); ?>
    </p>

    <form method="POST">

        <label>Tiêu đề:</label><br>
        <input type="text" name="tieude" required>
        <br><br>

        <label>Nội dung:</label><br>
        <textarea name="noidung" rows="6" cols="50" required></textarea>
        <br><br>

        <button type="submit">Đăng thông báo</button>

    </form>

</body>
</html>
