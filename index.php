<?php
include 'koneksi.php';

function getDataById($conn, $id) {
    $query = "SELECT * FROM crud WHERE id = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];

        $query = "UPDATE crud SET nama = '$nama', nim = '$nim' WHERE id = $id";
    } elseif (isset($_POST['tambah'])) {
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];

        $query = "INSERT INTO crud (nama, nim) VALUES ('$nama', '$nim')";
    } elseif (isset($_POST['hapus'])) {
        $id = $_POST['id'];

        $query = "DELETE FROM crud WHERE id = $id";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: " . $_SERVER['PHP_SELF']);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$query = "SELECT * FROM crud";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Sederhana</title>
    <style>
        form {
            display: flex;
            flex-direction: row;
            max-width: 300px;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-right: 10px;
        }

        form input {
            width: 100px;
            padding: 5px;
            box-sizing: border-box;
        }

        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
            table-layout: auto; 
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Tambah Mahasiswa</h2>
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="nama">Nama:</label>
        <input type="text" name="nama" required>
        <label for="nim">NIM:</label>
        <input type="text" name="nim" required>
        <input type="submit" name="tambah" value="Tambah">
    </form>

    <h2>Data Mahasiswa</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['nim']; ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <input type="submit" name="hapus" value="Hapus">
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <input type="submit" name="edit" value="Edit">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php
    if (isset($_POST['edit'])) {
        $editId = $_POST['id'];
        $data = getDataById($conn, $editId);
    ?>
        <h2>Edit Mahasiswa</h2>
        <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="id" value="<?= $data['id']; ?>">
            <label for="nama">Nama:</label>
            <input type="text" name="nama" value="<?= $data['nama']; ?>" required>
            <label for="nim">NIM:</label>
            <input type="text" name="nim" value="<?= $data['nim']; ?>" required>
            <input type="submit" name="update" value="Update">
        </form>
    <?php
    }
    ?>
</body>
</html>
