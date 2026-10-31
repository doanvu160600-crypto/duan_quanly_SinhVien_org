
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Chỉ cho phép xóa bằng phương thức POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: student_list.php");
    exit();
}

// Lấy MSSV cần xóa
$mssv = trim($_POST["mssv"] ?? "");

if ($mssv == "") {
    header("Location: student_list.php");
    exit();
}

// Xóa sinh viên khỏi database
$sql = "DELETE FROM sinh_vien WHERE mssv = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$mssv]);

// Kiểm tra kết quả
if ($stmt->rowCount() > 0) {
    header("Location: student_list.php?msg=deleted");
} else {
    header("Location: student_list.php?msg=notfound");
}

exit();
?>
