<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'stocktake'; // Your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to validate the user credentials
    $sql = "SELECT id_user, username, password FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);  // Bind username to query
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id_user, $stored_username, $stored_password);

    // If username exists, verify password
    if ($stmt->num_rows > 0) {
        $stmt->fetch();  // Get the result row

        // Verify the password using password_verify
        if (password_verify($password, $stored_password)) {
            // Password is correct, create a session
            $_SESSION['user_id'] = $id_user;
            $_SESSION['username'] = $stored_username;
            header('Location: index.php');  // Redirect to index.php
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
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: rgb(222, 254, 255);">
    <div class="container mt-5">
        <h1 class="text-center">Login</h1>

        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>{$error_message}</div>";
        }
        ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
