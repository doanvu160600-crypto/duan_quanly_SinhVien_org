
<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

$thongBao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $makhoa = trim($_POST["makhoa"]);
    $tenkhoa = trim($_POST["tenkhoa"]);
    $mota = trim($_POST["mota"]);

    if ($makhoa == "" || $tenkhoa == "") {
        $thongBao = "Vui lòng nhập mã khoa và tên khoa!";
    } else {

        // Kiểm tra mã khoa đã tồn tại chưa
        $sql = "SELECT COUNT(*) FROM khoa WHERE makhoa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$makhoa]);

        if ($stmt->fetchColumn() > 0) {
            $thongBao = "Mã khoa đã tồn tại!";
        } else {

            // Thêm khoa vào database
            $sql = "INSERT INTO khoa (makhoa, tenkhoa, mota)
                    VALUES (?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$makhoa, $tenkhoa, $mota]);

            header("Location: faculty_list.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm khoa</title>
</head>
<body>

    <h1>THÊM KHOA MỚI</h1>

    <p>
        <a href="faculty_list.php">Quay lại danh sách khoa</a>
    </p>

    <p style="color:red;">
        <?php echo htmlspecialchars($thongBao); ?>
    </p>

    <form method="POST">

        <label>Mã khoa:</label><br>
        <input type="text" name="makhoa" required>
        <br><br>

        <label>Tên khoa:</label><br>
        <input type="text" name="tenkhoa" required>
        <br><br>

        <label>Mô tả:</label><br>
        <textarea name="mota"></textarea>
        <br><br>

        <button type="submit">Thêm khoa</button>

    </form>

</body>
</html>
