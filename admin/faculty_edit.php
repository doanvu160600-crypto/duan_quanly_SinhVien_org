
<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Lấy mã khoa từ URL
$makhoa = $_GET["makhoa"] ?? "";

// Tìm khoa cần sửa
$sql = "SELECT * FROM khoa WHERE makhoa = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$makhoa]);

$khoa = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu không tìm thấy khoa
if (!$khoa) {
    die("Không tìm thấy khoa!");
}

$thongBao = "";

// Xử lý khi nhấn nút cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tenkhoa = trim($_POST["tenkhoa"]);
    $mota = trim($_POST["mota"]);

    if ($tenkhoa == "") {
        $thongBao = "Vui lòng nhập tên khoa!";
    } else {

        $sql = "UPDATE khoa
                SET tenkhoa = ?, mota = ?
                WHERE makhoa = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$tenkhoa, $mota, $makhoa]);

        header("Location: faculty_list.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa khoa</title>
</head>
<body>

    <h1>SỬA THÔNG TIN KHOA</h1>

    <p>
        <a href="faculty_list.php">Quay lại danh sách khoa</a>
    </p>

    <p style="color:red;">
        <?php echo htmlspecialchars($thongBao); ?>
    </p>

    <form method="POST">

        <label>Mã khoa:</label><br>
        <input type="text"
               value="<?php echo htmlspecialchars($khoa["makhoa"]); ?>"
               disabled>
        <br><br>

        <label>Tên khoa:</label><br>
        <input type="text"
               name="tenkhoa"
               value="<?php echo htmlspecialchars($khoa["tenkhoa"]); ?>"
               required>
        <br><br>

        <label>Mô tả:</label><br>
        <textarea name="mota"><?php
            echo htmlspecialchars($khoa["mota"] ?? "");
        ?></textarea>
        <br><br>

        <button type="submit">Cập nhật</button>

    </form>

</body>
</html>
