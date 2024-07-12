<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Layanan Pengaduan Mahasiswa</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 2px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Layanan Pengaduan Mahasiswa</h2>
    <?php
include '../conn/koneksi.php';

$no = 1; 
$query = "SELECT pengaduan.id_pengaduan, 
pengaduan.tgl_pengaduan, 
mahasiswa.nim, 
mahasiswa.nama AS nama_pelapor, 
petugas.nama_petugas, 
tanggapan.tgl_tanggapan, 
pengaduan.status, 
tanggapan.id_tanggapan, 
pengaduan.foto, 
pengaduan.isi_laporan,
tanggapan.tanggapan
FROM pengaduan 
INNER JOIN mahasiswa ON pengaduan.nik = mahasiswa.nim
LEFT JOIN tanggapan ON pengaduan.id_pengaduan = tanggapan.id_pengaduan
LEFT JOIN petugas ON tanggapan.id_petugas = petugas.id_petugas
ORDER BY pengaduan.tgl_pengaduan DESC";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($koneksi));
}
?>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIM Pelapor</th>
            <th>Nama Pelapor</th>
            <th>Nama Petugas</th>
            <th>Tanggal Masuk</th>
            <th>Tanggal Ditanggapi</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($r = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $r['nim']; ?></td>
                <td><?php echo $r['nama_pelapor']; ?></td>
                <td><?php echo $r['nama_petugas']; ?></td>
                <td><?php echo $r['tgl_pengaduan']; ?></td>
                <td><?php echo $r['tgl_tanggapan']; ?></td>
                <td><?php echo $r['status']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

    <script type="text/javascript">
        window.print();
    </script>
</body>
</html>
