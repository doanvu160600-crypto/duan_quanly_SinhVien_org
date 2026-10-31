
<?php
session_start();
require_once "../config/ketnoi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Kiểm tra tài khoản Admin
    $sql = "SELECT * FROM quan_tri_vien WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {

        session_regenerate_id(true);

        $_SESSION["role"] = "admin";
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["hoten"] = $user["hoten"];

        header("Location: ../admin/index.php"); 
        exit();

    } else {

        // Kiểm tra tài khoản Sinh viên
        $sql = "SELECT * FROM sinh_vien WHERE mssv = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["role"] = "sinhvien";
            $_SESSION["user_id"] = $user["mssv"];
            $_SESSION["hoten"] = $user["hoten"];

            header("Location: ../sinhvien/index.php");
            exit();

        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <!-- Tiêu đề -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                DN
            </div>

            <h1 class="text-2xl font-bold text-slate-800">
                ĐĂNG NHẬP HỆ THỐNG
            </h1>

            <p class="text-slate-500 mt-2">
                Hệ thống quản lý sinh viên
            </p>
        </div>

        <!-- Thông báo lỗi -->
        <?php if ($error != "") { ?>
            <div class="bg-red-100 text-red-700 border border-red-200 rounded-lg p-3 mb-5 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <!-- Form đăng nhập -->
        <form method="POST" class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Tên đăng nhập hoặc MSSV
                </label>

                <input
                    type="text"
                    name="username"
                    required
                    autocomplete="username"
                    placeholder="Nhập tên đăng nhập hoặc MSSV"
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Mật khẩu
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Nhập mật khẩu"
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200"
            >
                Đăng nhập
            </button>

        </form>

        <p class="text-center text-sm text-slate-400 mt-8">
            create by zay(trvu)
        </p>

    </div>

</body>
</html>
