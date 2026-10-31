
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Lấy danh sách thông báo
$sql = "SELECT * FROM thong_bao ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$danhSachThongBao = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý thông báo</title>
    
    <?php
    if (isset($_GET["msg"])) {

        if ($_GET["msg"] == "deleted") {
            echo "<p style='color:green;'>Xóa thông báo thành công!</p>";

        } elseif ($_GET["msg"] == "error") {
            echo "<p style='color:red;'>Không thể xóa thông báo!</p>";
        }
    }
    ?>

</head>
<body>

    <h1>QUẢN LÝ THÔNG BÁO</h1>

    <p>
        <a href="index.php">Trang chủ Admin</a>
    </p>

    <p>
        <a href="notification_add.php">Thêm thông báo mới</a>
    </p>

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Nội dung</th>
            <th>Ngày tạo</th>
            <th>Thao tác</th>
        </tr>

        <?php if (count($danhSachThongBao) > 0) { ?>

            <?php foreach ($danhSachThongBao as $tb) { ?>

                <tr>
                    <td><?php echo htmlspecialchars($tb["id"]); ?></td>

                    <td><?php echo htmlspecialchars($tb["tieude"]); ?></td>

                    <td><?php echo nl2br(htmlspecialchars($tb["noidung"])); ?></td>

                    <td><?php echo htmlspecialchars($tb["created_at"]); ?></td>

                    <td>
                        
                        <a href="notification_edit.php?id=<?php echo urlencode($tb["id"]); ?>">
                            Sửa
                        </a>

                        <form action="notification_delete.php" method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Bạn có chắc muốn xóa thông báo này không?');">

                            <input type="hidden" name="id"
                                value="<?php echo htmlspecialchars($tb["id"]); ?>">

                            <button type="submit">Xóa</button>
                        </form>

                    </td>
                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>
                <td colspan="5">Chưa có thông báo nào.</td>
            </tr>

        <?php } ?>

    </table>

</body>
</html>
