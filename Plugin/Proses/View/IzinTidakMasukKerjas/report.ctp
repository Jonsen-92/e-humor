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
		$pdf->Cell(117, 4, '',"", 0,"L", true);
		$pdf->Cell(18, 4, 'Lampiran III  ',"", 0,"L", true);
		$pdf->Cell(2, 4, ':  ',"", 0,"L", true);
		$pdf->Cell(58, 4, 'Formulir Izin Tidak Masuk Kerja ',"", 1,"L", true);
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
		$pdf->Cell(195, 6, 'IZIN TIDAK MASUK KERJA',"", 1,"C", true);
        $pdf->Ln(10);

        $pdf->SetFont('Arial','','12');      
        $pdf->Cell(20, 6, '',"", 0,"L", true);
        $pdf->Cell(70, 6, 'Yang bertandatangan di',"", 0,"L", true);
        $pdf->Cell(2, 6, ':',"", 0,"L", true);
        $pdf->Cell(103, 6, $data['IzinTidakMasukKerja']['nama_pemberi_izin'],"", 1,"L", true);
        $pdf->Cell(20, 6, '',"", 0,"L", true);
        $pdf->Cell(72, 6, 'bawah ini',"", 0,"L", true);
        $pdf->Cell(103, 6, '',"T", 1,"C", true);
        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->Cell(70, 6, 'Selaku',"", 0,"L", true);
        $pdf->Cell(2, 6, ':',"", 0,"L", true);
        $pdf->Cell(103, 6, $data['IzinTidakMasukKerja']['jabatan_pemberi_izin'],"", 1,"L", true);
        $pdf->Cell(92, 6, '',"", 0,"L", true);
        $pdf->Cell(103, 6, '',"T", 1,"C", true);

        
        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->Cell(70, 6, 'Dengan ini memberikan izin',"", 0,"L", true);
        $pdf->Cell(2, 6, ':',"", 0,"L", true);
        $pdf->Cell(103, 6, $data['IzinTidakMasukKerja']['nama_pemohon'],"", 1,"L", true);
        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->Cell(72, 6, 'kepada',"", 0,"L", true);
        $pdf->Cell(103, 6, 'NIP. '.$data['IzinTidakMasukKerja']['nip_pemohon'],"T", 1,"L", true);
        $pdf->Ln(5);

        $tglAwal = date('d-m-Y', strtotime($data['IzinTidakMasukKerja']['tanggal_mulai']));
        $tglAkhir = date('d-m-Y', strtotime($data['IzinTidakMasukKerja']['tanggal_akhir']));
        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->Cell(70, 6, 'Untuk tidak masuk kerja pada',"", 0,"L", true);
        $pdf->Cell(2, 6, ':',"", 0,"L", true);
        $pdf->Cell(103, 6, $tglAwal.'   s/d   '.$tglAkhir,"", 1,"L", true);
        $pdf->Ln(3);

        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->Cell(70, 6, 'Untuk Keperluan',"", 0,"L", true);
        $pdf->Cell(2, 6, ':',"", 0,"L", true);
        $pdf->Cell(103, 6, $data['IzinTidakMasukKerja']['keterangan'],"", 1,"L", true);
        $pdf->Ln(10);

        $pdf->Cell(20, 6, '',"", 0,"C", true);
        $pdf->MultiCell(165, 6, 'Demikian Izin ini diberikan kepada yang bersangkutan untuk digunakan sebagaimana mestinya',"", 1,"L", true);
        $pdf->Ln(5);

        $pdf->Cell(95, 6, 'Pemohon,',"", 0,"C", true);
        $pdf->Cell(100, 6, 'Pematangsiantar, '.date('d F Y', strtotime($data['IzinTidakMasukKerja']['tanggal_persetujuan'])),"", 1,"C", true);
        $pdf->Cell(26, 6, $pdf->image('img/'.$data['IzinTidakMasukKerja']['nip_pemohon'].'ttd..png',49,142,16,16), "", 1,"C", false);
        $pdf->Cell(26, 15, $pdf->image('img/'.$data['IzinTidakMasukKerja']['nip_pemberi_izin'].'ttd..png',145,142,16,16), "", 1,"C", false);
        $pdf->SetFont('Arial','BU','10');
        $pdf->Cell(95, 6, '( '.$data['IzinTidakMasukKerja']['nama_pemohon'].' )',"", 0,"C", true);
        $pdf->Cell(100, 6, '( '.$data['IzinTidakMasukKerja']['nama_pemberi_izin'].' )',"", 1,"C", true);

		$pdf->Output('formulir_izin_keluar_kantor.pdf','I');

		ob_end_flush();
		exit;

?>