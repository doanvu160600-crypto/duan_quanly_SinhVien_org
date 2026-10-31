
<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

$error = "";
$success = "";

// Lấy danh sách lớp
$sql = "SELECT malop, tenlop FROM lop ORDER BY tenlop";
$stmt = $conn->prepare($sql);
$stmt->execute();
$danhSachLop = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý khi nhấn nút Thêm
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mssv = trim($_POST["mssv"] ?? "");
    $hoten = trim($_POST["hoten"] ?? "");
    $ngaysinh = $_POST["ngaysinh"] ?? "";
    $gioitinh = $_POST["gioitinh"] ?? "";
    $malop = $_POST["malop"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $sdt = trim($_POST["sdt"] ?? "");
    $diachi = trim($_POST["diachi"] ?? "");
    $password = $_POST["password"] ?? "";
    $trangthai = $_POST["trangthai"] ?? "";

    // Kiểm tra dữ liệu
    if (
        $mssv == "" || $hoten == "" || $ngaysinh == "" ||
        $gioitinh == "" || $malop == "" || $email == "" ||
        $password == "" || $trangthai == ""
    ) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ!";
    } elseif (!in_array($gioitinh, ["Nam", "Nữ", "Khác"])) {
        $error = "Giới tính không hợp lệ!";
    } elseif (!in_array($trangthai, ["Đang học", "Tốt nghiệp", "Bảo lưu", "Đã nghỉ"])) {
        $error = "Trạng thái không hợp lệ!";
    } else {

        // Kiểm tra MSSV đã tồn tại
        $sql = "SELECT mssv FROM sinh_vien WHERE mssv = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$mssv]);

        if ($stmt->fetch()) {
            $error = "MSSV đã tồn tại!";
        } else {

            // Lấy mã khoa theo lớp
            $sql = "SELECT makhoa FROM lop WHERE malop = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$malop]);
            $lop = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$lop) {
                $error = "Lớp không tồn tại!";
            } else {

                // Mã hóa mật khẩu
                $matKhauMaHoa = password_hash($password, PASSWORD_DEFAULT);

                // Thêm sinh viên
                $sql = "INSERT INTO sinh_vien
                        (mssv, hoten, ngaysinh, gioitinh, malop,
                         makhoa, email, sdt, diachi, password, trangthai)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);

                $stmt->execute([
                    $mssv,
                    $hoten,
                    $ngaysinh,
                    $gioitinh,
                    $malop,
                    $lop["makhoa"],
                    $email,
                    $sdt,
                    $diachi,
                    $matKhauMaHoa,
                    $trangthai
                ]);

                $success = "Thêm sinh viên thành công!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sinh viên</title>
</head>
<body>

    <h1>THÊM SINH VIÊN</h1>

    <a href="student_list.php">Quay lại danh sách</a>

    <?php if ($error != "") { ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php } ?>

    <?php if ($success != "") { ?>
        <p style="color:green;">
            <?php echo htmlspecialchars($success); ?>
        </p>
    <?php } ?>

    <?php if (count($danhSachLop) == 0) { ?>

        <p>Chưa có lớp học. Vui lòng thêm lớp trước.</p>

    <?php } else { ?>

        <form method="POST">

            <p>
                MSSV:
                <input type="text" name="mssv" required>
            </p>

            <p>
                Họ tên:
                <input type="text" name="hoten" required>
            </p>

            <p>
                Ngày sinh:
                <input type="date" name="ngaysinh" required>
            </p>

            <p>
                Giới tính:
                <select name="gioitinh" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </p>

            <p>
                Lớp:
                <select name="malop" required>
                    <?php foreach ($danhSachLop as $lop) { ?>
                        <option value="<?php echo htmlspecialchars($lop["malop"]); ?>">
                            <?php echo htmlspecialchars($lop["tenlop"]); ?>
                        </option>
                    <?php } ?>
                </select>
            </p>

            <p>
                Email:
                <input type="email" name="email" required>
            </p>

            <p>
                Số điện thoại:
                <input type="text" name="sdt">
            </p>

            <p>
                Địa chỉ:
                <input type="text" name="diachi">
            </p>

            <p>
                Mật khẩu:
                <input type="password" name="password" required>
            </p>

            <p>
                Trạng thái:
                <select name="trangthai" required>
                    <option value="Đang học">Đang học</option>
                    <option value="Tốt nghiệp">Tốt nghiệp</option>
                    <option value="Bảo lưu">Bảo lưu</option>
                    <option value="Đã nghỉ">Đã nghỉ</option>
                </select>
            </p>

            <button type="submit">Thêm sinh viên</button>

        </form>

    <?php } ?>

</body>
</html>
