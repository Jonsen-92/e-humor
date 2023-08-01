<?php 
        ob_start();
		include ROOT.$this->base."/cutionline/Vendor/fpdf/fpdf.php";
		$pdf=new FPDF('P','mm',array(215,330));
		$pdf->SetTopMargin(5);
        $pdf->SetAutoPageBreak(false);   
		$pdf->AddPage();
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','','8');
		$pdf->Cell(120, 4, '',"", 0,"L", true);
		$pdf->Cell(15, 4, 'Lampiran II  ',"", 0,"L", true);
		$pdf->Cell(2, 4, ':  ',"", 0,"L", true);
		$pdf->Cell(58, 4, 'Formulir Izin Keluar Kantor ',"", 1,"L", true);
		$pdf->Cell(137, 4, ' ',"", 0,"L", true);
		$pdf->Cell(58, 4, 'Peraturan Mahkamah Agung Nomor 7',"", 1,"L", true);
		$pdf->Cell(137, 4, ' ',"", 0,"L", true);
		$pdf->Cell(58, 4, 'Tahun 2016 tentang Penegakan Disiplin',"", 1,"L", true);
		$pdf->Cell(137, 4, ' ',"", 0,"L", true);
		$pdf->Cell(58, 4, 'Kerja Hakim Pada Mahkamah Agung dan',"", 1,"L", true);
		$pdf->Cell(137, 4, ' ',"", 0,"L", true);
		$pdf->Cell(58, 4, 'Badan Peradilan yang berada dibawahnya',"", 1,"L", true);
        $pdf->Ln(10);

        $pdf->SetFont('Arial','BU','14');
		$pdf->Cell(195, 6, 'IZIN KELUAR KANTOR',"", 1,"C", true);
        $pdf->Ln(10);

        $pdf->SetFont('Arial','','12');      
        $pdf->Cell(20, 6, '',"", 0,"L", true);
        $pdf->Cell(70, 6, 'Yang bertandatangan di',"", 0,"L", true);
        $pdf->Cell(2, 6, ':',"", 0,"L", true);
        $pdf->Cell(103, 6, $data['IzinKeluarKantor']['nama_pemberi_izin'],"", 1,"L", true);
        $pdf->Cell(20, 6, '',"", 0,"L", true);
        $pdf->Cell(72, 6, 'bawah ini',"", 0,"L", true);
        $pdf->Cell(103, 6, '',"T", 1,"C", true);
        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->Cell(70, 6, 'Selaku',"", 0,"L", true);
        $pdf->Cell(2, 6, ':',"", 0,"L", true);
        $pdf->Cell(103, 6, $data['IzinKeluarKantor']['jabatan_pemberi_izin'],"", 1,"L", true);
        $pdf->Cell(92, 6, '',"", 0,"L", true);
        $pdf->Cell(103, 6, '',"T", 1,"C", true);

        
        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->Cell(70, 6, 'Dengan ini memberikan',"", 0,"L", true);
        $pdf->Cell(2, 6, ':',"", 0,"L", true);
        $pdf->Cell(103, 6, $data['IzinKeluarKantor']['nama_pemohon'],"", 1,"L", true);
        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->Cell(72, 6, 'Izin kepada',"", 0,"L", true);
        $pdf->Cell(103, 6, 'NIP. '.$data['IzinKeluarKantor']['nip_pemohon'],"T", 1,"L", true);
        $pdf->Ln(5);

        $hari = date('l', strtotime($data['IzinKeluarKantor']['tanggal_izin']));
        if($hari == 'Sunday'){$hari = 'Minggu';}
        else if ($hari == 'Monday'){$hari = 'Senin';}
        else if ($hari == 'Tuesday'){$hari = 'Selasa';}
        else if ($hari == 'Wednesday'){$hari = 'Rabu';}
        else if ($hari == 'Thrusday'){$hari = 'Kamis';}
        else if ($hari == 'Friday'){$hari = 'Jumat';}
        else if ($hari == 'Saturday'){$hari = 'Sabtu';}
        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->Cell(70, 6, 'Untuk Keluar Kantor Pada',"", 0,"L", true);
        $pdf->Cell(2, 6, ':',"", 0,"L", true);
        $pdf->Cell(25, 6, $hari."  ,","", 0,"L", true);
        $pdf->Cell(78, 6, date('d F Y', strtotime($data['IzinKeluarKantor']['tanggal_izin'])),"", 1,"L", true);
        $pdf->Cell(92, 6, '',"", 0,"L", true);
        $pdf->Cell(15, 6, 'Pukul',"T", 0,"L", true);
        $pdf->Cell(20, 6, $data['IzinKeluarKantor']['jam_mulai'],"T", 0,"C", true);
        $pdf->Cell(5, 6, 's.d.',"T", 0,"C", true);
        $pdf->Cell(20, 6, $data['IzinKeluarKantor']['jam_akhir'],"T", 0,"C", true);
        $pdf->Cell(43, 6, 'WIB',"T", 1,"L", true);

        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->Cell(70, 6, 'Untuk Keperluan',"", 0,"L", true);
        $pdf->Cell(2, 6, ':',"", 0,"L", true);
        $pdf->Cell(103, 6, $data['IzinKeluarKantor']['keterangan'],"", 1,"L", true);
        $pdf->Ln(10);

        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->MultiCell(165, 6, 'Demikian Izin ini diberikan kepada yang bersangkutan untuk digunakan sebagaimana mestinya',"", 1,"L", true);
        $pdf->Ln(5);

        $pdf->Cell(95, 6, 'Pemohon,',"", 0,"C", true);
        $pdf->Cell(100, 6, 'Pematangsiantar, '.date('d F Y', strtotime($data['IzinKeluarKantor']['tanggal_persetujuan'])),"", 1,"C", true);
        $pdf->Cell(26, 6, $pdf->image('img/'.$data['IzinKeluarKantor']['nip_pemohon'].'ttd..png',50,145,16,16), "", 1,"C", false);
        $pdf->Cell(26, 15, $pdf->image('img/'.$data['IzinKeluarKantor']['nip_pemberi_izin'].'ttd..png',145,145,16,16), "", 1,"C", false);
        $pdf->SetFont('Arial','BU','10');
        $pdf->Cell(95, 6, '( '.$data['IzinKeluarKantor']['nama_pemohon'].' )',"", 0,"C", true);
        $pdf->Cell(100, 6, '( '.$data['IzinKeluarKantor']['nama_pemberi_izin'].' )',"", 1,"C", true);

		$pdf->Output('formulir_izin_keluar_kantor.pdf','I');

		ob_end_flush();
		exit;

?>