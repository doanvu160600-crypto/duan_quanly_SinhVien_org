
<?php
session_start();
// Kiểm tra quyền sinh viên
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "sinhvien") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Lấy MSSV từ session
$mssv = $_SESSION["user_id"];

// Truy vấn thông tin sinh viên
$sql = "SELECT sv.*, l.tenlop, k.tenkhoa
        FROM sinh_vien sv
        LEFT JOIN lop l ON sv.malop = l.malop
        LEFT JOIN khoa k ON sv.makhoa = k.makhoa
        WHERE sv.mssv = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$mssv]);

$sv = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra sinh viên có tồn tại không
if (!$sv) {
    die("Không tìm thấy thông tin sinh viên!");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
</head>
<body>

    <h1>THÔNG TIN CÁ NHÂN</h1>

    <p>
        <a href="index.php">Trang chủ</a> |
        <a href="change_password.php">Đổi mật khẩu</a> |
        <a href="notifications.php">Thông báo</a> |
        <a href="../auth/logout.php">Đăng xuất</a>
    </p>

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>Mã số sinh viên</th>
            <td><?php echo htmlspecialchars($sv["mssv"]); ?></td>
        </tr>

        <tr>
            <th>Họ tên</th>
            <td><?php echo htmlspecialchars($sv["hoten"]); ?></td>
        </tr>

        <tr>
            <th>Ngày sinh</th>
            <td><?php echo htmlspecialchars($sv["ngaysinh"]); ?></td>
        </tr>

        <tr>
            <th>Giới tính</th>
            <td><?php echo htmlspecialchars($sv["gioitinh"]); ?></td>
        </tr>

        <tr>
            <th>Lớp</th>
            <td><?php echo htmlspecialchars($sv["tenlop"] ?? "Chưa có"); ?></td>
        </tr>

        <tr>
            <th>Khoa</th>
            <td><?php echo htmlspecialchars($sv["tenkhoa"] ?? "Chưa có"); ?></td>
        </tr>

        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($sv["email"]); ?></td>
        </tr>

        <tr>
            <th>Số điện thoại</th>
            <td><?php echo htmlspecialchars($sv["sdt"]); ?></td>
        </tr>

        <tr>
            <th>Địa chỉ</th>
            <td><?php echo htmlspecialchars($sv["diachi"]); ?></td>
        </tr>

        <tr>
            <th>Trạng thái</th>
            <td><?php echo htmlspecialchars($sv["trangthai"]); ?></td>
        </tr>

    </table>

</body>
</html>
