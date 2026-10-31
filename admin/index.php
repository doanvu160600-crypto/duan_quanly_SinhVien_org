
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang quản trị Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen">

    <!-- Header -->
    <header class="bg-blue-900 text-white px-6 py-5 shadow-md">
        <h1 class="text-2xl font-bold">
            HỆ THỐNG QUẢN LÝ SINH VIÊN
        </h1>

        <p class="mt-2 text-blue-100">
            Xin chào,
            <span class="font-semibold">
                <?php echo htmlspecialchars($_SESSION["hoten"]); ?>
            </span>
        </p>
    </header>

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 text-white p-5 shrink-0">

            <h2 class="text-lg font-bold mb-6 border-b border-slate-600 pb-4">
                MENU ADMIN
            </h2>

            <nav class="space-y-2">

                <a href="index.php"
                   class="block px-4 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 transition">
                    Trang chủ
                </a>

                <a href="student_list.php"
                   class="block px-4 py-3 rounded-lg hover:bg-slate-700 transition">
                    Quản lý sinh viên
                </a>

                <a href="class_list.php"
                   class="block px-4 py-3 rounded-lg hover:bg-slate-700 transition">
                    Quản lý lớp
                </a>

                <a href="faculty_list.php"
                   class="block px-4 py-3 rounded-lg hover:bg-slate-700 transition">
                    Quản lý khoa
                </a>

                <a href="notification.php"
                   class="block px-4 py-3 rounded-lg hover:bg-slate-700 transition">
                    Quản lý thông báo
                </a>

                <a href="../auth/logout.php"
                   class="block px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 transition mt-6">
                    Đăng xuất
                </a>

            </nav>
        </aside>

        <!-- Nội dung chính -->
        <main class="flex-1 p-8">

            <h2 class="text-2xl font-bold text-slate-800 mb-3">
                TRANG QUẢN TRỊ
            </h2>

            <p class="text-slate-600 mb-8">
                Chào mừng bạn đến với hệ thống quản lý sinh viên.
            </p>

            <!-- Các thẻ chức năng -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

                <a href="student_list.php"
                   class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-t-4 border-blue-500">

                    <h3 class="text-xl font-bold text-blue-700 mb-3">
                        Sinh viên
                    </h3>

                    <p class="text-slate-600">
                        Quản lý thông tin sinh viên.
                    </p>

                    <p class="text-blue-600 font-medium mt-4">
                        Xem chi tiết →
                    </p>
                </a>

                <a href="class_list.php"
                   class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-t-4 border-green-500">

                    <h3 class="text-xl font-bold text-green-700 mb-3">
                        Lớp học
                    </h3>

                    <p class="text-slate-600">
                        Quản lý danh sách lớp.
                    </p>

                    <p class="text-green-600 font-medium mt-4">
                        Xem chi tiết →
                    </p>
                </a>

                <a href="faculty_list.php"
                   class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-t-4 border-purple-500">

                    <h3 class="text-xl font-bold text-purple-700 mb-3">
                        Khoa
                    </h3>

                    <p class="text-slate-600">
                        Quản lý thông tin khoa.
                    </p>

                    <p class="text-purple-600 font-medium mt-4">
                        Xem chi tiết →
                    </p>
                </a>

                <a href="notification.php"
                   class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-t-4 border-orange-500">

                    <h3 class="text-xl font-bold text-orange-700 mb-3">
                        Thông báo
                    </h3>

                    <p class="text-slate-600">
                        Quản lý thông báo hệ thống.
                    </p>

                    <p class="text-orange-600 font-medium mt-4">
                        Xem chi tiết →
                    </p>
                </a>

            </div>

        </main>
    </div>

</body>
</html>
