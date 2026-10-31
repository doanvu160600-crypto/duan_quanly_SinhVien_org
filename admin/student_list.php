
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Lấy danh sách sinh viên
$sql = "SELECT
            sv.mssv,
            sv.hoten,
            sv.ngaysinh,
            sv.gioitinh,
            sv.email,
            sv.sdt,
            sv.trangthai,
            l.tenlop,
            k.tenkhoa
        FROM sinh_vien sv
        LEFT JOIN lop l ON sv.malop = l.malop
        LEFT JOIN khoa k ON sv.makhoa = k.makhoa";

$stmt = $conn->prepare($sql);
$stmt->execute();

$danhSach = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy thông báo
$msg = $_GET["msg"] ?? "";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sinh viên</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen">

    <div class="max-w-7xl mx-auto p-6">

        <!-- Tiêu đề -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    QUẢN LÝ SINH VIÊN
                </h1>

                <p class="text-slate-500 mt-1">
                    Danh sách và thông tin sinh viên trong hệ thống
                </p>
            </div>

            <a href="student_add.php"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-medium transition text-center">
                + Thêm sinh viên
            </a>

        </div>

        <!-- Điều hướng -->
        <div class="mb-5">
            <a href="index.php"
               class="text-blue-600 hover:text-blue-800 font-medium">
                ← Quay lại trang quản trị
            </a>
        </div>

        <!-- Thông báo -->
        <?php if ($msg == "deleted") { ?>

            <div class="bg-green-100 text-green-700 border border-green-200 rounded-lg p-4 mb-5">
                Xóa sinh viên thành công!
            </div>

        <?php } elseif ($msg == "notfound") { ?>

            <div class="bg-red-100 text-red-700 border border-red-200 rounded-lg p-4 mb-5">
                Không tìm thấy sinh viên cần xóa!
            </div>

        <?php } ?>

        <!-- Bảng danh sách -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="p-5 border-b">
                <h2 class="text-lg font-bold text-slate-800">
                    Danh sách sinh viên
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Tổng số: <?php echo count($danhSach); ?> sinh viên
                </p>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="px-4 py-4">MSSV</th>
                            <th class="px-4 py-4">Họ tên</th>
                            <th class="px-4 py-4">Ngày sinh</th>
                            <th class="px-4 py-4">Giới tính</th>
                            <th class="px-4 py-4">Lớp</th>
                            <th class="px-4 py-4">Khoa</th>
                            <th class="px-4 py-4">Email</th>
                            <th class="px-4 py-4">SĐT</th>
                            <th class="px-4 py-4">Trạng thái</th>
                            <th class="px-4 py-4 text-center">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">

                        <?php if (count($danhSach) > 0) { ?>

                            <?php foreach ($danhSach as $sv) { ?>

                                <tr class="hover:bg-slate-50">

                                    <td class="px-4 py-4 font-medium text-blue-700">
                                        <?php echo htmlspecialchars($sv["mssv"]); ?>
                                    </td>

                                    <td class="px-4 py-4">
                                        <?php echo htmlspecialchars($sv["hoten"]); ?>
                                    </td>

                                    <td class="px-4 py-4">
                                        <?php echo htmlspecialchars($sv["ngaysinh"]); ?>
                                    </td>

                                    <td class="px-4 py-4">
                                        <?php echo htmlspecialchars($sv["gioitinh"]); ?>
                                    </td>

                                    <td class="px-4 py-4">
                                        <?php echo htmlspecialchars($sv["tenlop"] ?? "Chưa có"); ?>
                                    </td>

                                    <td class="px-4 py-4">
                                        <?php echo htmlspecialchars($sv["tenkhoa"] ?? "Chưa có"); ?>
                                    </td>

                                    <td class="px-4 py-4">
                                        <?php echo htmlspecialchars($sv["email"]); ?>
                                    </td>

                                    <td class="px-4 py-4">
                                        <?php echo htmlspecialchars($sv["sdt"]); ?>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                            <?php echo htmlspecialchars($sv["trangthai"]); ?>
                                        </span>
                                    </td>

                                    <td class="px-4 py-4">

                                        <div class="flex items-center justify-center gap-2">

                                            <a href="student_edit.php?mssv=<?php echo urlencode($sv['mssv']); ?>"
                                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg transition">
                                                Sửa
                                            </a>

                                            <form action="student_delete.php"
                                                  method="POST"
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa sinh viên này không?');">

                                                <input type="hidden"
                                                       name="mssv"
                                                       value="<?php echo htmlspecialchars($sv['mssv']); ?>">

                                                <button type="submit"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg transition">
                                                    Xóa
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>
    
                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="10" class="text-center py-10 text-slate-500">
                                    Chưa có sinh viên nào.
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>
</html>
