<div class="row">
    <div class="col s12 m9">
        <h3 class="orange-text">Laporan</h3>
    </div>
    <div class="col s12 m3">
        <div class="section"></div>
        <a class="waves-effect waves-light btn blue" href="cetak.php"><i class="material-icons">print</i></a>
    </div>
</div>

<table id="example" class="display responsive-table" style="width:100%">
    <thead>
        <tr>
            <th>No</th>
            <th>NIM Pelapor</th>
            <th>Nama Pelapor</th>
            <th>Nama Petugas</th>
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
    
    while ($r = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $r['nim']; ?></td>
            <td><?php echo $r['nama_pelapor']; ?></td>
            <td><?php echo $r['nama_petugas'] ?? 'Belum ditanggapi'; ?></td>
            <td><?php echo $r['tgl_pengaduan']; ?></td>
            <td><?php echo $r['tgl_tanggapan'] ?? 'Belum ditanggapi'; ?></td>
            <td><?php echo $r['status']; ?></td>
            <td><a class="btn blue modal-trigger" href="#laporan?id_tanggapan=<?php echo $r['id_tanggapan']; ?>">More</a></td>
        </tr>

        <!-- Modal Structure -->
        <div id="laporan?id_tanggapan=<?php echo $r['id_tanggapan']; ?>" class="modal">
            <div class="modal-content">
                <h4 class="orange-text">Detail</h4>
                <div class="col s12">
                    <p>NIM : <?php echo $r['nim']; ?></p>
                    <p>Dari : <?php echo $r['nama_pelapor']; ?></p>
                    <p>Petugas : <?php echo $r['nama_petugas'] ?? 'Belum ditanggapi'; ?></p>
                    <p>Tanggal Masuk : <?php echo $r['tgl_pengaduan']; ?></p>
                    <p>Tanggal Ditanggapi : <?php echo $r['tgl_tanggapan'] ?? 'Belum ditanggapi'; ?></p>
                    <?php if ($r['foto'] == "kosong") { ?>
                        <img src="../img/noImage.png" width="100">
                    <?php } else { ?>
                        <img width="100" src="../img/<?php echo $r['foto']; ?>">
                    <?php } ?>
                    <br><b>Pesan</b>
                    <p><?php echo $r['isi_laporan']; ?></p>
                    <br><b>Respon</b>
                    <p><?php echo $r['tanggapan'] ?? 'Belum ditanggapi'; ?></p>
                </div>
            </div>
            <div class="modal-footer col s12">
                <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
            </div>
        </div>

    <?php } ?>

    </tbody>
</table>
