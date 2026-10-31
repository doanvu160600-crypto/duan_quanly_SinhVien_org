
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Lấy danh sách lớp và tên khoa
$sql = "SELECT
            l.malop,
            l.tenlop,
            k.tenkhoa
        FROM lop l
        LEFT JOIN khoa k ON l.makhoa = k.makhoa
        ORDER BY l.malop";

$stmt = $conn->prepare($sql);
$stmt->execute();

$danhSachLop = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý lớp học</title>
</head>

<body>

    <h1>QUẢN LÝ LỚP HỌC</h1>

    <?php
    // Hiển thị thông báo kết quả xóa
    if (isset($_GET["msg"])) {

        if ($_GET["msg"] == "deleted") {
            echo "<p style='color:green;'>Xóa lớp thành công!</p>";

        } elseif ($_GET["msg"] == "error") {
            echo "<p style='color:red;'>Không thể xóa lớp vì đang có sinh viên liên kết!</p>";
        }
    }
    ?>

    <p>
        <a href="index.php">Trang chủ Admin</a>
    </p>

    <p>
        <a href="class_add.php">Thêm lớp học</a>
    </p>

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>Mã lớp</th>
            <th>Tên lớp</th>
            <th>Khoa</th>
            <th>Thao tác</th>
        </tr>

        <?php if (count($danhSachLop) > 0) { ?>

            <?php foreach ($danhSachLop as $lop) { ?>

                <tr>
                    <td>
                        <?php echo htmlspecialchars($lop["malop"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($lop["tenlop"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($lop["tenkhoa"] ?? "Chưa có"); ?>
                    </td>

                    <td>
                        <a href="class_edit.php?malop=<?php echo urlencode($lop["malop"]); ?>">
                            Sửa
                        </a>

                        <form action="class_delete.php" method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('Bạn có chắc muốn xóa lớp này không?');">

                            <input type="hidden" name="malop"
                                   value="<?php echo htmlspecialchars($lop["malop"]); ?>">

                            <button type="submit">Xóa</button>

                        </form>
                    </td>
                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>
                <td colspan="4">Chưa có lớp học nào.</td>
            </tr>

        <?php } ?>

    </table>

</body>
</html>
