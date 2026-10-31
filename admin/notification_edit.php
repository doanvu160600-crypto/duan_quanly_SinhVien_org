
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Lấy ID thông báo từ URL
$id = $_GET["id"] ?? "";

// Tìm thông báo cần sửa
$sql = "SELECT * FROM thong_bao WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

$tb = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra thông báo có tồn tại không
if (!$tb) {
    die("Không tìm thấy thông báo!");
}

$thongBao = "";

// Xử lý khi nhấn cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tieude = trim($_POST["tieude"]);
    $noidung = trim($_POST["noidung"]);

    if ($tieude == "" || $noidung == "") {

        $thongBao = "Vui lòng nhập đầy đủ thông tin!";

    } else {

        $sql = "UPDATE thong_bao
                SET tieude = ?, noidung = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$tieude, $noidung, $id]);

        header("Location: notification.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thông báo</title>
</head>
<body>

    <h1>SỬA THÔNG BÁO</h1>

    <p>
        <a href="notification.php">Quay lại danh sách</a>
    </p>

    <p style="color:red;">
        <?php echo htmlspecialchars($thongBao); ?>
    </p>

    <form method="POST">

        <label>Tiêu đề:</label><br>
        <input type="text"
               name="tieude"
               value="<?php echo htmlspecialchars($tb["tieude"]); ?>"
               required>
        <br><br>

        <label>Nội dung:</label><br>
        <textarea name="noidung" rows="6" cols="50" required><?php
            echo htmlspecialchars($tb["noidung"]);
        ?></textarea>
        <br><br>

        <button type="submit">Cập nhật</button>

    </form>

</body>
</html>
