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
    $assets_code = $_POST['assets_code'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $pic = $_POST['pic'];
    $status = $_POST['status'];
    $kondisi = $_POST['kondisi'] ?? null;
    $user_id = $_SESSION['user_id'];

    $bukti_foto = null;

    error_log("POST Data: " . print_r($_POST, true));

    if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['proof_file']['tmp_name'];
        $file_name = $_FILES['proof_file']['name'];
        $upload_dir = 'bukti/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($file_tmp_name);

        if (in_array($file_type, $allowed_types)) {
            $compressed_file_path = $upload_dir . 'compressed_' . basename($file_name);

            switch ($file_type) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($file_tmp_name);
                    imagejpeg($image, $compressed_file_path, 40); // Compress to 40% quality
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($file_tmp_name);
                    imagepng($image, $compressed_file_path, 8); // PNG compression (scale 0-9)
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($file_tmp_name);
                    imagegif($image, $compressed_file_path); // No quality adjustment for GIF
                    break;
                default:
                    echo json_encode(['success' => false, 'error' => 'Unsupported file type']);
                    exit;
            }

            imagedestroy($image); // Free memory
            $bukti_foto = 'compressed_' . basename($file_name);
            error_log('File berhasil dikompresi dan disimpan: ' . $bukti_foto);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid file type']);
            exit;
        }
    } else {
        error_log('Tidak ada file yang diupload atau error: ' . $_FILES['proof_file']['error']);
    }

    error_log('Nama Foto untuk Database: ' . $bukti_foto);

    $sql = "UPDATE assets SET 
            id_lokasi = ?, 
            id_kategori = ?, 
            id_pic = ?, 
            status = ?, 
            kondisi = ?, 
            id_user = ?, 
            bukti_foto = ?  
            WHERE assets_code = ?";

    error_log('Query: ' . $sql);

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
