<table class="responsive-table" border="1" style="width: 100%;">
	<tr>
		<td><h4 class="orange-text hide-on-med-and-down">Daftar Laporan</h4></td>
	</tr>
	<tr>
		<td>
			<?php
			include "../conn/koneksi.php";
			session_start();

			if (!isset($_SESSION['data']['nim'])) {
				die("Anda harus login untuk mengakses halaman ini.");
			}

			$no = 1;
			$nim = $_SESSION['data']['nim'];

			$query = "SELECT pengaduan.id_pengaduan, pengaduan.tgl_pengaduan, mahasiswa.nim, mahasiswa.nama AS nama_pelapor, 
							 petugas.nama_petugas, tanggapan.tgl_tanggapan, pengaduan.status, tanggapan.id_tanggapan, pengaduan.foto, pengaduan.isi_laporan
					  FROM pengaduan 
					  INNER JOIN mahasiswa ON pengaduan.nik = mahasiswa.nim
					  LEFT JOIN tanggapan ON pengaduan.id_pengaduan = tanggapan.id_pengaduan
					  LEFT JOIN petugas ON tanggapan.id_petugas = petugas.id_petugas
					  WHERE mahasiswa.nim = '$nim'  -- Filter berdasarkan nim pengguna yang login
					  ORDER BY pengaduan.tgl_pengaduan DESC";
			

			$result = mysqli_query($koneksi, $query);

			if (!$result) {
				die("Query failed: " . mysqli_error($koneksi));
			}
			?>

			<table border="3" class="responsive-table striped highlight">
				<tr>
					<td>No</td>
					<td>NIM</td>
					<td>Nama</td>
					<td>Tanggal Masuk</td>
					<td>Status</td>
					<td>Opsi</td>
				</tr>
				<?php while ($r = mysqli_fetch_assoc($result)) { ?>
					<tr>
						<td><?php echo $no++; ?></td>
						<td><?php echo $r['nim']; ?></td>
						<td><?php echo $r['nama_pelapor']; ?></td>
						<td><?php echo $r['tgl_pengaduan']; ?></td>
						<td><?php echo $r['status']; ?></td>
						<td>
							<a class="btn blue modal-trigger" href="#tanggapan_<?php echo $r['id_pengaduan']; ?>">More</a> 
							<a class="btn red" onclick="return confirm('Anda Yakin Ingin Menghapus Y/N')" href="index.php?p=pengaduan_hapus&id_pengaduan=<?php echo $r['id_pengaduan']; ?>">Hapus</a>
						</td>

						<!-- Modal untuk detail tanggapan -->
						<div id="tanggapan_<?php echo $r['id_pengaduan']; ?>" class="modal">
							<div class="modal-content">
								<h4 class="orange-text">Detail</h4>
								<div class="col s12">
									<p>NIM : <?php echo $r['nim']; ?></p>
									<p>Dari : <?php echo $r['nama_pelapor']; ?></p>
									<p>Petugas : <?php echo $r['nama_petugas']; ?></p>
									<p>Tanggal Masuk : <?php echo $r['tgl_pengaduan']; ?></p>
									<p>Tanggal Ditanggapi : <?php echo $r['tgl_tanggapan']; ?></p>
									<?php if ($r['foto'] == "kosong" || empty($r['foto'])) { ?>
										<img src="../img/noImage.png" width="100">
									<?php } else { ?>
										<img width="100" src="../img/<?php echo $r['foto']; ?>">
									<?php } ?>
									<br><b>Pesan</b>
									<p><?php echo $r['isi_laporan']; ?></p>
									<br><b>Respon</b>
									<p><?php echo $r['tanggapan']; ?></p>
								</div>
							</div>
							<div class="modal-footer col s12">
								<a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
							</div>
						</div>
						<!-- Akhir modal -->

					</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
</table>

<?php 
if(isset($_POST['kirim'])){
	$nim = $_SESSION['data']['nim'];
	$tgl = date('Y-m-d');
	$foto = $_FILES['foto']['name'];
	$source = $_FILES['foto']['tmp_name'];
	$folder = './../img/';
	$listeks = array('jpg','png','jpeg');
	$pecah = explode('.', $foto);
	$eks = $pecah['1'];
	$size = $_FILES['foto']['size'];
	$nama = date('dmYis').$foto;

	if($foto !=""){
		if(in_array($eks, $listeks)){
			if($size<=500000){
				move_uploaded_file($source, $folder.$nama);
				$query = mysqli_query($koneksi,"INSERT INTO pengaduan VALUES (NULL,'$tgl','$nim','".$_POST['laporan']."','$nama','proses')");

				if($query){
					echo "<script>alert('Pengaduan Akan Segera di Proses')</script>";
					echo "<script>location='index.php';</script>";
				}

			}
			else{
				echo "<script>alert('Akuran Gambar Tidak Lebih Dari 500KB')</script>";
			}
		}
		else{
			echo "<script>alert('Format File Tidak Di Dukung')</script>";
		}
	}
	else{
		$query = mysqli_query($koneksi,"INSERT INTO pengaduan VALUES (NULL,'$tgl','$nim','".$_POST['laporan']."','noImage.png','proses')");
		if($query){
			echo "<script>alert('Pengaduan Akan Segera Ditanggapi')</script>";
			echo "<script>location='index.php';</script>";
		}
	}
}
?>
