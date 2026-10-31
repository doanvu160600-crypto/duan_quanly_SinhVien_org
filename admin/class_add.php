
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Lấy danh sách khoa
$sql = "SELECT * FROM khoa ORDER BY makhoa";
$stmt = $conn->prepare($sql);
$stmt->execute();

$danhSachKhoa = $stmt->fetchAll(PDO::FETCH_ASSOC);

$thongBao = "";

// Xử lý khi gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $malop = trim($_POST["malop"]);
    $tenlop = trim($_POST["tenlop"]);
    $makhoa = trim($_POST["makhoa"]);

    if ($malop == "" || $tenlop == "" || $makhoa == "") {
        $thongBao = "Vui lòng nhập đầy đủ thông tin!";
    } else {

        // Kiểm tra mã lớp đã tồn tại chưa
        $sql = "SELECT COUNT(*) FROM lop WHERE malop = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$malop]);

        if ($stmt->fetchColumn() > 0) {
            $thongBao = "Mã lớp đã tồn tại!";
        } else {

            // Thêm lớp vào database
            $sql = "INSERT INTO lop (malop, tenlop, makhoa)
                    VALUES (?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$malop, $tenlop, $makhoa]);

            header("Location: class_list.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm lớp học</title>
</head>
<body>

    <h1>THÊM LỚP HỌC</h1>

    <p>
        <a href="class_list.php">Quay lại danh sách lớp</a>
    </p>

    <p style="color:red;">
        <?php echo htmlspecialchars($thongBao); ?>
    </p>

    <?php if (count($danhSachKhoa) > 0) { ?>

        <form method="POST">

            <label>Mã lớp:</label><br>
            <input type="text" name="malop" required>
            <br><br>

            <label>Tên lớp:</label><br>
            <input type="text" name="tenlop" required>
            <br><br>

            <label>Khoa:</label><br>
            <select name="makhoa" required>
                <option value="">-- Chọn khoa --</option>

                <?php foreach ($danhSachKhoa as $khoa) { ?>
                    <option value="<?php echo htmlspecialchars($khoa["makhoa"]); ?>">
                        <?php echo htmlspecialchars($khoa["tenkhoa"]); ?>
                    </option>
                <?php } ?>

            </select>
            <br><br>

            <button type="submit">Thêm lớp</button>

        </form>

    <?php } else { ?>

        <p>Chưa có khoa. Vui lòng thêm khoa trước!</p>

    <?php } ?>

</body>
</html>
