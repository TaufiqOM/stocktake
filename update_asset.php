<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assets_code = $_POST['asset_code'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $pic = $_POST['pic'];
    $status = $_POST['status'];
    $kondisi = $_POST['kondisi'] ?? null;
    $user_id = $_SESSION['user_id'];

    $bukti_foto = null;
    
    if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['proof_file']['tmp_name'];
        $file_name = $_FILES['proof_file']['name'];
        $file_size = $_FILES['proof_file']['size'];
        $file_error = $_FILES['proof_file']['error'];

        $upload_dir = 'bukti/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_path = $upload_dir . basename($file_name);

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array(mime_content_type($file_tmp_name), $allowed_types)) {
            if (move_uploaded_file($file_tmp_name, $file_path)) {
                $bukti_foto = $file_name;
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid file type']);
            exit;
        }
    }

    $sql = "UPDATE assets SET 
            id_lokasi = ?, 
            id_kategori = ?, 
            id_pic = ?, 
            status = ?, 
            kondisi = ?, 
            id_user = ?, 
            bukti_foto = ?  
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
