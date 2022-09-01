<?php
//koneksi database mysql
$host = "localhost";
$user = "root";
$pass = "";
$database = "dbmahasiswa";

$koneksi = mysqli_connect($host, $user, $pass, $database);

if (!$koneksi) {
    echo "database tidak dapat terhubung";
}

$nim = "";
$nama = "";
$prodi = "";
$alamat = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
  $op = $_GET['op'];
}else {
    $op = "";
}

if ($op == 'ubah') {
    $nim = $_GET['nim'];
    $query = "select * from mahasiswa where NIM = '$nim' ";
    $ubah = mysqli_query($koneksi, $query);
    $tampil = mysqli_fetch_array($ubah);
    $nim = $tampil ['NIM'];
    $nama = $tampil ['nama_mahasiswa'];
    $prodi = $tampil ['prodi'];
    $alamat = $tampil ['alamat'];

    if ($nim == '') {
        $error = "data tidak ditemukan";
    }
}

if ($op == 'hapus') {
    $nim = $_GET['nim'];
    $query = "delete from mahasiswa where NIM = '$nim'";
    $hapus = mysqli_query($koneksi, $query);
    if ($hapus) {
        $sukses = "data berhasil di hapus";
        $nim = "";
    }else {
        $error  = "data gagal di hapus";
    }
}


if (isset($_POST['simpan'])) {
    $nim = $_POST['NIM'];
    $nama = $_POST['nama'];
    $prodi = $_POST['prodi'];
    $alamat = $_POST['alamat'];

    if ($nim && $nama && $prodi && $alamat) {
        if ($op == 'ubah') {
            $query = "update mahasiswa set nama_mahasiswa = '$nama', prodi = '$prodi', alamat = '$alamat' where 
            NIM = '$nim'";
            $ubah = mysqli_query($koneksi, $query);
            if ($ubah) {
                $sukses = "data berhasil di update";
                $nim = "";
                $nama = "";
                $prodi = "";
                $alamat = "";
            } else {
                $error = "data gagal di ";
            }
        } else {
            $query = "insert into mahasiswa values ('$nim', '$nama', '$prodi', '$alamat')";
            $simpan = mysqli_query($koneksi, $query);
            if ($simpan) {
                $sukses = "data berhasil di simpan";
                $nim = "";
                $nama = "";
                $prodi = "";
                $alamat = "";
            } else {
                $error = "data gagal di simpan";
            }
        }
    } else {
        $error = "silahkan masukkan semua data";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="mx-auto">
        <div class="card">
            <div class="card-header text-white bg-primary">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses; ?>
                    </div>
                <?php
                }
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="NIM" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="NIM" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="prodi" class="col-sm-2 col-form-label">Prodi</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="prodi" name="prodi">
                                <option value="">-Pilih Prodi-</option>
                                <option value="Teknik Informatika" <?php if ($prodi == 'Teknik Informatika') echo "
                                   selected ";  ?>>Teknik Informatika</option>
                                <option value="Teknik Industri" <?php if ($prodi == 'Teknik Industri') echo "
                                   selected ";  ?>>Teknik Industri</option>
                                <option value="Teknik Mesin" <?php if ($prodi == 'Teknik Mesin') echo "
                                   selected ";  ?>>Teknik Mesin</option>
                                <option value="Teknik Elektro" <?php if ($prodi == 'Teknik Elektro') echo "
                                   selected ";  ?>>Teknik Elektro</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo $alamat ?></textarea>
                        </div>
                    </div>
                    <div class="col-12" align="right">
                        <input type="submit" name="simpan" value="simpan data" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header text-white bg-dark">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Prodi</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query  = "select * from mahasiswa order by NIM asc";
                        $tampil = mysqli_query($koneksi, $query);
                        $urut = 1;
                        while ($result = mysqli_fetch_array($tampil)) {
                            $nim    = $result['NIM'];
                            $nama   = $result['nama_mahasiswa'];
                            $prodi  = $result['prodi'];
                            $alamat = $result['alamat'];
                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++; ?></th>
                                <td scope="row"><?php echo $nim; ?></td>
                                <td scope="row"><?php echo $nama; ?></td>
                                <td scope="row"><?php echo $prodi; ?></td>
                                <td scope="row"><?php echo $alamat; ?></td>
                                <td scope="row">
                                    <a href="index.php?op=ubah&nim=<?php echo $nim ?>"><button type="button" class="btn 
                                    btn-warning">Edit</button></a>
                                    <a href="index.php?op=hapus&nim=<?php echo $nim ?>" onclick="return confirm ('apakah anda yakin ingin menghapus data ini ?')"><button type="button" class="btn
                                     btn-danger">Hapus</button></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>