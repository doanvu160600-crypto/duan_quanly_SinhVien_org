
<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Chỉ cho phép xóa bằng phương thức POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: faculty_list.php");
    exit();
}

$makhoa = $_POST["makhoa"] ?? "";

if ($makhoa == "") {
    header("Location: faculty_list.php");
    exit();
}

try {
    $sql = "DELETE FROM khoa WHERE makhoa = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$makhoa]);

    header("Location: faculty_list.php?msg=deleted");
    exit();

} catch (PDOException $e) {
    header("Location: faculty_list.php?msg=error");
    exit();
}
?>
