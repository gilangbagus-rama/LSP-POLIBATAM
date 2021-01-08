<?php
    
// Include Limbrary
require('fpdf.php');
include 'config.php';

class PDF_MC_Table extends FPDF {
    var $widths;
    var $aligns;

    function SetWidths($w) {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a) {
        //Set the array of column alignments
        $this->aligns=$a;
    }

    function Row($data) {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h);
            //Print the text
            $this->MultiCell($w,5,$data[$i],0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt) {
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }


    // Page Header
    function Header(){
        // if ($this->page == 1) { // Khusus Hal 1


        //     // Arial bold 15
        // $this->SetY(5);
        // $this->SetFont('Arial','B',15);
        // // Move to the right
        // // Title
        // $this->Cell(50,10, $this->Image('./fpdf/BNSP.jpg', $this->GetX(), $this->GetY(), 20) , 0, 1,'L');
        // // Line break
        // }

        $logo = './fpdf/BNSP.jpg' ;
        // Arial bold 15
        $this->SetY(5);
        $this->SetFont('Arial','B',15);
        // Move to the right
        // Title
        $this->Cell(50,11, $this->Image($logo, $this->GetX(), $this->GetY(), 20) , 0, 1,'L');
        // Line break
        
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-20);
        // Arial italic 8
        $this->SetFont('Arial','',7);
        
        // Page number
        $this->setFillColor(0, 0, 0);
        $this->Cell(60,5, 'KOMISI SERTIFIKASI BNSP-IA/2015' , 'T' , 0 , 'L' , );
        $this->Cell(30,5, '' , 'T' , 0 , 'C' , );
        $this->Cell(60,5, 'FORM APL-01-Rev.02' , 'T' , 0 , 'R' , );
        $this->setFillColor(0, 128, 0); 
        $this->SetTextColor(255, 255, 255); $this->SetFont('Arial','B',8);
        $this->Cell(20 , 10 , $this->PageNo() , 0 , 0 , 'C' , 1);
        // $this->Cell(20,10,'Page '.$this->PageNo().'/{nb}',1,0,'L');
    }


    function WordWrap(&$text, $maxwidth) {
        $text = trim($text);
        if ($text==='')
            return 0;
        $space = $this->GetStringWidth(' ');
        $lines = explode("\n", $text);
        $text = '';
        $count = 0;

        foreach ($lines as $line){
            $words = preg_split('/ +/', $line);
            $width = 0;

            foreach ($words as $word)
            {
                $wordwidth = $this->GetStringWidth($word);
                if ($wordwidth > $maxwidth)
                {
                    // Word is too long, we cut it
                    for($i=0; $i<strlen($word); $i++)
                    {
                        $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                        if($width + $wordwidth <= $maxwidth)
                        {
                            $width += $wordwidth;
                            $text .= substr($word, $i, 1);
                        }
                        else
                        {
                            $width = $wordwidth;
                            $text = rtrim($text)."\n".substr($word, $i, 1);
                            $count++;
                        }
                    }
                }
                elseif($width + $wordwidth <= $maxwidth)
                {
                    $width += $wordwidth + $space;
                    $text .= $word.' ';
                }
                else
                {
                    $width = $wordwidth + $space;
                    $text = rtrim($text)."\n".$word.' ';
                    $count++;
                }
            }
            $text = rtrim($text)."\n";
            $count++;
        }
        $text = rtrim($text);
        return $count;
    }


    
    function Circle($x, $y, $r, $style='D') {
        $this->Ellipse($x,$y,$r,$r,$style);
    }

    function Ellipse($x, $y, $rx, $ry, $style='D')
    {
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $lx=4/3*(M_SQRT2-1)*$rx;
        $ly=4/3*(M_SQRT2-1)*$ry;
        $k=$this->k;
        $h=$this->h;
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$rx)*$k,($h-$y)*$k,
            ($x+$rx)*$k,($h-($y-$ly))*$k,
            ($x+$lx)*$k,($h-($y-$ry))*$k,
            $x*$k,($h-($y-$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$lx)*$k,($h-($y-$ry))*$k,
            ($x-$rx)*$k,($h-($y-$ly))*$k,
            ($x-$rx)*$k,($h-$y)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$rx)*$k,($h-($y+$ly))*$k,
            ($x-$lx)*$k,($h-($y+$ry))*$k,
            $x*$k,($h-($y+$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x+$lx)*$k,($h-($y+$ry))*$k,
            ($x+$rx)*$k,($h-($y+$ly))*$k,
            ($x+$rx)*$k,($h-$y)*$k,
            $op));
    }

}

// Mintak ID Permohonan

// $id_permohonan = 'P0002'; // Debug



