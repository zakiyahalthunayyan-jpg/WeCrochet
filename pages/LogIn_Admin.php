<?php
// Zakiyah Al-Thunayyan 2240005958

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../includes/Connection.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['admin_user'];
    $password = $_POST['admin_pass'];

    $sql = "SELECT * FROM admin WHERE A_UserName = ? AND A_Password = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $username;

        header("Location: AdminPage.php");
        exit();

    } else {
        $error = "Invalid username or password";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>WeCrochet Login</title>
    <link rel="stylesheet" href="../Styles/LogIn_Admin.css">

    <script>
        function validateLogin() {

            let username = document.getElementById("username").value;
            let password = document.getElementById("password").value;

            if (username == "") {
                alert("Username is required");
                return false;
            }

            if (password == "") {
                alert("Password is required");
                return false;
            }

            if (password.length < 5) {
                alert("Password must be at least 5 characters");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>

<div class="container">

    <div class="left-panel">

        <h1>WeCrochet</h1>

        <p class="welcome">
            <img src="../images/logo.1.jpeg" alt="Logo" class="logo">

            Welcome Admin <br>
            Manage your store and control your content easily.
        </p>

    </div>

    <div class="right-panel">

        <h2>Login</h2>

        <?php if (!empty($error)) { ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php } ?>

        <form action="" method="POST" onsubmit="return validateLogin()">

            <input type="text"
                   id="username"
                   name="admin_user"
                   placeholder="Username"
                   class="input-box">

            <input type="password"
                   id="password"
                   name="admin_pass"
                   placeholder="Password"
                   class="input-box">

            <button type="submit" class="login-btn">
                LogIn
            </button>

        </form>

    </div>

</div>

</body>
</html>