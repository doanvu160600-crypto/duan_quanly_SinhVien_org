
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Chỉ cho phép xóa bằng POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: class_list.php");
    exit();
}

$malop = $_POST["malop"] ?? "";

if ($malop == "") {
    header("Location: class_list.php");
    exit();
}

try {

    // Xóa lớp học
    $sql = "DELETE FROM lop WHERE malop = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$malop]);

    header("Location: class_list.php?msg=deleted");
    exit();

} catch (PDOException $e) {

    // Không thể xóa nếu lớp đang có sinh viên liên kết
    header("Location: class_list.php?msg=error");
    exit();
}
?>
