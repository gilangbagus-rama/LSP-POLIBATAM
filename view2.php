<?php
 $id_usulan = $_GET['id_usulan'];

include 'config.php';
          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
          $sql7  = $conn->query("SELECT * FROM tb_file_subtansi_usulan_pengabdian WHERE id_usulan = '$id_usulan'");
          $data7 = mysqli_fetch_assoc($sql7);
          $file1 = $data7['file_substansi'];
          $file2 = $data7['lampiran_1'];
          $file3 = $data7['lampiran_2'];

include 'PDFMerger.php';
$pdf = new PDFMerger;

$pdf->addPDF($file1, 'all')
	->addPDF($file2, 'all')
	->addPDF($file3, 'all')
	->merge('output', 'samplepdfs/TEST2.pdf');
	
	//REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
	//You do not need to give a file path for browser, string, or download - just the name.
?>