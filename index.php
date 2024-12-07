<?php
session_start();

if (!isset($_SESSION['username'])) {
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

$locations = [];
$sql_locations = 'SELECT id_lokasi, nama_lokasi FROM lokasi';
$result_locations = $conn->query($sql_locations);
if ($result_locations->num_rows > 0) {
    while ($row = $result_locations->fetch_assoc()) {
        $locations[] = $row;
    }
}

$categories = [];
$sql_categories = 'SELECT id_kategori, nama_kategori FROM kategori';
$result_categories = $conn->query($sql_categories);
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
}

$pics = [];
$sql_pics = 'SELECT id_pic, nama_pic FROM pic';
$result_pics = $conn->query($sql_pics);
if ($result_pics->num_rows > 0) {
    while ($row = $result_pics->fetch_assoc()) {
        $pics[] = $row;
    }
}

if (isset($_POST['submit'])) {
    $assets_code = $_POST['assets_code'];

    $sql = "SELECT 
                a.assets_id, a.assets_code, a.assets_name, a.assets_img, 
                a.status, a.kondisi, 
                l.id_lokasi, l.nama_lokasi, 
                k.id_kategori, k.nama_kategori, 
                p.id_pic, p.nama_pic
            FROM assets a
            LEFT JOIN lokasi l ON a.id_lokasi = l.id_lokasi
            LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
            LEFT JOIN pic p ON a.id_pic = p.id_pic
            WHERE a.assets_code = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $assets_code);
    $stmt->execute();
    $result = $stmt->get_result();

    $card_output = '';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected_id_lokasi = $row['id_lokasi'] ?? null;
            $selected_id_kategori = $row['id_kategori'] ?? null;
            $selected_id_pic = $row['id_pic'] ?? null;
            $asset_img = $row['assets_img'] ? 'foto/' . $row['assets_img'] : 'foto/assets.png';

            if (!file_exists($asset_img)) {
                $asset_img = 'foto/assets.png';
            }

            $card_output .= "
                <div class='col-12 col-md-8 col-lg-10 mb-4'>
                    <div class='card shadow-lg'>
                        <div class='card-header text-center bg-primary text-white'>
                            <strong>Assets Code : </strong> <h2>{$row['assets_code']}</h2>
                        </div>
                        <div class='row g-0'>
                            <div class='col-md-4 d-flex align-items-center justify-content-center'>
                                <img src='{$asset_img}' class='img-fluid rounded-start' alt='{$row['assets_name']}'>
                            </div>
                            <div class='col-md-8'>
                                <div class='card-body'>
                                    <h4 class='card-title'>{$row['assets_name']}</h4>
                                    <p class='card-text'>
                                        <br>
                                        <strong>Status Barang:</strong>
                                        <select class='form-select status-barang'>
                                        <option value='0' " . ($row['status'] == 0 ? 'selected' : '') . ">Tidak Ditemukan</option>
                                        <option value='1' " . ($row['status'] == 1 ? 'selected' : '') . ">Ditemukan</option>
                                        </select>
                                        <div class='kondisi-barang-section' style='display: " . ($row['status'] == 1 ? 'block' : 'none') . ";'>
                                            <strong>Kondisi Barang:</strong>
                                            <select class='form-select kondisi-barang'>
                                                <option value='1' " . ($row['kondisi'] == 1 ? 'selected' : '') . ">Rusak</option>
                                                <option value='2' " . ($row['kondisi'] == 2 ? 'selected' : '') . ">Kurang Baik</option>
                                                <option value='3' " . ($row['kondisi'] == 3 ? 'selected' : '') . ">Cukup Baik</option>
                                                <option value='4' " . ($row['kondisi'] == 4 ? 'selected' : '') . ">Baik</option>
                                            </select>
                                        </div>
                                        <strong>Lokasi:</strong>
                                        <select class='form-select location'>
                                            " . generateLocationOptions($locations, $selected_id_lokasi) . "
                                        </select><br>
                                        <strong>Kategori:</strong>
                                        <select class='form-select category'>
                                            " . generateCategoryOptions($categories, $selected_id_kategori) . "
                                        </select><br>
                                        <strong>PIC:</strong>
                                        <select class='form-select pic'>
                                            " . generatePicOptions($pics, $selected_id_pic) . "
                                        </select><br>
                                        <strong>Foto:</strong>
                                        <input type='file' name='proof_file' class='form-control' accept='image/*'>
                                    </p>
                                    <button class='btn btn-success save-changes'>Simpan Perubahan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";
        }
    } else {
        $card_output = "<div class='alert alert-warning'>No data found.</div>";
    }

    $stmt->close();
}

