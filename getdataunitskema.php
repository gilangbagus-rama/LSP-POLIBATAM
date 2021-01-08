<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Unit Skema Sertifikasi</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>


<body>

    <div class="container">
        
        <?php
        header("Access-Control-Allow-Origin: *");
        
        $q = $_GET['q'];
        date_default_timezone_set('Asia/Jakarta'); // CDT

        $current_date = date('l');

        //$con = mysqli_connect('localhost','root','','jadwal_if');
        $con = mysqli_connect('localhost','root','','lsp');
        
        if (!$con) {
            die('Could not connect: ' . mysqli_error($con));
            exit;
        }

        if ( $q == "0" ) {
            echo '
            <input type="text" readonly class="form-control" value="Silahkan pilih Skema Sertifikasi terlebih dahulu">';
            echo '<br/>';
            exit;
        }

        // mysqli_select_db($con,"ajax_demo");
        //$sql="SELECT * FROM jadwal_pbm WHERE kelas LIKE '%".$q."%' and hari_ngajar = '".$current_date."' ORDER BY jam_ngajar ASC";

        $sql="SELECT * FROM `unit_skema` WHERE `id_skema` = '$q' 
            ORDER BY `judul_unit` DESC ";
        $result = mysqli_query($con,$sql); ?>



        <table class="table table-hover table-sm table-bordered table-striped">

            <thead>
                <tr>

                    <th scope='col' width="3%">No</th>
                    <th scope='col' width="5%">Kode Unit</th>
                    <th scope='col' >Judul Unit</th>
                    <th scope='col' width="5%">Jenis Standar</th>

                </tr>
            </thead>


            <tbody>


                <?php $no = 1;
                while($row = mysqli_fetch_array($result)) {  ?>


                    <tr scope='row' >
                        <td> <?= $no ?> </td>
                        <td> <?= $row['kode_unit'] ?> </td>
                        <td width="20%"> <?= $row['judul_unit'] ?> </td>
                        <td> <?= $row['jenis_standar'] ?> </td>
                    </tr>


                <?php 
                $no = $no + 1;
                } 
                ?>


            </tbody>


        </table>


        <?php
        mysqli_close($con);
        ?>


    </div>

    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>


</html>

<?php
    // $sql2="SELECT * FROM jadwal_pbm WHERE ruangan LIKE '$q' ORDER BY FIELD (`hari_ngajar`, 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY')";
        // $result2 = mysqli_query($con,$sql2);
        // echo '
        // <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Tampilkan Jadwal Perminggu</button>
        //   <div id="demo" class="collapse">';
        // echo '<table class="table">
        // <thead>
        // <tr>
        // <th>Hari</th>
        // <th>Jam</th>
        // <th>Mata Kuliah</th>
        // <th>Dosen</th>
        // <th>Room</th>
        // </tr>
        // </thead>
        // <tbody>';
        // while($row2 = mysqli_fetch_array($result2)) {
        //     echo "<tr>";
        //     echo "<td>" . $row2['hari_ngajar'] . "</td>";
        //     echo "<td>" . $row2['jam_ngajar'] . "</td>";
        //     echo "<td>" . $row2['makul_diajar'] . "</td>";
        //     echo "<td>" . $row2['nama_dosen'] . "</td>";
        //     echo "<td>" . $row2['ruangan'] . "</td>";
        //     echo "</tr>";
        // }
        // echo "</tbody></table>";
        // echo '</div>';

        // echo "<strong>Tempat Bertambat: </strong><strong>Dari </strong>".$row['tempat_tambat_dari']."<strong> Ke </strong>".$row['tempat_tambat_ke']."<br>";
        // echo "<strong>Pandu Naik Tanggal & Jam: </strong>".$row['tgljam_pandu_naik']."<br>";
        // echo "<strong>Pengg. kpl Tunda Tanggal & Jam: </strong>".$row['tgljam_kapal_tunda']."";

?>