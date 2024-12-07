<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'stocktake';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id_user, username, password FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id_user, $stored_username, $stored_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        if (password_verify($password, $stored_password)) {
            $_SESSION['user_id'] = $id_user;
            $_SESSION['username'] = $stored_username;

            if ($password === '1234') {
                header('Location: change_password.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: rgb(222, 254, 255);">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg" style="width: 400px;">
            <div class="card-header bg-info text-white text-center">
                <h2>Login</h2>
            </div>
            <div class="card-body">
                <?php
                if (isset($error_message)) {
                    echo "<div class='alert alert-danger'>{$error_message}</div>";
                }
                ?>
                <div class="text-center">
                    <img src="logo/logo.png" alt="Logo" class="mb-3" style="width: 80px; height: 50px;">
                </div>
                <form method="POST" action="">
                    <div class="mb-3">
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <small>© 2024 Stocktake System</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>