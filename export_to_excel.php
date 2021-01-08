<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Rekapitulasi-Penilaian-Usulan-Penelitian.xls");
?>

<div class="container-fluid">
  <h1>Rekapitulasi Penilaian Usulan Peneliian oleh Reviewer</h1>      
  <p>Rekapitulasi Penilaian Usulan Peneliian oleh Reviewer </p>

   <table class="table table-striped table-responsive" border="1" >
              <thead>
                <tr>
                 <th>Kode SKIM</th>
                  <th>Tahun Usul</th>
                  <th>No Proposal</th>
                  <th >Judul Kegiatan</th>
                  <th>Nama Reviewer 1</th>
                  <th>Nilai Reviewer 1</th>
                  <th>Rekomendasi Reviewer 1</th>
                  <th>Nama Reviewer 2</th>
                  <th>Nilai Reviewer 2</th>
                  <th>Rekomendasi Reviewer 2</th>
                  <th>Nilai Rata-rata</th>
                  <th>Biaya Rata-rata</th>
                  <th>Rekomendasi Final</th>
                </tr>
              </thead>
               <tbody>
                <?php
                include 'config.php';
                error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                $sql  = $conn->query("SELECT *, YEAR(`tgl_usulan`) as tahun_usulan FROM `tb_usulan_penelitian` WHERE reviewer_satu != 0 AND reviewer_dua != 0");
                while($data = mysqli_fetch_assoc($sql)){
                  $skema = $data['skema'];
                  $tanggal_usulan = $data['tahun_usulan'];
                  $judul = $data['judul'];
                  $id_usulan = $data['id_usulan'];
                  $id_user_reviwer_1 = $data['reviewer_satu'];
                  $id_user_reviwer_2 = $data['reviewer_dua'];

                  if($skema == 'Penelitian Muda'){
                    $no_proposal = 'PM-'.$tanggal_usulan.'-'.$id_usulan.'';
                    $kode_skim = 'PM';
                  }
                  if($skema == 'Penelitian Terapan'){
                    $no_proposal = 'PT-'.$tanggal_usulan.'-'.$id_usulan.'';
                    $kode_skim = 'PT';
                  }
                  if($skema == 'Penelitian Kerjasama'){
                    $no_proposal = 'PK-'.$tanggal_usulan.'-'.$id_usulan.'';
                    $kode_skim = 'PK';
                  }
                  if($skema == 'Penelitian Penugasan'){
                    $no_proposal = 'PP-'.$tanggal_usulan.'-'.$id_usulan.'';
                    $kode_skim = 'PP';
                  }

                  $sql3  = $conn->query("SELECT *, SUM(nilai_1+nilai_2+nilai_3+nilai_4+nilai_5) as total_nilai FROM `tb_penilaian_usulan_penelitian` WHERE id_usulan = '".$id_usulan."' AND id_user_reviewer = '".$id_user_reviwer_1."';");            
                  $data3 = mysqli_fetch_assoc($sql3);

                  $komentar_satu = $data3['komentar'];
                  $rekomendasi_satu = $data3['rekomendasi'];
                  $biaya_disetujui_satu = $data3['biaya_disetujui'];
                  $nilai_satu = $data3['total_nilai'];

                  $sql4  = $conn->query("SELECT *, SUM(nilai_1+nilai_2+nilai_3+nilai_4+nilai_5) as total_nilai FROM `tb_penilaian_usulan_penelitian` WHERE id_usulan = '".$id_usulan."' AND id_user_reviewer = '".$id_user_reviwer_2."';");                  
                  $data4 = mysqli_fetch_assoc($sql4);
                  $komentar_dua = $data4['komentar'];
                  $rekomendasi_dua = $data4['rekomendasi'];
                  $biaya_disetujui_dua = $data4['biaya_disetujui'];
                  $nilai_dua = $data4['total_nilai'];

                  $biaya_rata2 = ($biaya_disetujui_satu + $biaya_disetujui_dua) / 2;               
                  $nilai_rata2 = ($nilai_satu + $nilai_dua) / 2;

                  $sql5 = $conn->query("SELECT * FROM tb_identitas_peneliti WHERE id_user = $id_user_reviwer_1; ");
                  $data5 = mysqli_fetch_assoc($sql5);
                  $nama_satu = $data5['nama'];

                  $sql6 = $conn->query("SELECT * FROM tb_identitas_peneliti WHERE id_user = $id_user_reviwer_2; ");
                  $data6 = mysqli_fetch_assoc($sql6);
                  $nama_dua = $data6['nama'];                
                  
                  echo '<tr>';
                  echo '<td >'.$kode_skim.'</td>';
                  echo '<td >'.$tanggal_usulan.'</td>';
                  echo '<td >'.$no_proposal.'</td>';
                  echo '<td >'.$judul.'</td>';
                  echo '<td >'.$nama_satu.'</td>';
                  echo '<td >'.$nilai_satu.'</td>';
                  echo '<td >'.$rekomendasi_satu.'</td>';
                  echo '<td >'.$nama_dua.'</td>';
                  echo '<td >'.$nilai_dua.'</td>';
                  echo '<td >'.$rekomendasi_dua.'</td>';
                  echo '<td >'.$nilai_rata2.'</td>';
                  echo '<td >Rp. '.number_format($biaya_rata2,2,',','.').'</td>'; 
                  echo '<td></td>';
                  echo '</tr>';
                      }
              ?>  
               </tbody>
              </table>    
</div>