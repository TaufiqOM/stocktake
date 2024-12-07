<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'stocktake'; // Replace with your actual database name

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form submission
    $assets_code = $_POST['asset_code'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $pic = $_POST['pic'];
    $status = $_POST['status'];
    $kondisi = $_POST['kondisi'] ?? null; // Null if no condition selected
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID from session

    // Handle file upload
    $bukti_foto = null;  // Default to null in case no file is uploaded
    
    if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] === UPLOAD_ERR_OK) {
        // Get file details
        $file_tmp_name = $_FILES['proof_file']['tmp_name'];
        $file_name = $_FILES['proof_file']['name'];
        $file_size = $_FILES['proof_file']['size'];
        $file_error = $_FILES['proof_file']['error'];

        // Define upload directory
        $upload_dir = 'bukti/';

        // Ensure the upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);  // Create the directory if it doesn't exist
        }

        // Set the file path
        $file_path = $upload_dir . basename($file_name);

        // Check if the file is a valid image (optional, you can adjust this as needed)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array(mime_content_type($file_tmp_name), $allowed_types)) {
            // Move the uploaded file to the upload directory
            if (move_uploaded_file($file_tmp_name, $file_path)) {
                $bukti_foto = $file_name;  // Store the file name in the database
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid file type']);
            exit;
        }
    }

    // Update query to include the user_id and bukti_foto
    $sql = "UPDATE assets SET 
            id_lokasi = ?, 
            id_kategori = ?, 
            id_pic = ?, 
            status = ?, 
            kondisi = ?, 
            id_user = ?, 
            bukti_foto = ?  -- Add bukti_foto field to the update query
            WHERE assets_code = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiissss', $location, $category, $pic, $status, $kondisi, $user_id, $bukti_foto, $assets_code);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