// Data Dari Asesi
    $id_permohonan = $id_permohonan;

    $id_asesi = $conn->query("SELECT * FROM `permohonan` WHERE `id_permohonan` = '$id_permohonan'");
    $data       = mysqli_fetch_assoc($id_asesi); 
    $id_asesi = $data['id_asesi'];
    $id_skema = $data['id_skema'];
    $tujuan_asesmen = $data['tujuan_asesmen'];

    $timestamp_permohonan = $data['timestamp_permohonan'];
    $timestamp_permohonan2 = new DateTime( $data['timestamp_permohonan'] );
    $tgl_permohonan = $timestamp_permohonan2->format('d - m - Y');
    $jam_permohonan = $timestamp_permohonan2->format('H - i - s');

    $email_asesi = $conn->query("SELECT `email` FROM `asesi` WHERE `id_asesi` = '$id_asesi'");
    $data       = mysqli_fetch_assoc($email_asesi); 
    $email_asesi = $data['email'];


    $sql_akun_asesi = $conn->query("SELECT `nama` , `no_hp` FROM `akun` WHERE `email` = '$email_asesi'");
    $data       = mysqli_fetch_assoc($sql_akun_asesi); 
    $nama_asesi = $data['nama']; $no_hp = $data['no_hp'];


    $sql_asesi = $conn->query("SELECT * FROM `asesi` WHERE `id_asesi` = '$id_asesi'");
    $data       = mysqli_fetch_assoc($sql_asesi);


    // Data Pribadi
    $no_nik = $data['no_nik']; $tmpt_lahir = $data['tmpt_lahir']; 

    $tgl = new DateTime( $data['tgl_lahir'] );
    $tgl_lahir = $tgl->format('d-m-Y');


    $jenkel = $data['jenkel'];

    if ( $jenkel == 'lk' ) {
        $pr = 1; $lk = 0;
    }

    if ( $jenkel == 'pr' ) {
        $lk = 1; $pr = 0;
    }


    $kebangsaan = $data['kebangsaan']; $alamat_rmh = $data['alamat_rmh'];
    $kodepos = $data['kodepos']; $notelp_rmh = $data['notelp_rmh']; $pendidikan = $data['pendidikan'];
    $telppribadi_perusahaan = $data['telppribadi_perusahaan'];

    // Data Perusahaan
    $nama_perusahaan = $data['nama_perusahaan']; $jabatan = $data['jabatan']; $email_perusahaan = $data['email_perusahaan'];
    $telp_perusahaan = $data['telp_perusahaan']; $fax_perusahaan = $data['fax_perusahaan']; $alamat_perusahaan = $data['alamat_perusahaan'];
    $kodepos_perusahaan = $data['kodepos_perusahaan'];

