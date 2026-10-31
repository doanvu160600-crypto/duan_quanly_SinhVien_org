
<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

$error = "";

// Lấy MSSV từ URL
$mssv = $_GET["mssv"] ?? "";

// Tìm sinh viên cần sửa
$sql = "SELECT * FROM sinh_vien WHERE mssv = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$mssv]);
$sv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sv) {
    die("Không tìm thấy sinh viên!");
}

// Lấy danh sách lớp
$sql = "SELECT malop, tenlop FROM lop ORDER BY tenlop";
$stmt = $conn->prepare($sql);
$stmt->execute();
$danhSachLop = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý khi nhấn nút Cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hoten = trim($_POST["hoten"] ?? "");
    $ngaysinh = $_POST["ngaysinh"] ?? "";
    $gioitinh = $_POST["gioitinh"] ?? "";
    $malop = $_POST["malop"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $sdt = trim($_POST["sdt"] ?? "");
    $diachi = trim($_POST["diachi"] ?? "");
    $trangthai = $_POST["trangthai"] ?? "";

    if (
        $hoten == "" || $ngaysinh == "" ||
        $gioitinh == "" || $malop == "" ||
        $email == "" || $trangthai == ""
    ) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ!";
    } else {

        // Lấy mã khoa theo lớp
        $sql = "SELECT makhoa FROM lop WHERE malop = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$malop]);
        $lop = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$lop) {
            $error = "Lớp không tồn tại!";
        } else {

            // Cập nhật thông tin sinh viên
            $sql = "UPDATE sinh_vien SET
                    hoten = ?,
                    ngaysinh = ?,
                    gioitinh = ?,
                    malop = ?,
                    makhoa = ?,
                    email = ?,
                    sdt = ?,
                    diachi = ?,
                    trangthai = ?
                    WHERE mssv = ?";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                $hoten,
                $ngaysinh,
                $gioitinh,
                $malop,
                $lop["makhoa"],
                $email,
                $sdt,
                $diachi,
                $trangthai,
                $mssv
            ]);

            header("Location: student_list.php");
            exit();
        }
    }

    // Giữ lại dữ liệu vừa nhập nếu có lỗi
    $sv = array_merge($sv, $_POST);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sinh viên</title>
</head>
<body>

    <h1>SỬA THÔNG TIN SINH VIÊN</h1>

    <a href="student_list.php">Quay lại danh sách</a>

    <?php if ($error != "") { ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php } ?>

    <form method="POST">

        <p>
            MSSV:
            <input type="text"
                   value="<?php echo htmlspecialchars($sv["mssv"]); ?>"
                   disabled>
        </p>

        <p>
            Họ tên:
            <input type="text" name="hoten"
                   value="<?php echo htmlspecialchars($sv["hoten"]); ?>"
                   required>
        </p>

        <p>
            Ngày sinh:
            <input type="date" name="ngaysinh"
                   value="<?php echo htmlspecialchars($sv["ngaysinh"]); ?>"
                   required>
        </p>

        <p>
            Giới tính:
            <select name="gioitinh" required>
                <?php foreach (["Nam", "Nữ", "Khác"] as $gt) { ?>
                    <option value="<?php echo $gt; ?>"
                        <?php echo ($sv["gioitinh"] == $gt) ? "selected" : ""; ?>>
                        <?php echo $gt; ?>
                    </option>
                <?php } ?>
            </select>
        </p>

        <p>
            Lớp:
            <select name="malop" required>
                <?php foreach ($danhSachLop as $lop) { ?>
                    <option value="<?php echo htmlspecialchars($lop["malop"]); ?>"
                        <?php echo ($sv["malop"] == $lop["malop"]) ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($lop["tenlop"]); ?>
                    </option>
                <?php } ?>
            </select>
        </p>

        <p>
            Email:
            <input type="email" name="email"
                   value="<?php echo htmlspecialchars($sv["email"]); ?>"
                   required>
        </p>

        <p>
            Số điện thoại:
            <input type="text" name="sdt"
                   value="<?php echo htmlspecialchars($sv["sdt"] ?? ""); ?>">
        </p>

        <p>
            Địa chỉ:
            <input type="text" name="diachi"
                   value="<?php echo htmlspecialchars($sv["diachi"] ?? ""); ?>">
        </p>

        <p>
            Trạng thái:
            <select name="trangthai" required>
                <?php foreach (["Đang học", "Tốt nghiệp", "Bảo lưu", "Đã nghỉ"] as $tt) { ?>
                    <option value="<?php echo $tt; ?>"
                        <?php echo ($sv["trangthai"] == $tt) ? "selected" : ""; ?>>
                        <?php echo $tt; ?>
                    </option>
                <?php } ?>
            </select>
        </p>

        <button type="submit">Cập nhật</button>

    </form>

</body>
</html>
