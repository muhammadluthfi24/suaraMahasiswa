<!DOCTYPE html>
<html>
<head>
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
</head>
<body>
    <div class="content">
        <!-- Your existing HTML content -->

        <div class="row">
            <div class="col s4">
                <div class="card red">
                    <div class="card-content white-text">
                        <?php 
                            $query = mysqli_query($koneksi,"SELECT * FROM pengaduan WHERE status='proses'");
                            $jlmmember = mysqli_num_rows($query);
                            if($jlmmember<1){
                                $jlmmember=0;
                            }
                        ?>
                        <span class="card-title">Laporan Status Proses<b class="right"><?php echo $jlmmember; ?></b></span>
                        <p></p>
                    </div>
                </div>
            </div>  

            <div class="col s4">
                <div class="card teal">
                    <div class="card-content white-text">
                        <?php 
                            $query = mysqli_query($koneksi,"SELECT * FROM tanggapan WHERE id_petugas='".$_SESSION['data']['id_petugas']."'");
                            $jlmmember = mysqli_num_rows($query);
                            if($jlmmember<1){
                                $jlmmember=0;
                            }
                        ?>
                        <span class="card-title">Laporan Ditanggapi <b class="right"><?php echo $jlmmember; ?></b></span>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer text -->
    <div class="footer">
        <i><?php include "../layout/footer.html" ?></i>
    </div>
</body>
</html>
