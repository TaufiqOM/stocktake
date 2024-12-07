<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'stocktake';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if (isset($_POST['change_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE user SET password = ? WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $new_password, $user_id);

    if ($stmt->execute()) {
        session_destroy();
        header('Location: login.php');
        exit;
    } else {
        $error_message = "Failed to update password.";
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
    <title>Ganti Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: rgb(222, 254, 255);">
    <div class="container mt-5">
    <div class="d-flex align-items-center mb-4">
            <img src="logo/logo.png" alt="Logo" class="me-3" style="width: 80px; height: 50px;">
            <h1 class="text-center flex-grow-1">Ganti Password</h1>
        </div>

        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>{$error_message}</div>";
        }
        ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="new_password" class="form-label">Password Baru</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">Ganti Password</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