$conn->close();

function generateLocationOptions($locations, $selected_id_lokasi)
{
    $options = "<option value='' " . (is_null($selected_id_lokasi) ? 'selected' : '') . '>Pilih Lokasi</option>';
    foreach ($locations as $location) {
        $is_selected = $location['id_lokasi'] == $selected_id_lokasi ? 'selected' : '';
        $options .= "<option value='{$location['id_lokasi']}' $is_selected>{$location['nama_lokasi']}</option>";
    }
    return $options;
}

function generateCategoryOptions($categories, $selected_id_kategori)
{
    $options = "<option value='' " . (is_null($selected_id_kategori) ? 'selected' : '') . '>Pilih Kategori</option>';
    foreach ($categories as $category) {
        $is_selected = $category['id_kategori'] == $selected_id_kategori ? 'selected' : '';
        $options .= "<option value='{$category['id_kategori']}' $is_selected>{$category['nama_kategori']}</option>";
    }
    return $options;
}

function generatePicOptions($pics, $selected_id_pic)
{
    $options = "<option value='' " . (is_null($selected_id_pic) ? 'selected' : '') . '>Pilih PIC</option>';
    foreach ($pics as $pic) {
        $is_selected = $pic['id_pic'] == $selected_id_pic ? 'selected' : '';
        $options .= "<option value='{$pic['id_pic']}' $is_selected>{$pic['nama_pic']}</option>";
    }
    return $options;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Aset</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body style="background-color: rgb(222, 254, 255);">
    <div class="container mt-5">
        <h1 class="text-center">Cari Data Aset</h1>
        <form method="POST" action="" class="mb-4" enctype="multipart/form-data">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-10 mb-4">
                    <div class="input-group">
                        <input type="text" name="assets_code" id="assets_code" class="form-control"
                            placeholder="Masukkan Kode Aset" required autofocus>
                        <button type="submit" name="submit" class="btn btn-primary btn-sm">Cari</button>
                    </div>
                </div>
            </div>
        </form>

    <div class="row justify-content-center d-flex align-items-center">
        <?php echo isset($card_output) ? $card_output : ''; ?>
    </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.js"></script>

    <script>
        document.querySelectorAll('.status-barang').forEach(function (select) {
            select.addEventListener('change', function () {
                const kondisiSection = this.closest('.card-body').querySelector('.kondisi-barang-section');
                if (this.value === '1') {
                    kondisiSection.style.display = 'block';
                } else {
                    kondisiSection.style.display = 'none';
                }
            });
        });
        document.querySelectorAll('.save-changes').forEach(function (button) {
            button.addEventListener('click', function () {
                const cardBody = this.closest('.card-body');
                const assetCode = cardBody.closest('.card').querySelector('.card-header h2').textContent.trim();
                const location = cardBody.querySelector('.location').value;
                const category = cardBody.querySelector('.category').value;
                const pic = cardBody.querySelector('.pic').value;
                const status = cardBody.querySelector('.status-barang').value;
                const kondisi = cardBody.querySelector('.kondisi-barang').value;
                const fileInput = cardBody.querySelector('input[name="proof_file"]');
                const file = fileInput.files[0];

                const formData = new FormData();
                formData.append('assets_code', assetCode);
                formData.append('location', location);
                formData.append('category', category);
                formData.append('pic', pic);
                formData.append('status', status);
                formData.append('kondisi', kondisi);
                if (file) {
                    formData.append('proof_file', file);
                }

                fetch('update_asset.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Changes saved successfully!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to save changes!',
                                icon: 'error',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>
</body>

</html>
