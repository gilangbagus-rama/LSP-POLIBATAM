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
    function Header()     {
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
        $this->Cell(60,5, 'KOMISI SERTIFIKASI BNSP' , 'T' , 0 , 'L' , );
        $this->Cell(30,5, '' , 'T' , 0 , 'C' , );
        $this->Cell(60,5, 'FORM APL-02-2018' , 'T' , 0 , 'R' , );
        $this->setFillColor(0, 128, 0); 
        $this->SetTextColor(255, 255, 255); $this->SetFont('Arial','B',8);
        $this->Cell(20 , 10 , $this->PageNo() , 0 , 0 , 'C' , 1);
        // $this->Cell(20,10,'Page '.$this->PageNo().'/{nb}',1,0,'L');
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


    function WordWrap(&$text, $maxwidth) {
        $text = trim($text);
        if ($text==='')
            return 0;
        $space = $this->GetStringWidth(' ');
        $lines = explode("\n", $text);
        $text = '';
        $count = 0;

        foreach ($lines as $line)
        {
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

}

// Mintak ID Permohonan

// $id_permohonan = 'P0001'; // Debug

$id_permohonan = $id_permohonan;









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
    $pdf->SetFont('Arial','B',12); 
    
    // $pdf->Cell(170,0,'Ini tesss' , $border , 1 , 'L');
    // $pdf->Cell(170,0,'' , 1 , 1 , 'L');

    // $pdf->Ln(5);
    


    // $pdf->Line($pdf->GetX(), 10, 20, 10);

    // $pdf->Circle($pdf->GetX()+2,$pdf->GetY()+3,0.7,'F') , 0 , 0 , 'C');


    // Membuat Cel dengan ukuran 40 x 10
    $pdf->Cell(170,5,'FR.APL.02. ASESMEN MANDIRI' , $border , 1 , 'L');


    $pdf->Ln(3); $pdf->SetFont('Arial','B',10); $pdf->setFillColor(251, 212, 180);  

    $pdf->Cell(2,6 , '' , 'L,B,T' , 0 , 'C' , 1);
    $pdf->Cell(168,6,'PANDUAN ASESMEN MANDIRI' , 'R,B,T' , 1 , 'L' , 1);

    $pdf->Cell(2,6 , '' , 'L' , 0 , 'C' , 0);    
    $pdf->Cell(168,6,'Instruksi:' , 'R' , 1 , 'L' , 0);


    $pdf->SetFont('Arial','',9); $pdf->setFillColor(0,0,0);

    $pdf->Cell(2,5, '' , 'L' , 0 , 'C' , 0);
    $pdf->Cell(5,5, $pdf->Circle($pdf->GetX()+2,$pdf->GetY()+3,0.7,'F') , 0 , 0 , 'C');
    $pdf->Cell(163,5, 'Baca setiap pertanyaan di kolom sebelah kiri' , 'R' , 1 , 'L');

    $pdf->Cell(2,5 , '' , 'L' , 0 , 'C' , 0);
    $pdf->Cell(5,5, $pdf->Circle($pdf->GetX()+2,$pdf->GetY()+3,0.7,'F') , 0 , 0 , 'C');
    $pdf->Cell(163,5, 'Beritanda centang (v) pada kotak jika Anda yakin dapat melakukan tugas yang dijelaskan.' , 'R' , 1 , 'L');

    $pdf->Cell(2,5, '' , 'L' , 0 , 'C' , 0);
    $pdf->Cell(5,5, $pdf->Circle($pdf->GetX()+2,$pdf->GetY()+3,0.7,'F') , 0 , 0 , 'C');
    $pdf->Cell(163,5, 'Isi kolom di sebelah kanan dengan mendaftar bukti yang Anda miliki untuk menunjukkan bahwa Anda melakukan ' , 'R' , 1 , 'L');

    $pdf->Cell(2,5, '' , 'L,B' , 0 , 'C' , 0);
    $pdf->Cell(5,5, '' , 'B' , 0 , 'C');
    $pdf->Cell(163,5, 'tugas-tugas ini.' , 'R,B' , 1 , 'L');



// // Data Dari Asesi
//     $id_permohonan = $id_permohonan;
    

    $id_asesi = $conn->query("SELECT `id_skema` , `id_asesi` FROM `permohonan` WHERE `id_permohonan` = '$id_permohonan'");
    $data       = mysqli_fetch_assoc($id_asesi); 
    $id_asesi = $data['id_asesi'];
    $id_skema = $data['id_skema'];

    $email_asesi = $conn->query("SELECT `email` FROM `asesi` WHERE `id_asesi` = '$id_asesi'");
    $data       = mysqli_fetch_assoc($email_asesi); 
    $email_asesi = $data['email'];


    $sql_akun_asesi = $conn->query("SELECT `nama` , `no_hp` FROM `akun` WHERE `email` = '$email_asesi'");
    $data       = mysqli_fetch_assoc($sql_akun_asesi); 
    $nama_asesi = $data['nama']; $no_hp = $data['no_hp'];



    // SQL Skema Sertfikasi
    $sql_skema = $conn->query("SELECT * FROM `skema_sertifikasi` WHERE `id_skema` = '$id_skema' ");
    $data_skema = mysqli_fetch_assoc($sql_skema);
    $nama_skema = $data_skema['nama_skema']; $nomor_skema = $data_skema['nomor_skema'];


    // SQL AM
    $sql_AM = $conn->query("SELECT * FROM `asesmen_mandiri` WHERE `id_permohonan` = '$id_permohonan' ") ;
    $data_AM = mysqli_fetch_assoc($sql_AM);
    $id_AM = $data_AM['id_asesmen_mandiri'];

    $tgl = new DateTime( $data_AM['timestamp_asesmen_mandiri'] );
    $tgl_asesmen_mandiri = $tgl->format('d-m-Y');
    $jam_asesmen_mandiri = $tgl->format('H:i:s');

// $nomor_skema = '001';

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B' ,9); $pdf->setFillColor(251, 212, 180); 

    $pdf->Cell(50,16,'Skema Sertifikasi' , 1 , 0 , 'C' , 1); 
    $x = $pdf->GetX();
    $pdf->Cell(30,8,'Nomor' , 1 , 0 , 'C' , 1);
    $pdf->Cell(5,8,':' , 1 , 0 , 'C' , 1);
    $pdf->Cell(85,8, $nomor_skema , 1 , 1 , 'C' , 1);
    $pdf->SetX($x);
    $pdf->Cell(30,8,'Judul' , 1 , 0 , 'C' , 1);
    $pdf->Cell(5,8,':' , 1 , 0 , 'C' , 1);
    $pdf->Cell(85,8, $nama_skema , 1 , 1 , 'C' , 1);


    // SQL Asesmen Mandiri
    $sql_unit = $conn->query("SELECT * FROM `unit_skema` WHERE `id_skema` = '$id_skema' ") ;

    $no_unit = 1; // $no_unit ++

    //Masukkan ke while
    while ( $data_unit = mysqli_fetch_assoc($sql_unit) ) {
        $id_unit = $data_unit['id_unit']; 

        $kode_unit = $data_unit['kode_unit']; $judul_unit = $data_unit['judul_unit']; 
        $jenis_standar = $data_unit['jenis_standar'];

        $count = strlen($judul_unit);

        if ( $count >= 60 ) {


        $judul_unit = split_title_middle($judul_unit);


        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B' ,9);

        $pdf->Cell(50,20,'Unit Kompetensi ' .$no_unit , 1 , 0 , 'C' , 0); 
        $x = $pdf->GetX();
        $pdf->Cell(30,10,'Kode Unit', 1 , 0 , 'C' , 0);
        $pdf->Cell(5,10,':' , 1 , 0 , 'C' , 0);
        $pdf->Cell(85,10,$kode_unit , 1 , 1 , 'C' , 0);
        $pdf->SetX($x);
        $pdf->Cell(30,10,'Judul Unit' , 1 , 0 , 'C' , 0);
        $pdf->Cell(5,10,':' , 1 , 0 , 'C' , 0);
        $x = $pdf->GetX();
        $pdf->Cell(85,5, $judul_unit[0] , 'L,T,R' , 1 , 'C' , 0);
        $pdf->SetX($x);
        $pdf->Cell(85,5, $judul_unit[1] , 'L,B,R' , 1 , 'C' , 0);

        }


        if ( $count <= 60 ) {

            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B' ,9);
    
            $pdf->Cell(50,20,'Unit Kompetensi ' .$no_unit , 1 , 0 , 'C' , 0); 
            $x = $pdf->GetX();
            $pdf->Cell(30,10,'Kode Unit' , 1 , 0 , 'C' , 0);
            $pdf->Cell(5,10,':' , 1 , 0 , 'C' , 0);
            $pdf->Cell(85,10,$kode_unit , 1 , 1 , 'C' , 0);
            $pdf->SetX($x);
            $pdf->Cell(30,10,'Judul Unit' , 1 , 0 , 'C' , 0);
            $pdf->Cell(5,10,':' , 1 , 0 , 'C' , 0);
            $pdf->Cell(85,10, $judul_unit , 1 , 1 , 'C' , 0);

        }



        // $pdf->Cell(85,8,$judul_unit , 1 , 1 , 'C' , 0);


        $sql_elemen = $conn->query("SELECT * FROM `elemen_unit` WHERE `id_unit` = '$id_unit'");
        $no_el = 1; //$no_el ++
        
        // Tarok while disini
        while ( $data_elemen = mysqli_fetch_assoc($sql_elemen) ) {
            $id_elemen = $data_elemen['id_elemen']; $no_elemen = $data_elemen['no_elemen']; 
            $elemen = $data_elemen['elemen'];

            $elemen = split_title_middle($elemen);


            $pdf->Ln(5);
            $pdf->SetFont('Arial', '' ,10);

            $pdf->Cell(50,1,'' , 'L,T,R' , 0 , 'C' , 0); 
            $pdf->Cell(5,12,':' , 1 , 0 , 'C' , 0);
            $pdf->Cell(115,1, '' , 'L,T,R' , 1 , 'C' , 0);

            $pdf->Cell(50,5,'Elemen' , 'L,R' , 0 , 'C' , 0); 
            $pdf->Cell(5,5,'' , 0 , 0 , 'C' , 0);
            $pdf->Cell(115,5, $no_elemen. '. ' .$elemen[0] , 'L,R' , 1 , 'C' , 0);
            $pdf->Cell(50,5,'Kompetensi ' .$no_elemen  , 'L,R' , 0 , 'C' , 0); 
            $pdf->Cell(5,5,'' , 0 , 0 , 'C' , 0);
            $pdf->Cell(115,5, $elemen[1] , 'L,R' , 1 , 'C' , 0);

            $pdf->Cell(50,1,'' , 'L,B,R' , 0 , 'C' , 0); 
            $pdf->Cell(5,1, '' , 'L,B,R' , 0 , 'C' , 0);
            $pdf->Cell(115,1, '' , 'L,B,R' , 1 , 'C' , 0);

            $pdf->SetFont('Arial', '' ,9);    $pdf->setFillColor(198, 217, 241);
            $pdf->Cell(1,12, '' , 'L,T,B' , 0 , 'C' , 1);

            $x = $pdf->GetX();
            $pdf->Cell(12,6,'Nomor' , 'T,R' , 0 , 'C' , 1);
            $pdf->Cell(118,6,'Daftar Pertanyaan' , 'L,T,R' , 0 , 'C' , 1);
            $pdf->Cell(19,6,'Penilaian' , 1 , 0 , 'C' , 1);
            $pdf->Cell(20,6,'Bukti-Bukti' , 'L,T,R' , 1 , 'C' , 1);
            $pdf->SetX($x);

            $pdf->Cell(12,6,'KUK' , 'B,R' , 0 , 'C' , 1);
            $pdf->Cell(118,6,'(Asesmen Mandiri/Self  Assessment)' , 'L,B,R' , 0 , 'C' , 1);
            $pdf->Cell(9,6,'K' , 1 , 0 , 'C' , 1);
            $pdf->Cell(10,6,'BK' , 1 , 0 , 'C' , 1);
            $pdf->Cell(20,6,'Kompetensi' , 'L,B,R' , 1 , 'C' , 1);


            // SQL Asesmen Mandiri

            $sql_kuk = $conn->query("SELECT * FROM `kriteria_unjuk_kerja` WHERE `id_elemen` = '$id_elemen'");
            while ( $data_kuk = mysqli_fetch_assoc($sql_kuk) ) {
                $id_kuk = $data_kuk['id_kuk']; $no_kuk = $data_kuk['no_kuk']; $kuk = $data_kuk['kuk'];



                $sql_AM_kuk = $conn->query("SELECT * FROM `asesmen_mandiri_kuk` WHERE `id_asesmen_mandiri` = '$id_AM' AND `id_kuk` = '$id_kuk'");
                $data_AM_kuk = mysqli_fetch_assoc($sql_AM_kuk);

                $kompetensi = $data_AM_kuk['kompetensi'];

                if ( $kompetensi == 'K' ) {
                    $K = 'v'; $BK = '';
                }
                if ( $kompetensi == 'BK' ) {
                    $BK = 'v'; $K = '';
                }
                



                $pdf->SetFont('Arial', '' ,9);

                $count = strlen($kuk);

                if ( $count <= 70 ) {
        


                $pdf->Cell(13,10,$no_kuk , 1 , 0 , 'C' , 0);
                $pdf->Cell(118,10, $kuk , 1 , 0 , 'L' , 0);
                $pdf->Cell(9,10, $K , 1 , 0 , 'C' , 0);
                $pdf->Cell(10,10, $BK , 1 , 0 , 'C' , 0);
                $pdf->Cell(20,10,'' , 1 , 1 , 'C' , 0);
                
                }

                if ( $count > 70 && $count <= 140) { 
// 

                $kuk = split_title_middle($kuk);

                $pdf->Cell(13,10, $no_kuk , 'L,T,R' , 0 , 'C' , 0);
                $pdf->Cell(118,2, '' , 'L,T,R' , 0 , 'L' , 0);
                $pdf->Cell(9,10, $K , 'L,T,R' , 0 , 'C' , 0);
                $pdf->Cell(10,10, $BK , 'L,T,R' , 0 , 'C' , 0);
                $pdf->Cell(20,2,'' , 'L,T,R' , 1 , 'C' , 0);


                $pdf->Cell(13,4, '' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(118,4, $kuk[0] , 'L,R' , 0 , 'L' , 0);
                $pdf->Cell(9,4, '' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(10,4, '' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(20,4,'' , 'L,R' , 1 , 'C' , 0);

                $pdf->Cell(13,4,'' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(118,4, $kuk[1] , 'L,R' , 0 , 'L' , 0);
                $pdf->Cell(9,4,'' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(10,4,'' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(20,4,'' , 'L,R' , 1 , 'C' , 0);

                $pdf->Cell(13,2, '' , 'L,B,R' , 0 , 'C' , 0);
                $pdf->Cell(118,2, '' , 'L,B,R' , 0 , 'L' , 0);
                $pdf->Cell(9,2, '' , 'L,B,R' , 0 , 'C' , 0);
                $pdf->Cell(10,2, '' , 'L,B,R' , 0 , 'C' , 0);
                $pdf->Cell(20,2,'' , 'L,B,R' , 1 , 'C' , 0);


                }


                if ( $count >= 140 && $count < 210 ) { 

                // Potong 1
                $kuk1 = split_title_middle($kuk);

                //  Potong 2
                $kuk20 = split_title_middle($kuk1 [0]);
                $kuk21 = split_title_middle($kuk1 [1]);

                // Potong 3
                $kuk3201 = split_title_middle($kuk20 [0]);
                $kuk3202 = split_title_middle($kuk20 [1]);

                $kuk3211 = split_title_middle($kuk21 [0]);
                $kuk3212 = split_title_middle($kuk21 [1]);


                // Potong 4
                // $kuk111 = split_title_middle($kuk3201) [0];
                // $kuk222 = split_title_middle($kuk3201) [1];

                // $kuk333 = split_title_middle($kuk3202) [0];
                // $kuk444 = split_title_middle($kuk3202) [1];

                // $kuk555 = split_title_middle($kuk3211) [0];
                // $kuk666 = split_title_middle($kuk3211) [1];

                // $kuk777 = split_title_middle($kuk3212) [0];
                // $kuk888 = split_title_middle($kuk3212) [1];


                $pdf->Cell(13,12, $no_kuk , 'L,T,R' , 0 , 'C' , 0);
                $pdf->Cell(118,2, '' , 'L,T,R' , 0 , 'L' , 0);
                $pdf->Cell(9,12, $K , 'L,T,R' , 0 , 'C' , 0);
                $pdf->Cell(10,12, $BK , 'L,T,R' , 0 , 'C' , 0);
                $pdf->Cell(20,2,'' , 'L,T,R' , 1 , 'C' , 0);


                $pdf->Cell(13,4,'' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(118,4, $kuk3201 [0]. ' ' .$kuk3201 [1]. ' ' .$kuk3202 [0] , 'L,R' , 0 , 'L' , 0);
                $pdf->Cell(9,4, '' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(10,4, '' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(20,4,'' , 'L,R' , 1 , 'C' , 0);

                $pdf->Cell(13,4,'' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(118,4, $kuk3202 [1]. ' ' .$kuk3211 [0]. ' ' .$kuk3211 [1] , 'L,R' , 0 , 'L' , 0);
                $pdf->Cell(9,4,'' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(10,4,'' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(20,4,'' , 'L,R' , 1 , 'C' , 0);

                $pdf->Cell(13,4,'' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(118,4, $kuk3212 [0]. ' ' .$kuk3212 [1] , 'L,R' , 0 , 'L' , 0);
                $pdf->Cell(9,4,'' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(10,4,'' , 'L,R' , 0 , 'C' , 0);
                $pdf->Cell(20,4,'' , 'L,R' , 1 , 'C' , 0);

                $pdf->Cell(13,2, '' , 'L,B,R' , 0 , 'C' , 0);
                $pdf->Cell(118,2, '' , 'L,B,R' , 0 , 'L' , 0);
                $pdf->Cell(9,2, '' , 'L,B,R' , 0 , 'C' , 0);
                $pdf->Cell(10,2, '' , 'L,B,R' , 0 , 'C' , 0);
                $pdf->Cell(20,2,'' , 'L,B,R' , 1 , 'C' , 0);


                }

                // if ( $count >= 50 ) {


                //     $kuk = split_title_middle($kuk);
    
                //     $pdf->Cell(13,5,$no_kuk , 'L,T,R' , 0 , 'C' , 0);
                //     $pdf->Cell(118,5, $kuk[0] , 'L,T,R' , 0 , 'L' , 0);
                //     $pdf->Cell(9,5, $K , 'L,T,R' , 0 , 'C' , 0);
                //     $pdf->Cell(10,5, $BK , 'L,T,R' , 0 , 'C' , 0);
                //     $pdf->Cell(20,5,'' , 'L,T,R' , 1 , 'C' , 0);
    
                //     $pdf->Cell(13,5,'' , 'L,B,R' , 0 , 'C' , 0);
                //     $pdf->Cell(118,5, $kuk[1] , 'L,B,R' , 0 , 'L' , 0);
                //     $pdf->Cell(9,5,'' , 'L,B,R' , 0 , 'C' , 0);
                //     $pdf->Cell(10,5,'' , 'L,B,R' , 0 , 'C' , 0);
                //     $pdf->Cell(20,5,'' , 'L,B,R' , 1 , 'C' , 0);
    
    
                //     }

            }

        }

        $no_unit++;
    }


    function split_title_middle ( $title ) {
        $title = strip_tags( $title );
        $middle_length = floor( strlen( $title ) / 2 );
        $new_title = explode( '<br />', wordwrap( $title, $middle_length, '<br />') );
        if (isset( $new_title[2] ) ) {
            $new_title[1] .= ' ' . $new_title[2];
            unset( $new_title[2] );
        }
    
        return $new_title;
    }

    // $data_skema = $conn->query("SELECT * FROM `skema_sertifikasi` WHERE `id_skema` = '$id_skema'");
    // $data       = mysqli_fetch_assoc($data_skema);

    // $nama_skema = $data['nama_skema'];
    // $nomor_skema = $data['nomor_skema'];
    // $id_skema = $data['id_skema'];


    $pdf->AddPage();
    // $pdf->AddPage();
    $pdf->Ln(10); // Line Break
    $pdf->SetFont('Arial', '' ,9); $pdf->setFillColor(251, 212, 180); 


    $pdf->Cell(70,6, 'Nama Asesi:' , 'L,T,R' , 0 , 'L'  , 0 );
    $pdf->Cell(40,6, 'Tanggal:' , 'L,T,R' , 0 , 'L'  , 0 );
    $pdf->Cell(60,6, 'Tanda Tangan Asesi:' , 'L,T,R' , 1 , 'L'  , 0 );

    $pdf->Cell(70,15, $nama_asesi , 'L,B,R' , 0 , 'L'  , 0 );
    $pdf->Cell(40,15, $tgl_asesmen_mandiri , 'L,B,R' , 0 , 'L'  , 0 );
    $x = $pdf->GetX();$pdf->SetFont('Arial', 'B' ,9);
    $pdf->Cell(60,7.5, 'SIGNED' , 'L,R' , 1 , 'L'  , 0 );$pdf->SetFont('Arial', '' ,9);

    $pdf->SetX($x);
    $pdf->Cell(60,7.5, $tgl_asesmen_mandiri. ' , Jam ' .$jam_asesmen_mandiri , 'L,B,R' , 1 , 'L'  , 0 );

    
    $cek_validasiAM = $conn->query("SELECT `rekomendasi_asesor` FROM `asesmen_mandiri` WHERE `id_permohonan` = '$id_permohonan'");
    $cek_validasiAM = mysqli_fetch_assoc($cek_validasiAM) ['rekomendasi_asesor'];


    $nama_asesor = ""; $rekomendasi1 = ""; $rekomendasi2 = ""; $timestamp_validasi = ""; $validasi = ""; $waktu_validasi = "";


    if ( $cek_validasiAM != '-' ) {

    $nama_asesor = $conn->query("SELECT `asesmen_mandiri`.`timestamp_tinjauan_asesor`,`akun`.`nama` , `asesmen_mandiri`.`rekomendasi_asesor` FROM `akun`,`asesor`,`asesmen_mandiri` WHERE `asesmen_mandiri`.`id_asesor` = `asesor`.`id_asesor` AND `asesor`.`email` = `akun`.`email` AND `asesmen_mandiri`.`id_permohonan` = '$id_permohonan' ");    
    $c = mysqli_fetch_assoc($nama_asesor); $nama_asesor = $c['nama']; $rekomendasi = $c['rekomendasi_asesor']; $timestamp_validasi = $c['timestamp_tinjauan_asesor'];


    $tgl = new DateTime( $timestamp_validasi );
    $tgl_validasi = $tgl->format('d - m - Y');
    $jam_validasi = $tgl->format('H:i:s');

    $validasi = "SIGNED";
    $waktu_validasi = $tgl_validasi. ", Jam " .$jam_validasi ;


    }
    

    $pdf->Cell(170,6, 'Ditinjau oleh Pelatih dan / atau Asesor' , 1 , 1 , 'L'  , 1 );

    $pdf->Cell(50,6, 'Nama Pelatih dan / atau Asesor: ' , 'L,T,R' , 0 , 'L'  , 0 );
    $pdf->Cell(60,6, 'Rekomendasi:' , 'L,T,R' , 0 , 'L'  , 0 );
    $pdf->Cell(60,6, 'Tanda Tangan dan Tanggal:' , 'L,T,R' , 1 , 'L'  , 0 );

    $pdf->Cell(50,6, '' , 'L,R' , 0 , 'L'  , 0 );
    $pdf->Cell(60,6, ''  , 'L,R' , 0 , 'L'  , 0 ); // Rekomendasi 1
    $pdf->SetFont('Arial', 'B' ,9);
    $pdf->Image('./fpdf/cap_lsp.png',$pdf->GetX()+10,$pdf->GetY()+2,30);
    $pdf->Cell(60,6, '' , 'L,R' , 1 , 'L'  , 0 ); // TTD Kolom 1
    $pdf->SetFont('Arial', '' ,9);


    $pdf->Cell(50,8, $nama_asesor , 'L,R' , 0 , 'L'  , 0 ); // Nama Asesor
    $pdf->Cell(60,8, $rekomendasi , 'L,R' , 0 , 'L'  , 0 ); // Rekomendasi 2
    $pdf->Cell(60,8, '' , 'L,R' , 1 , 'L'  , 0 ); // TTD Kolom 2

    $pdf->Cell(50,8, '' , 'L,R' , 0 , 'L'  , 0 ); // Nama Asesor
    $pdf->Cell(60,8, '' , 'L,R' , 0 , 'L'  , 0 ); // Rekomendasi 2

    $pdf->SetFont('Arial', 'B' ,9);
    $pdf->Cell(60,8, 'SIGNED' , 'L,R' , 1 , 'L'  , 0 ); // TTD Kolom 2

    $pdf->SetFont('Arial', '' ,9);
    $pdf->Cell(50,8, '' , 'L,B,R' , 0 , 'L'  , 0 ); // Nama Asesor
    $pdf->Cell(60,8, '', 'L,B,R' , 0 , 'L'  , 0 ); // Rekomendasi 2
    $pdf->Cell(60,8, $waktu_validasi , 'L,B,R' , 1 , 'L'  , 0 ); // TTD Kolom 2


    // Footer
    $pdf->AliasNbPages();



    // Tampilkan Hasil
    // $pdf->Output();

    // Simpan Di Direktori
    $dir = "./uploads/Asesi/" .$id_asesi. "/" .$id_permohonan; // kasih ID Asesi

    // Buat direktori simpan jika tidak ada
    if( !is_dir ( $dir ) ) {
        //Directory does not exist, so lets create it.
        mkdir( $dir, 0755, true );
    }

    $file_APL_02 = $id_permohonan. '-FR APL-02-' .$id_asesi. '-' .$nama_skema. '.pdf';

    $file = $dir. "/" .$file_APL_02;

    $pdf->Output($file,  'F'); // save into some other location

?>