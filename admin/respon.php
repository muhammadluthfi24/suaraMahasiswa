<div class="row">
    <div class="col s12 m9">
        <h3 class="orange-text">Respon</h3>
    </div>
</div>

<table id="example" class="display responsive-table" style="width:100%">
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Petugas</th>
            <th>Tanggal Masuk</th>
            <th>Tanggal Ditanggapi</th>
            <th>Status</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>

    <?php 
    include "../conn/koneksi.php";

    $no = 1;
    $query = mysqli_query($koneksi, "SELECT pengaduan.id_pengaduan, 
        pengaduan.tgl_pengaduan, 
        mahasiswa.nim, 
        mahasiswa.nama, 
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
    ORDER BY pengaduan.tgl_pengaduan DESC");

    if (!$query) {
        die("Query failed: " . mysqli_error($koneksi));
    }

    while ($r = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $r['nim']; ?></td>
            <td><?php echo $r['nama']; ?></td> 
            <td><?php echo $r['nama_petugas'] ?? 'Belum ditanggapi'; ?></td>
            <td><?php echo $r['tgl_pengaduan']; ?></td>
            <td><?php echo $r['tgl_tanggapan'] ?? 'Belum ditanggapi'; ?></td>
            <td><?php echo $r['status']; ?></td>
            <td>
                <a class="btn blue modal-trigger" href="#more<?php echo $r['id_pengaduan']; ?>">More</a>
            </td>
        </tr>

    <?php } ?>

    </tbody>
</table>

<!-- Modal Structure -->
<?php 
mysqli_data_seek($query, 0); // Kembali ke awal data setelah loop sebelumnya

while ($r = mysqli_fetch_assoc($query)) { ?>
    <div id="more<?php echo $r['id_pengaduan']; ?>" class="modal">
        <div class="modal-content">
            <h4 class="orange-text">Detail</h4>
            <div class="col s12">
                <p>NIM : <?php echo $r['nim']; ?></p>
                <p>Dari : <?php echo $r['nama']; ?></p>
                <p>Petugas : <?php echo $r['nama_petugas'] ?? 'Belum ditanggapi'; ?></p>
                <p>Tanggal Masuk : <?php echo $r['tgl_pengaduan']; ?></p>
                <p>Tanggal Ditanggapi : <?php echo $r['tgl_tanggapan'] ?? 'Belum ditanggapi'; ?></p>
                <?php 
                if ($r['foto'] == "kosong") { ?>
                    <img src="../img/noImage.png" width="100">
                <?php } else { ?>
                    <img width="100" src="../img/<?php echo $r['foto']; ?>">
                <?php } ?>
                <br><b>Pesan</b>
                <p><?php echo $r['isi_laporan']; ?></p>
                <br><b>Respon</b>
                <p><?php echo $r['tanggapan'] ?? 'Belum ditanggapi'; ?></p>

                <?php if ($r['status'] == 'proses') { ?>
                    <form method="POST">
   						 <input type="hidden" name="id_pengaduan" value="<?php echo $r['id_pengaduan']; ?>">
    						<div class="input-field">
     						   <label for="textarea<?php echo $r['id_pengaduan']; ?>">Tanggapan</label>
      								  <textarea id="textarea<?php echo $r['id_pengaduan']; ?>" name="tanggapan" class="materialize-textarea"></textarea>
   							 </div>
  							<div class="input-field">
        				<input type="submit" name="tanggapi" value="Kirim" class="btn right">
   							 </div>
						</form>

                <?php } ?>
            </div>
        </div>
        <div class="modal-footer col s12">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
    </div>

    <?php 
    // Proses tanggapan
    if (isset($_POST['tanggapi']) && $_POST['tanggapi'] == 'Kirim' && $_POST['id_pengaduan'] == $r['id_pengaduan']) {
        $tgl = date('Y-m-d');
        $tanggapan = $_POST['tanggapan'];
        $id_pengaduan = $r['id_pengaduan'];
        $id_petugas = $_SESSION['data']['id_petugas'];

        // Simpan tanggapan ke database
        $query_insert_tanggapan = mysqli_query($koneksi, "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan, id_petugas) VALUES ('$id_pengaduan', '$tgl', '$tanggapan', '$id_petugas')");
        
        if ($query_insert_tanggapan) {
            // Update status pengaduan menjadi 'selesai'
            $query_update_pengaduan = mysqli_query($koneksi, "UPDATE pengaduan SET status = 'selesai' WHERE id_pengaduan = '$id_pengaduan'");
            
            if ($query_update_pengaduan) {
                echo "<script>alert('Tanggapan Terkirim dan Status Pengaduan Telah Diperbarui');</script>";
                echo "<script>location='index.php?p=pengaduan';</script>";
            } else {
                echo "<script>alert('Gagal memperbarui status pengaduan');</script>";
            }
        } else {
            echo "<script>alert('Gagal mengirim tanggapan');</script>";
        }
    }
    ?>

<?php } ?>
