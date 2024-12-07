<?php
// Koneksi ke database
$host = "localhost"; // Ganti dengan hostname database Anda
$user = "root"; // Ganti dengan username database Anda
$pass = ""; // Ganti dengan password database Anda
$db   = "stocktake"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk membaca file CSV dan mengimpor data ke tabel
if (isset($_POST['import'])) {
    // Pastikan file telah diunggah
    if (isset($_FILES['file']['tmp_name'])) {
        $file = fopen($_FILES['file']['tmp_name'], "r");

        // Lewati baris pertama (header)
        fgetcsv($file);

        // Proses setiap baris
        while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
            $assets_code = $row[0];
            $assets_name = $row[1];
            $assets_img = $row[2];

            // Insert ke database
            $sql = "INSERT INTO assets (assets_code, assets_name, assets_img) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $assets_code, $assets_name, $assets_img);
            $stmt->execute();
        }

        fclose($file);
        echo "Data berhasil diimpor!";
    } else {
        echo "Harap unggah file CSV!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data ke Tabel Assets</title>
</head>
<body>
    <h2>Import Data ke Tabel Assets</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="file">Unggah File CSV:</label>
        <input type="file" name="file" id="file" accept=".csv" required>
        <br><br>
        <button type="submit" name="import">Import</button>
    </form>
</body>
</html>
