
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
    header("Location: notification.php");
    exit();
}

$id = $_POST["id"] ?? "";

if ($id == "") {
    header("Location: notification.php");
    exit();
}

try {

    // Xóa thông báo
    $sql = "DELETE FROM thong_bao WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    header("Location: notification.php?msg=deleted");
    exit();

} catch (PDOException $e) {

    header("Location: notification.php?msg=error");
    exit();
}
?>