// End



    // Membuat objek $pdf

    // $pdf = new FPDF('P','mm','A4');
    // // ("Orientasi Kertas" , "Satuan Pengukuran (mm / inc)" , "Ukuran Kertas")

    // // $pdf->SetMargins(float 2, float 1,27 [,float 2,24]);
    // $pdf->SetMargins(5, 5);

    // $pdf->SetMargins(2, 1.27 , 2 , 2.24);



    $pdf = new PDF_MC_Table('P','mm','A4');
    $pdf->SetTopMargin(20,1); // l t r
    $pdf->SetRightMargin(17,8); // l t r
    $pdf->SetLeftMargin(24,9); // l t r


    


    // Deklarasi Tambah Halaman
    $pdf->AddPage();

    // Page Number
    $pdf->AliasNbPages();


    $border = 0; // Debug, border = 1 , no debug = 0


    /* Buat Cell **/
        // Set Ukuran Font
        $pdf->Ln(3);
        $pdf->SetFont('Arial','B',13);         


        // Membuat Cel dengan ukuran 40 x 10
        $pdf->Cell(170,5,'FR.APL.01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI' , $border , 1 , 'L');

        $pdf->Ln(3);

        // Max Lebar Cell = 190

        // Cell( "Lebar" , "Tinggi" "Isi Teks" , border (O = N , 1 = Y) , Baris Baru ( 0 = Stack new cell , 1 = Baris Baru new cell ) , "Text Allign");


    $pdf->SetFont('Arial', 'B' ,11);

    // Bagian 1
    $pdf->Cell(170,5,'Bagian 1 : Rincian Data Pemohon Sertifikasi' , $border , 1 , 'L');

    $pdf->SetFont('Arial', '' ,10);
    $pdf->Cell(170,5,'Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini.' , $border , 1 , 'L');


    $pdf->Ln(5); // Line Break
    $pdf->SetFont('Arial', 'B' ,10);


    // Bagian Data Pribadi 
    $pdf->Cell(5,5,'a.' , $border , 0 , 'C');

    $pdf->Cell(165,5,'Data Pribadi' , $border , 1 , 'L');


    $pdf->SetFont('Arial', '' ,9);


    // Nama
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Nama' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120,5, $nama_asesi , $border , 1 , 'L'); // Nama Asesi


    // No KTP
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'No. KTP/ NIK/PASSPORT' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120,5, $no_nik , $border , 1 , 'L'); // No NIK


    // TTL
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Tempat/ Tanggal Lahir' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120,5, $tmpt_lahir. '/' .$tgl_lahir , $border , 1 , 'L');



    // Jenkel
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Jenis Kelamin' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');


    $pdf->Cell(20,5,'Laki - Laki' , $border , 0 , 'L');

    $pdf->Cell(5,5,'/' , $border , 0 , 'L');

    $pdf->Cell(20,5,'Perempuan' , $border , 0 , 'L');

    $pdf->Cell(75,5,'*' , $border , 0 , 'L');

    $pdf->Cell(1,2.5,'' , $border , 1 , 'L');

    // Jenkel
    $pdf->Cell(5,2.5,'' , 0 , 0 , 'L');

    $pdf->Cell(40,2.5,'' , 0 , 0 , 'L');

    $pdf->Cell(5,2.5,'' , 0 , 0 , 'L');



    $pdf->Cell(20,0, '' , $lk , 0 , 'L'); // Kalu PR Coret Disini

    $pdf->Cell(5,0,'' , 0 , 0 , 'L');

    $pdf->Cell(20,0,'' , $pr , 0 , 'L'); // Kalau LK Coret Disini

    $pdf->Cell(75,2.5,'' , 0 , 1 , 'L');



    // Kebangsaan
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Kebangsaan' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120,5, $kebangsaan , $border , 1 , 'L');



    // Alamat - Jalan
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Alamat Rumah' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120,5, $alamat_rmh , $border , 1 , 'L');

    // $panjang_kalimat = strlen($alamat);

    // function subwords( $str, $max = '100' , $char = ' ', $end = '' ) {
    //     $str = trim( $str ) ;
    //     $str = $str . $char ;
    //     $len = strlen( $str ) ;
    //     $words = '' ;
    //     $w = '' ;
    //     $c = 0 ;
    //     for ( $i = 0; $i < $len; $i++ )
    //         if ( $str[$i] != $char )
    //             $w = $w . $str[$i] ;
    //         else
    //             if ( ( $w != $char ) and ( $w != '' ) ) {
    //                 $words .= $w . $char ;
    //                 $c++ ;
    //                 if ( $c >= $max ) {
    //                     break ;
    //                 }
    //                 $w = '' ;
    //             }
    //     if ( $i+1 >= $len ) {
    //         $end = '' ;
    //     }
    //     return trim( $words ) . $end ;
    // }

    // function cutText($text, $length, $mode = 2)
    // {
    //     if ($mode != 1)
    //     {
    //         $char = $text{$length - 1};
    //         switch($mode)
    //         {
    //             case 2: 
    //                 while($char != ' ') {
    //                     $char = $text{--$length};
    //                 }
    //             case 3:
    //                 while($char != ' ') {
    //                     $char = $text{++$num_char};
    //                 }
    //         }
    //     }
    //     return substr($text, 0, $length);
    // }

    // $text = 'Contoh script yang digunakan untuk memotong 50 huruf pertama dari teks, kalimat atau paragraf dengan php';
    // $alamat1 = cutText($alamat, 16, 1);


    // // $alamat1 = subwords($alamat, 15);

    // $alamat2 = subwords($alamat, 16);

    // Kalau alamat nya kepanjangan , buat baris baru $alamat_rmh

    // Alamat - Jalan
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'' , $border , 0 , 'L');

    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(120,5, '' , $border , 1 , 'L');



    // Alamat - Kode Pos
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'' , $border , 0 , 'L');

    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(20,5,'Kode Pos' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(95,5, $kodepos , $border , 1 , 'L');


    // Phone / E - Mail

    // 50
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'No Telepon /E-mail' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    // 110
    $pdf->Cell(20,5, 'Rumah' , $border , 0 , 'L');

    $pdf->Cell(5,5, ': ' , $border , 0 , 'L');

    $pdf->Cell(30,5, $notelp_rmh  , $border , 0 , 'L'); // No Rumah Pribadi $notelp_rmh

    $pdf->Cell(15,5, 'Kantor' , $border , 0 , 'L');

    $pdf->Cell(5,5, ': ' , $border , 0 , 'L');

    $pdf->Cell(45,5, $telppribadi_perusahaan , $border , 1 , 'L'); // No Kantor Pribadi 


    // 50
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'' , $border , 0 , 'L');

    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    // 110
    $pdf->Cell(20,5, 'HP' , $border , 0 , 'L');

    $pdf->Cell(5,5, ': ' , $border , 0 , 'L');

    $pdf->Cell(95,5, '0851 5686 1862' , $border , 1 , 'L');
    
    // No HP Pribadi $no_hp

    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'' , $border , 0 , 'L');

    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(20,5, 'E-Mail ' , $border , 0 , 'L');

    $pdf->Cell(5,5, ': ' , $border , 0 , 'L');

    $pdf->SetFont('Arial', '' ,9);

    $pdf->Cell(95,5, $email_asesi , $border , 1 , 'L');




    $pdf->Ln(5); // Line Break
    $pdf->SetFont('Arial', 'B' ,10);


    // Bagian Data Pekerjaan Sekarang 
    $pdf->Cell(5,5,'b.' , $border , 0 , 'C');

    $pdf->Cell(165,5,'Data Pekerjaan Sekarang' , $border , 1 , 'L');


    $pdf->SetFont('Arial', '' ,9);

    // Perusahaan/Lembaga
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Perusahaan/Lembaga' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120,5, $nama_perusahaan , $border , 1 , 'L'); // Nama Perusahaan


    // Jabatan
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Jabatan' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120,5, $jabatan , $border , 1 , 'L'); // Jabatan


    // Alamat Kantor - Jalan
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Alamat Kantor' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120,5, $alamat_perusahaan , $border , 1 , 'L'); // Alamat Kantor

    // Kalau alamat nya kepanjangan , buat baris baru

    // Alamat - Jalan
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'' , $border , 0 , 'L');

    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(120,5,'' , $border , 1 , 'L');



    // Alamat - Kode Pos
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'' , $border , 0 , 'L');

    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(20,5,'Kode Pos' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(95,5, $kodepos_perusahaan , $border , 1 , 'L');


    // Phone / E - Mail

    // 50
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'No. Telp/Fax/E-mail' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    // 110
    $pdf->Cell(20,5, 'Telp' , $border , 0 , 'L');

    $pdf->Cell(5,5, ': ' , $border , 0 , 'L');

    $pdf->Cell(30,5, $telp_perusahaan , $border , 0 , 'L'); // No Telp Perusahaan

    $pdf->Cell(15,5, 'Fax' , $border , 0 , 'L');

    $pdf->Cell(5,5, ': ' , $border , 0 , 'L');

    $pdf->Cell(45,5, $fax_perusahaan , $border , 1 , 'L'); // Fax Perusahaan


    // 50
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'' , $border , 0 , 'L');

    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    // 110
    $pdf->Cell(20,5, 'E-mail' , $border , 0 , 'L');

    $pdf->Cell(5,5, ': ' , $border , 0 , 'L');

    $pdf->Cell(95,5, $email_perusahaan , $border , 1 , 'L'); // E Mail Perusahaan


    $data_skema = $conn->query("SELECT * FROM `skema_sertifikasi` WHERE `id_skema` = '$id_skema'");
    $data       = mysqli_fetch_assoc($data_skema);

    $nama_skema = $data['nama_skema'];
    $nomor_skema = $data['nomor_skema'];
    $id_skema = $data['id_skema'];


    $pdf->Ln(5); // Line Break
    $pdf->SetFont('Arial', 'B' ,11);

    // Bagian 1
    $pdf->Cell(170,5,'Bagian 2 : Data Sertifikasi' , $border , 1 , 'L');

    $pdf->SetFont('Arial', '' ,10);

    $pdf->Cell(170,5, 'Tuliskan Judul dan Nomor Skema Sertifikasi, Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi' , $border , 1 , 'L');

    $pdf->Cell(170,5, 'yang Anda ajukan untuk mendapatkan pengakuan sesuai dengan latarbelakang pendidikan, pelatihan serta' , $border , 1 , 'L');
    $pdf->Cell(170,5, 'pengalaman kerja yang Anda miliki.' , $border , 1 , 'L');


    $pdf->Ln(5); // Line Break
    $pdf->SetFont('Arial', '' ,9);


    // Nama Skema
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Judul Skema' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120, 5, $nama_skema , $border , 1 , 'L');


    // Nomor Skema
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Nomor Skema' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120,5, $nomor_skema , $border , 1 , 'L');


    // Tujuan Asesmen
    $pdf->Cell(5,5,'' , $border , 0 , 'L');

    $pdf->Cell(40,5,'Tujuan Asesmen' , $border , 0 , 'L');

    $pdf->Cell(5,5,':' , $border , 0 , 'L');

    $pdf->Cell(120, 5, $tujuan_asesmen , $border , 1 , 'L');




    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',9);
    
    // Tabel Unit Skema
    // Header
    $pdf->setFillColor(251, 212, 180); 
    $pdf->Cell(10,12,'No.' , 1 , 0 , 'C' , 1);

    $pdf->Cell(30,12,'Kode Unit' , 1 , 0 , 'C' , 1);

    $pdf->Cell(100,12,'Judul Unit' , 1 , 0 , 'C' , 1);

    $pdf->Cell(30,12,'Jenis Standar' , 1 , 1 , 'C' , 1);


    $pdf->SetFont('Arial','',8);


    // Query panggil data unit_skema
    $data = $conn->query("SELECT * FROM `unit_skema` WHERE `id_skema` = '$id_skema'");
    
    $no = 1;
    while ( $data_unit = mysqli_fetch_assoc($data) ) {

        $judul_unit = $pdf->WordWrap($data_unit['judul_unit'], 50);

    $pdf->Cell(10, 8, $no. '.', 1 , 0 , 'C' , 0);

    $pdf->Cell(30,8, $data_unit['kode_unit'] , 1 , 0 , 'C' , 0);

    $pdf->Cell(100,8, $data_unit['judul_unit']  , 1 , 0 , 'L' , 0);

    $pdf->Cell(30,8, $data_unit['jenis_standar']  , 1 , 1 , 'C' , 0);

    $no = $no + 1 ;
    }



    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B' ,11);

    // Bagian 1
    $pdf->Cell(170,5,'Bagian 3 : Bukti Kelengkapan Pemohon' , $border , 1 , 'L');

    $pdf->Ln(5); // Line Break
    $pdf->SetFont('Arial', 'B' ,10);
    
    // Bagian Data Pribadi 
    $pdf->Cell(5,5,'a.' , $border , 0 , 'C');

    $pdf->Cell(165,5,'Bukti kelengkapan persyaratan dasar pemohon :' , $border , 1 , 'L');


    // Tabel Unit Skema
    // Header
    $pdf->Ln(5); // Line Break
    $pdf->SetFont('Arial', 'B' ,9);

    $pdf->setFillColor(251, 212, 180); 
    $pdf->Cell(10,20, 'No.' , 'L,T,R' , 0 , 'C'  , 1 );
    $pdf->Cell(100,20, 'Persyaratan Dasar' , 'L,T,R' , 0 , 'C'  , 1 );
    $pdf->Cell(40,5, 'Ada' , 'L,T,R' , 0 , 'C'  , 1 );
    $pdf->Cell(20,5, '' , 'L,T,R' , 1 , 'L'  , 1 );

    $pdf->Cell(10,5, '' , 'L,R' , 0 , 'C'  , 0 );
    $pdf->Cell(100,5, '' , 'L,R' , 0 , 'C'  , 0 );
    $pdf->Cell(20,5, 'Memenuhi' , 'L,T,R' , 0 , 'C'  , 1 );
    $pdf->Cell(20,5, 'Tidak' , 'L,T,R' , 0 , 'C'  , 1 );
    $pdf->Cell(20,5, 'Tidak' , 'L,R' , 1 , 'C'  , 1 );


    $pdf->Cell(10,5, '' , 'L,R' , 0 , 'C'  , 0 );
    $pdf->Cell(100,5, '' , 'L,R' , 0 , 'C'  , 0 );
    $pdf->Cell(20,5, 'Syarat' , 'L,R' , 0 , 'C'  , 1 );
    $pdf->Cell(20,5, 'Memenuhi' , 'L,R' , 0 , 'C'  , 1 );
    $pdf->Cell(20,5, 'Ada' , 'L,R' , 1 , 'C'  , 1 );


    $pdf->Cell(10,5, '' , 'L,B,R' , 0 , 'C'  , 1 );
    $pdf->Cell(100,5, '' , 'L,B,R' , 0 , 'C'  , 1 );
    $pdf->Cell(20,5, '' , 'L,B,R' , 0 , 'C'  , 1 );
    $pdf->Cell(20,5, 'Syarat' , 'L,B,R' , 0 , 'C'  , 1 );
    $pdf->Cell(20,5, '' , 'L,B,R' , 1 , 'C'  , 1 );


    $pdf->SetFont('Arial', '' ,8);

    $sql_syarat = $conn->query("SELECT * FROM `syarat_dasar` WHERE `id_skema` = '$id_skema'");

    while ( $data_syarat = mysqli_fetch_assoc($sql_syarat) ){

        $id_syarat = $data_syarat['id_syarat'];

        $cek_syarat = $conn->query("SELECT `terpenuhi` FROM `syarat_permohonan` WHERE `id_syarat` = '$id_syarat' AND `id_permohonan` = '$id_permohonan'");
        $cek_terpenuhi = mysqli_fetch_assoc($cek_syarat) ['terpenuhi'];

        if ( $cek_terpenuhi = 'Terpenuhi' ) {
            $terpenuhi = 'v'; $tidak_terpenuhi = '';
        }

        if ( $cek_terpenuhi != 'Terpenuhi' ) {
            $terpenuhi = ''; $tidak_terpenuhi = 'v';
        }


        $pdf->Cell(10,8, $data_syarat['no_syarat']. '.' , 1 , 0 , 'C' , 0);
        $pdf->Cell(100,8, $data_syarat['syarat'], 1 , 0 , 'L' , 0);
        $pdf->Cell(20,8, $terpenuhi , 1 , 0 , 'C' , 0);
        $pdf->Cell(20,8, '' , 1 , 0 , 'C' , 0);
        $pdf->Cell(20,8, $tidak_terpenuhi , 1 , 1 , 'C' , 0);



    }

    // $pdf->Ln(10);
    // $pdf->SetFont('Arial', 'B' ,11);



    // $pdf->Cell(10,20,'No.' , 1 , 0 , 'C' , 1);
    // $pdf->Cell(110,20,'Persyaratan Dasar' , 1 , 0 , 'C' , 1); 

    // $pdf->Cell(50,10,'Ada' , 1 , 0 , 'C' , 1);
    // $pdf->Cell(20,20,'Tidak Ada' , 1 , 0 , 'C' , 1);
    // $pdf->Cell(0,0,'' , 0 , 1 , 'C' , 0);

    // $pdf->Cell(10,10,'' , 0 , 0 , 'C' , 0);
    // $pdf->Cell(110,10,'' , 0 , 0 , 'C' , 0);

    // $pdf->Cell(20,10,'Memenuhi' , 1 , 0 , 'C' , 1);
    // $pdf->Cell(30,10,'Tidak Memenuhi' , 1 , 0 , 'C' , 1);
    // $pdf->Cell(20,10,'Tidak Ada' , 1 , 1 , 'C' , 1);   


    // $pdf->setFillColor(251, 212, 180); 
    // $pdf->Cell(10,20,'No.' , 1 , 0 , 'C' , 1);
    // $pdf->Cell(110,20,'Persyaratan Dasar' , 1 , 0 , 'C' , 1); 
    // $x = $pdf->GetX();
    // $pdf->Cell(50,10,'Ada' , 1 , 0 , 'C' , 1);
    // $pdf->Cell(20,10,'Tidak Ada' , 1 , 1 , 'C' , 1);
    // $pdf->SetX($x);
    // $pdf->Cell(20,10,'Memenuhi' , 1 , 0 , 'C' , 1);
    // $pdf->Cell(30,10,'Tidak Memenuhi' , 1 , 0 , 'C' , 1);
    // $pdf->Cell(20,10,'Tidak Ada' , 1 , 1 , 'C' , 1);     

    // $pdf->SetFont('Arial', '' ,11);

    // $pdf->Cell(10,20,'1.' , 1 , 0 , 'C' , 0);
    // $pdf->Cell(110,20,'Ini syarat Dasar' , 1 , 0 , 'C' , 0);
    // $pdf->Cell(20,20,'Judul Unit' , 1 , 0 , 'C' , 0);
    // $pdf->Cell(30,20,'Judul Unit' , 1 , 0 , 'C' , 0);
    // $pdf->Cell(20,20,'Jenis Standar' , 1 , 1 , 'C' , 0);





    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B' ,10);
    // Bagian Data Pribadi 
    $pdf->Cell(5,5,'b.' , $border , 0 , 'C');

    $pdf->Cell(165,5,'Bukti kompetensi yang relevan :' , $border , 1 , 'L');

    $pdf->Ln(5); // Line Break
    $pdf->SetFont('Arial', 'B' ,9);

    // Tabel Unit Skema
    // Header
    $pdf->setFillColor(198, 217, 241); 
    $pdf->Cell(10,16,'No.' , 1 , 0 , 'C' , 1);

    $pdf->Cell(120,16,'Rincian Bukti Pendidikan/Pelatihan, PengalamanKerja, Pengalaman Hidup' , 1 , 0 , 'C' , 1); 
    $x = $pdf->GetX();
    $pdf->Cell(40,8,'Lampiran Bukti*' , 1 , 1 , 'C' , 1);
    $pdf->SetX($x);
    $pdf->Cell(20,8,'Ada' , 1 , 0 , 'C' , 1);
    $pdf->Cell(20,8,'Tidak Ada' , 1 , 1 , 'C' , 1);


    $pdf->SetFont('Arial', '' ,8);
    $no = 1;
    $sql_portofolio = $conn->query("SELECT * FROM `portofolio` WHERE `id_permohonan` = '$id_permohonan'");

    while ( $portofolio = mysqli_fetch_assoc($sql_portofolio) ){

        if ( $portofolio['terpenuhi'] == '-' || $portofolio['terpenuhi'] == 'Benar dan Valid' ) {
            $porto_penuh = 'v' ; $porto_notpenuh = ''; 
        }
        else {
            $porto_penuh = '' ; $porto_notpenuh = 'v'; 
        }

        $pdf->Cell(10,8, $no. '.' , 1 , 0 , 'C' , 0);
        $pdf->Cell(120,8, $portofolio['keterangan'] , 1 , 0 , 'L' , 0);
        $pdf->Cell(20,8, $porto_penuh , 1 , 0 , 'C' , 0);
        $pdf->Cell(20,8, $porto_notpenuh , 1 , 1 , 'C' , 0);

    $no = $no +1;
    }




    // $pdf->AddPage();
    $pdf->Ln(10); // Line Break
    
    $pdf->SetFont('Arial', '' ,10);


    // $pdf->Cell(95,40, ' ' , 1, 0 , '' );$pdf->Cell(95,8, 'Tanda Tangan' , 1, 1  );
    // $y = $pdf->GetX();    
    // $pdf->SetX($y);
    // $pdf->Cell(95,8, 'Tanda Tangan' , 1, 1  );

    $pdf->setFillColor(251, 212, 180); 

    // If rekomendasi == '-';
    $rekomendasi = $conn->query("SELECT `rekomendasi` FROM `permohonan` WHERE `id_permohonan` = '$id_permohonan'");
    $rekomendasi = mysqli_fetch_assoc($rekomendasi) ['rekomendasi'];



    if ( $rekomendasi == '-' ) {


        $pdf->Cell(85,6, 'Rekomendasi (oleh LSP ):' , 'L,T,R' , 0 , 'L'  , 0 );

        $pdf->Cell(85,6, 'Pemohon :' , 1, 1 , 'L' , 1  );

        $pdf->Cell(85,6, 'Berdasarkan persyaratan dasar pemohon, kandidat ' , 'L,R' , 0 , 'L'  , 0 );

        
        $pdf->Cell(35,6, 'Nama' , 1, 0 , 'L' , 1  );
        $pdf->Cell(50,6, $nama_asesi  , 1, 1 , 'L' , 0  ); // Nama pemohon

        $pdf->Cell(85,6, 'dapat:' , 'L,R' , 0 , 'L'  , 0 );

        $pdf->Cell(35,6, 'Tanggal' , 1, 0 , 'L' , 1  );
        $pdf->Cell(50,6, $tgl_permohonan , 1, 1 , 'L' , 0  );

        $pdf->Cell(17,6, 'Diterima' , 'L' , 0 , 'L'  , 0 );
        $pdf->Cell(3,6, '/' , '' , 0 , 'C'  , 0 );
        $pdf->Cell(25,6, 'tidak diterima' , '' , 0 , 'L'  , 0 );
        $pdf->Cell(40,6, 'sebagai asesi.' , 'R' , 0 , 'L'  , 0 );

        $pdf->Cell(35,12, '' , 1, 0 , 'L' , 1  );
        $pdf->Cell(50,12, '' , 1, 0 , 'L' , 0  );


        $pdf->Cell(0,3, '' , 0 , 1 , 'L'  , 0 ); //Hidden

        $pdf->Cell(1,0, '' , 'L' , 0 , 'L'  , 0 );

        $pdf->Cell(15,0, '' , '' , 0 , 'L'  , 0 ); // Kalau Di tolak
        $pdf->Cell(4,0, '' , '' , 0 , 'C'  , 0 );
        $pdf->Cell(23,0, '' , '' , 0 , 'L'  , 0 ); // Kalau Diterima
        $pdf->Cell(42,0, '' , 'R' , 0 , 'L'  , 0 );

        $pdf->Cell(35,0, 'Tanda Tangan' , 0 , 0 , 'L' , 1  );
        $pdf->SetFont('Arial', 'B' ,10);
        $pdf->Cell(50,0, 'SIGNED' , 0, 0 , 'C' , 0  );

        $pdf->SetFont('Arial', '' ,10);

        $pdf->Cell(0,3, '' , 0 , 1 , 'L'  , 0 ); //Hidden

        
        $pdf->Cell(85,6, '' , 'L,R' , 0 , 'L'  , 0 );
        $pdf->Cell(35,6, '' , 'L,R,B' , 0 , 'L' , 1  );
        $pdf->Cell(50,6, $timestamp_permohonan , 'L,R,B' , 1 , 'C'  , 0 );


        $pdf->Cell(85,6, '' , 'L,T,R' , 0 , 'L'  , 0 );
        $pdf->Cell(85,6, 'Administrasi :' , 1, 1 , 'L' , 1  );

        $pdf->Cell(85,6, 'Catatan:' , 'L,R' , 0 , 'L'  , 0 );

        $pdf->Cell(35,6, 'Nama' , 1, 0 , 'L' , 1  );
        $pdf->Cell(50,6, ''  , 1, 1 , 'L' , 0  ); // Nama pemohon




        $pdf->Cell(85,6, '' , 'L,R' , 0 , 'L'  , 0 ); // Catatan 2

        $pdf->Cell(35,6, 'Tanggal' , 1, 0 , 'L' , 1  );
        $pdf->Cell(50,6, ''  , 1, 1 , 'L' , 0  ); // Nama pemohon

        $pdf->Cell(85,6, '', 'L,R' , 0 , 'L'  , 0 ); // Catatan 3

        $pdf->Cell(35,6, 'Tanda Tangan' , 'L,T,R', 0 , 'L' , 1  );

        $pdf->Cell(50,6, ''  , 'L,T,R' , 1 , 'L' , 0  ); // Nama pemohon

        $pdf->Cell(85,6, '' , 'L,R' , 0 , 'L'  , 0 ); // Catatan 4

        $pdf->Cell(35,6, '' , 'L,R', 0 , 'L' , 1  );
        $pdf->Cell(50,6, ''  , 'L,R' , 1 , 'L' , 0  ); // Nama pemohon

        $pdf->Cell(85,6, '' , 'L,R' , 0 , 'L'  , 0 ); // Catatan 5

        $pdf->Cell(35,6, '' , 'L,R', 0 , 'L' , 1  );
        $pdf->Cell(50,6, ''  , 'L,R' , 1 , 'L' , 0  ); // Nama pemohon

        $pdf->Cell(85,6, '' , 'L,R,B' , 0 , 'L'  , 0 ); // Catatan 6

        $pdf->Cell(35,6, '' , 'L,R,B', 0 , 'L' , 1  );

        $pdf->SetFont('Arial', 'B' ,10);
        $pdf->Cell(50,6, ''  , 'L,R,B' , 1 , 'C' , 0  ); // Nama pemohon

        $pdf->SetFont('Arial', '' ,10);

    }

    if ( $rekomendasi != '-' ) {
        if ( $rekomendasi == 'Tidak Diterima' ) {

            $ditolak = 1; $diterima = 0;
        }

        if ( $rekomendasi == 'Diterima' ) {

            $ditolak = 0;  $diterima = 1;
        }


        $pdf->Cell(85,6, 'Rekomendasi (oleh LSP ):' , 'L,T,R' , 0 , 'L'  , 0 );

        $pdf->Cell(85,6, 'Pemohon :' , 1, 1 , 'L' , 1  );

        $pdf->Cell(85,6, 'Berdasarkan persyaratan dasar pemohon, kandidat ' , 'L,R' , 0 , 'L'  , 0 );

        
        $pdf->Cell(35,6, 'Nama' , 1, 0 , 'L' , 1  );
        $pdf->Cell(50,6, $nama_asesi  , 1, 1 , 'L' , 0  ); // Nama pemohon

        $pdf->Cell(85,6, 'dapat:' , 'L,R' , 0 , 'L'  , 0 );

        $pdf->Cell(35,6, 'Tanggal' , 1, 0 , 'L' , 1  );
        $pdf->Cell(50,6, $tgl_permohonan , 1, 1 , 'L' , 0  );

        $pdf->Cell(17,6, 'Diterima' , 'L' , 0 , 'L'  , 0 );
        $pdf->Cell(3,6, '/' , '' , 0 , 'C'  , 0 );
        $pdf->Cell(25,6, 'tidak diterima' , '' , 0 , 'L'  , 0 );
        $pdf->Cell(40,6, 'sebagai asesi.' , 'R' , 0 , 'L'  , 0 );

        $pdf->Cell(35,12, '' , 1, 0 , 'L' , 1  );
        $pdf->Cell(50,12, '' , 1, 0 , 'L' , 0  );


        $pdf->Cell(0,3, '' , 0 , 1 , 'L'  , 0 ); //Hidden

        $pdf->Cell(1,0, '' , 'L' , 0 , 'L'  , 0 );

        $pdf->Cell(15,0, '' , $ditolak , 0 , 'L'  , 0 ); // Kalau Di tolak
        $pdf->Cell(4,0, '' , '' , 0 , 'C'  , 0 );
        $pdf->Cell(23,0, '' , $diterima , 0 , 'L'  , 0 ); // Kalau Diterima
        $pdf->Cell(42,0, '' , 'R' , 0 , 'L'  , 0 );

        $pdf->Cell(35,0, 'Tanda Tangan' , 0 , 0 , 'L' , 1  );
        $pdf->SetFont('Arial', 'B' ,10);
        $pdf->Cell(50,0, 'SIGNED' , 0, 0 , 'C' , 0  );

        $pdf->SetFont('Arial', '' ,10);

        $pdf->Cell(0,3, '' , 0 , 1 , 'L'  , 0 ); //Hidden

        
        $pdf->Cell(85,6, '' , 'L,R' , 0 , 'L'  , 0 );
        $pdf->Cell(35,6, '' , 'L,R,B' , 0 , 'L' , 1  );
        $pdf->Cell(50,6, $timestamp_permohonan , 'L,R,B' , 1 , 'C'  , 0 );


        $pdf->Cell(85,6, '' , 'L,T,R' , 0 , 'L'  , 0 );
        $pdf->Cell(85,6, 'Administrasi :' , 1, 1 , 'L' , 1  );

        $pdf->Cell(85,6, 'Catatan:' , 'L,R' , 0 , 'L'  , 0 );

        $nama_UA = $conn->query("SELECT `permohonan`.`timestamp_validasi` , `akun`.`nama` FROM `akun`,`permohonan`,`user_administrasi` WHERE `akun`.`email` = `user_administrasi`.`email` AND `user_administrasi`.`id_UA` = `permohonan`.`validasi_user_administrasi` AND `permohonan`.`id_permohonan` = '$id_permohonan' ");
        $d = mysqli_fetch_assoc($nama_UA); $nama_UA = $d ['nama']; 
        $timestamp_validasi = $d['timestamp_validasi'];
        $tgl_validasi = new DateTime($timestamp_validasi) ;
        $tgl_validasi = date_format($tgl_validasi, 'd - m - Y');

        $pdf->Cell(35,6, 'Nama' , 1, 0 , 'L' , 1  );
        $pdf->Cell(50,6, $nama_UA  , 1, 1 , 'L' , 0  ); // Nama pemohon



        $catatan1 = "";
        $catatan2 = $catatan1;
        $catatan3 = $catatan1;
        $catatan4 = $catatan1; 

        $catatan = $conn->query("SELECT `catatan` FROM `permohonan` WHERE `id_permohonan` = '$id_permohonan'");
        $catatan = mysqli_fetch_assoc($catatan) ['catatan'];

        if ( $catatan == '-' ) {

            $catatan1 = "";
            $catatan2 = $catatan1;
            $catatan3 = $catatan1;
            $catatan4 = $catatan1; 

        } 

        $cek_catatan = strlen($catatan);
        if ( $cek_catatan <= 50 && $catatan != '-' ) {

            // Pevcah jadi 2
            $catatan1 = $catatan;

        }

        if ( $cek_catatan > 50 && $cek_catatan <= 100 ) {

            $catatan = split_title_middle( $catatan );

            $catatan1 = $catatan[0];
            $catatan2 = $catatan[1]; 
        }

        if ( $cek_catatan > 100 && $cek_catatan <= 150 ) {

            // Potong 1
            $catatan1 = split_title_middle( $catatan );

            // Potong 2
            $catatan11 = split_title_middle($catatan1 [0] );
            $catatan21 = split_title_middle($catatan1 [1]);
            
            $catatan1 = $catatan11[0];
            $catatan2 = $catatan11[1];
            $catatan3 = $catatan21[0];
            $catatan4 = $catatan21[1];


        }


        $pdf->Cell(85,6, '' , 'L,R' , 0 , 'L'  , 0 ); // Catatan 2

        $pdf->Cell(35,6, 'Tanggal' , 1, 0 , 'L' , 1  );
        $pdf->Cell(50,6, $tgl_validasi  , 1, 1 , 'L' , 0  ); // Nama pemohon

        $pdf->Cell(85,6, $catatan1 , 'L,R' , 0 , 'L'  , 0 ); // Catatan 3

        $pdf->Cell(35,6, 'Tanda Tangan' , 'L,T,R', 0 , 'L' , 1  );
        $pdf->Image('./fpdf/cap_lsp.png',$pdf->GetX()+10,$pdf->GetY()+2,30);
        $pdf->Cell(50,6, ''  , 'L,T,R' , 1 , 'L' , 0  ); // Nama pemohon

        $pdf->Cell(85,6, $catatan2 , 'L,R' , 0 , 'L'  , 0 ); // Catatan 4

        $pdf->Cell(35,6, '' , 'L,R', 0 , 'L' , 1  );
        $pdf->Cell(50,6, ''  , 'L,R' , 1 , 'L' , 0  ); // Nama pemohon

        $pdf->Cell(85,6, $catatan3 , 'L,R' , 0 , 'L'  , 0 ); // Catatan 5

        $pdf->Cell(35,6, '' , 'L,R', 0 , 'L' , 1  );
        $pdf->Cell(50,6, ''  , 'L,R' , 1 , 'L' , 0  ); // Nama pemohon

        $pdf->Cell(85,6, $catatan4 , 'L,R' , 0 , 'L'  , 0 ); // Catatan 6

        $pdf->Cell(35,6, '' , 'L,R', 0 , 'L' , 1  );

        $pdf->SetFont('Arial', 'B' ,10);
        $pdf->Cell(50,6, 'SIGNED'  , 'L,R' , 1 , 'C' , 0  ); // Nama pemohon

        $pdf->SetFont('Arial', '' ,10);

        $pdf->Cell(85,6, '' , 'L,R,B' , 0 , 'L'  , 0 ); // Catatan 5

        $pdf->Cell(35,6, '' , 'L,R,B', 0 , 'L' , 1  );
        $pdf->Cell(50,6, $timestamp_validasi  , 'L,R,B' , 1 , 'C' , 0  ); // Nama pemohon


    }



    // Footer
    $pdf->AliasNbPages();





