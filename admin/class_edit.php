
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Lấy mã lớp từ URL
$malop = $_GET["malop"] ?? "";

// Lấy thông tin lớp cần sửa
$sql = "SELECT * FROM lop WHERE malop = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$malop]);

$lop = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lop) {
    die("Không tìm thấy lớp học!");
}

// Lấy danh sách khoa
$sql = "SELECT * FROM khoa ORDER BY makhoa";
$stmt = $conn->prepare($sql);
$stmt->execute();

$danhSachKhoa = $stmt->fetchAll(PDO::FETCH_ASSOC);

$thongBao = "";

// Xử lý khi nhấn cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tenlop = trim($_POST["tenlop"]);
    $makhoa = trim($_POST["makhoa"]);

    if ($tenlop == "" || $makhoa == "") {
        $thongBao = "Vui lòng nhập đầy đủ thông tin!";
    } else {

        $sql = "UPDATE lop
                SET tenlop = ?, makhoa = ?
                WHERE malop = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$tenlop, $makhoa, $malop]);

        header("Location: class_list.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa lớp học</title>
</head>
<body>

    <h1>SỬA THÔNG TIN LỚP</h1>

    <p>
        <a href="class_list.php">Quay lại danh sách lớp</a>
    </p>

    <p style="color:red;">
        <?php echo htmlspecialchars($thongBao); ?>
    </p>

    <form method="POST">

        <label>Mã lớp:</label><br>
        <input type="text"
               value="<?php echo htmlspecialchars($lop["malop"]); ?>"
               disabled>
        <br><br>

        <label>Tên lớp:</label><br>
        <input type="text"
               name="tenlop"
               value="<?php echo htmlspecialchars($lop["tenlop"]); ?>"
               required>
        <br><br>

        <label>Khoa:</label><br>
        <select name="makhoa" required>

            <?php foreach ($danhSachKhoa as $khoa) { ?>

                <option
                    value="<?php echo htmlspecialchars($khoa["makhoa"]); ?>"
                    <?php if ($khoa["makhoa"] == $lop["makhoa"]) echo "selected"; ?>>

                    <?php echo htmlspecialchars($khoa["tenkhoa"]); ?>

                </option>

            <?php } ?>

        </select>
        <br><br>

        <button type="submit">Cập nhật</button>

    </form>

</body>
</html>
