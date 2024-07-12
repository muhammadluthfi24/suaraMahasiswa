<style>
body, html {
            margin: 0;
            padding: 0;
            height: 100%;
			overflow-x: hidden;
        }
        .wrapper {
            min-height: 100%;
            position: relative;
        }
        .content {
            padding-bottom: 70px; 
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: left;
			margin-left: 450px;
            padding: 20px; 
            font-size: 14px;
            color: #777;
        }
    </style>
<table class="responsive-table" border="1" style="width: 100%;">
	<tr>
		<td><h4 class="orange-text hide-on-med-and-down">Tambah Pengaduan</h4></td>
		
	</tr>
	<tr>
		<td style="padding: 20px;">
			<form method="POST" enctype="multipart/form-data">
				<textarea class="materialize-textarea" name="laporan" required placeholder="Tulis Laporan"></textarea><br><br>
				<label>Gambar</label>
				<input type="file" name="foto" required><br><br>
				<input type="submit" name="kirim" value="Kirim" class="btn">
			</form>
		</td>

		
	</tr>
</table>
<?php
include "../conn/koneksi.php"; // Pastikan koneksi ke database sudah benar

if (isset($_POST['kirim'])) {
    $nim = $_SESSION['data']['nim']; // Ambil nim dari sesi
    $tgl = date('Y-m-d');
    $laporan = $_POST['laporan'];

    // Proses unggah file gambar
    $foto = $_FILES['foto']['name'];
    $source = $_FILES['foto']['tmp_name'];
    $folder = './../img/';
    $listeks = array('jpg', 'png', 'jpeg');
    $pecah = explode('.', $foto);
    $eks = strtolower(end($pecah)); // Menggunakan strtolower untuk memastikan ekstensi dalam huruf kecil
    $size = $_FILES['foto']['size'];
    $nama = date('dmYis') . $foto;

    // Validasi dan unggah gambar
    if (!empty($foto)) {
        if (in_array($eks, $listeks)) {
            if ($size <= 500000) {
                if (move_uploaded_file($source, $folder . $nama)) {
                    // Query untuk menyimpan data pengaduan
                    $query = mysqli_query($koneksi, "INSERT INTO pengaduan (tgl_pengaduan, nik, isi_laporan, foto, status) 
                                                     VALUES ('$tgl', '$nim', '$laporan', '$nama', 'proses')");

                    if ($query) {
                        echo "<script>alert('Pengaduan Akan Segera di Proses')</script>";
                        echo "<script>location='index.php';</script>";
                    } else {
                        echo "Error: " . mysqli_error($koneksi);
                    }
                } else {
                    echo "<script>alert('Gagal mengunggah file')</script>";
                }
            } else {
                echo "<script>alert('Ukuran Gambar Tidak Lebih Dari 500KB')</script>";
            }
        } else {
            echo "<script>alert('Format File Tidak Didukung')</script>";
        }
    } else {
        // Jika tidak ada gambar yang diunggah, masukkan data dengan gambar default
        $query = mysqli_query($koneksi, "INSERT INTO pengaduan (tgl_pengaduan, nik, isi_laporan, foto, status) 
                                         VALUES ('$tgl', '$nim', '$laporan', 'noImage.png', 'proses')");

        if ($query) {
            echo "<script>alert('Pengaduan Akan Segera Ditanggapi')</script>";
            echo "<script>location='index.php';</script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
}
?>
	    <!-- Footer text -->
		<div class="footer">
        <i><?php include "../layout/footer.html" ?></i>
    </div>
</body>
</html>