// Backup Tabel konfirmasi Lama
    //     // Tabel Konfirmasi
    //     $y = $pdf->GetX();

    //     $pdf->Cell(95,39, 'Rekomendasi (oleh LSP ):' , 1, 0 , 'L' , 0  );

    //     $y = $pdf->GetX();
    //     $pdf->setFillColor(251, 212, 180); 
    //     $pdf->Cell(95,8, 'Pemohon :' , 1, 1 , 'L' , 1  );

    //     $pdf->SetX($y); 

    //     $y = $pdf->GetX();
    //     $pdf->Cell(30,8, 'Nama' , 1, 0 , 'L' , 1  );
    //     $pdf->Cell(65,8, '' , 1, 1 , 'L' ,   );
    //     $pdf->SetX($y); 


    //     $y = $pdf->GetX();
    //     $pdf->Cell(30,8, 'Tanggal' , 1, 0 , 'L' , 1  );
    //     $pdf->Cell(65,8, '' , 1, 1 , 'L' ,   );
    //     $pdf->SetX($y);

    //     $y = $pdf->GetX();
    //     $pdf->Cell(30,15, 'Tanda Tangan' , 1, 0 , 'L' , 1  );
    //     $pdf->Cell(65,15, '' , 1, 1 , 'L' ,   );
    //     $pdf->SetX($y);



    // $pdf->SetX($y);

    // $y = $pdf->GetY();
    // $pdf->SetY($y);
    
    // // $pdf->SetFont('Arial','',12);
    // // $texta = 'Inilah yang akan terjadi apabila makan bertemu dengan minuman dan jatuh cinta, mereka akan menghasilkan buah hati yakni penyakit' ;
    // // $text=str_repeat('this is a word wrap test ',20);
    // // $nb=$pdf->WordWrap($text,120);


    // $y = $pdf->GetX();

    //     $pdf->Cell(95,39, 'Catatan : ' , 1, 0 , 'L' , 0  );



    //     $y = $pdf->GetX();
    //     $pdf->setFillColor(251, 212, 180); 
    //     $pdf->Cell(95,8, 'Administrasi :' , 1, 1 , 'L' , 1  );

    //     $pdf->SetX($y); 

    //     $y = $pdf->GetX();
    //     $pdf->Cell(30,8, 'Nama' , 1, 0 , 'L' , 1  );
    //     $pdf->Cell(65,8, '' , 1, 1 , 'L' ,   );
    //     $pdf->SetX($y);

    //     $y = $pdf->GetX();
    //     $pdf->Cell(30,8, 'Tanggal' , 1, 0 , 'L' , 1  );
    //     $pdf->Cell(65,8, '' , 1, 1 , 'L' ,   );
    //     $pdf->SetX($y);

    //     $y = $pdf->GetX();
    //     $pdf->Cell(30,15, 'Tanda Tangan' , 1, 0 , 'L' , 1  );
    //     $pdf->Cell(65,15, '' , 1, 1 , 'L' ,   );
    //     $pdf->SetX($y);

    // $pdf->SetX($y);



    // // Sebelah Kanan






    // $pdf->Ln(10);

    // $pdf->Cell(40,18,'Words Here', 1,0, 'C');
    // $x = $pdf->GetX();
    // $pdf->Cell(40,9,'Ada', 1,0);
    // $pdf->Cell(40,9,'Tidak Ada', 1,1);
    // $pdf->SetX($x);
    // $pdf->Cell(20,9,'Ada P', 1,0);
    // $pdf->Cell(20,9,'Ada TP', 1,0);
    // $pdf->Cell(40,9,'Tidak Ada', 1,1);
    // $pdf->SetX($x);


    // Tampilkan Hasil
    // $pdf->Output();

    // Simpan Di Direktori
    $dir = "./uploads/Asesi/" .$id_asesi. "/" .$id_permohonan; // kasih ID Asesi

    // Buat direktori simpan jika tidak ada
        if( !is_dir ( $dir ) ) {
            //Directory does not exist, so lets create it.
            mkdir( $dir, 0755, true );
        }

    $file_APL_01 = $id_permohonan. '-FR APL-01-' .$id_asesi. '-' .$nama_skema. '.pdf';
    $file = $dir. "/" .$file_APL_01;
    $pdf->Output($file,  'F'); // save into some other location
?>