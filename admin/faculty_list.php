
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
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý khoa</title>
</head>
<body>

    <h1>QUẢN LÝ KHOA</h1>
    <?php
        if (isset($_GET["msg"])) {
            if ($_GET["msg"] == "deleted") {
                echo "<p style='color:green;'>Xóa khoa thành công!</p>";
            } elseif ($_GET["msg"] == "error") {
                echo "<p style='color:red;'>Không thể xóa khoa vì đang có dữ liệu liên quan!</p>";
            }
        }
    ?>

    <p><a href="index.php">Trang chủ Admin</a></p>

    <p><a href="faculty_add.php">Thêm khoa mới</a></p>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Mã khoa</th>
            <th>Tên khoa</th>
            <th>Mô tả</th>
            <th>Thao tác</th>
        </tr>

        <?php if (count($danhSachKhoa) > 0) { ?>

            <?php foreach ($danhSachKhoa as $khoa) { ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($khoa["makhoa"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($khoa["tenkhoa"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($khoa["mota"] ?? ""); ?>
                    </td>

                    <td>
                        <a href="faculty_edit.php?makhoa=<?php echo urlencode($khoa["makhoa"]); ?>">
                            Sửa
                        </a>

                        <form action="faculty_delete.php" method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Bạn có chắc muốn xóa khoa này không?');">

                            <input type="hidden" name="makhoa"
                                value="<?php echo htmlspecialchars($khoa["makhoa"]); ?>">

                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>

        <?php } else { ?>

            <tr>
                <td colspan="4">Chưa có khoa nào.</td>
            </tr>

        <?php } ?>

    </table>

</body>
</html>
