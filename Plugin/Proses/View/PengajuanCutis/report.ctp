<?php 
        ob_start();
		include ROOT.$this->base."/Vendor/fpdf/fpdf.php";
		$pdf=new FPDF('P','mm',array(215,330));
		$pdf->SetTopMargin(5);
        // $pdf->SetAutoPageBreak(true);   
		$pdf->AddPage();
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','','8');
		$pdf->Cell(25, 4, 'LAMPIRAN II  ',"", 0,"L", true);
		$pdf->Cell(5, 4, ':  ',"", 0,"L", true);
		$pdf->Cell(180, 4, 'SURAT EDARAN SEKRETARIS MAHKAMAH AGUNG ',"", 1,"L", true);
		$pdf->Cell(25, 4, ' ',"", 0,"L", true);
		$pdf->Cell(5, 4, '  ',"", 0,"L", true);
		$pdf->Cell(180, 4, 'REPUBLIK INDONESIA ',"", 1,"L", true);
		$pdf->Cell(25, 4, ' ',"", 0,"L", true);
		$pdf->Cell(5, 4, ' ',"", 0,"L", true);
		$pdf->Cell(180, 4, 'NOMOR 13 TAHUN 2019 ',"", 1,"L", true);
        $pdf->Ln(4);

		$pdf->SetFont('Arial','','10');
        $pdf->Cell(109, 5, '',"", 0,"R", true);
        $pdf->Cell(68, 5, 'Pematangsiantar, '.date('d F Y', strtotime($data['PengajuanCuti']['tanggal_pengajuan'])),"", 1,"L", true);
        $pdf->Cell(109, 3, '',"", 1,"R", true);
        $pdf->Cell(109, 5, '',"", 0,"R", true);
		$pdf->Cell(68, 5, 'Kepada Yth.',"", 1,"L", true);
        $pdf->Cell(109, 5, '',"", 0,"R", true);
		$pdf->Cell(70, 5, 'Ketua Pengadilan Negeri Pematangsiantar',"", 1,"L", true);
        $pdf->Cell(109, 5, '',"", 0,"R", true);
		$pdf->Cell(68, 5, 'di Pematangsiantar',"", 1,"L", true);
		// $pdf->Cell(115, 2, '  ',"", 0,"L", true);
		// $pdf->Cell(50, 5, 'T e m p a t',"", 1,"L", true);
        $pdf->Ln(4);

        $pdf->SetFont('Arial','B','10');
        $pdf->Cell(195, 6, 'FORMULIR PERMINTAAN DAN PEMBERIAN CUTI',"", 1,"C", true);

        $nomor_surat = $data['PengajuanCuti']['nomor_pengajuan'].'/'.$data['PengajuanCuti']['jabatan_ppk'].'/Kp.05.02/'.date('m').'/'.date('Y');
        $pdf->SetFont('Arial','','10');
		$pdf->Cell(195, 4, 'Nomor : '.$nomor_surat,"", 1,"C", true);
        $pdf->Ln(4);

        //DATA PEGAWAI
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 6, 'I.', "TL", 0,"L", true);
        $pdf->Cell(187, 6, 'DATA PEGAWAI', "TR", 1,"L", true);

        $pdf->Cell(25, 6, 'NAMA', "TLR", 0,"L", true);
        $pdf->Cell(85, 6, strtoupper($data['PengajuanCuti']['nama']), "TLR", 0,"L", true);
        $pdf->Cell(25, 6, 'NIP', "TL", 0,"L", true);
        $pdf->Cell(60, 6, $data['PengajuanCuti']['nip'], "TLR", 1,"L", true);

        $pdf->Cell(25, 6, 'JABATAN', "TLR", 0,"L", true);
        $pdf->Cell(85, 6, strtoupper($data['PengajuanCuti']['jabatan']), "TLR", 0,"L", true);
        $pdf->Cell(25, 6, 'GOL. RUANG', "TL", 0,"L", true);
        $pdf->Cell(60, 6, $data['PengajuanCuti']['golongan'], "TLR", 1,"L", true);

        $pdf->Cell(25, 6, 'UNIT KERJA', "TLR", 0,"L", true);
        $pdf->Cell(85, 6, strtoupper($data['PengajuanCuti']['unit_kerja']), "TLR", 0,"L", true);
        $pdf->Cell(25, 6, 'MASA KERJA', "TL", 0,"L", true);
        $pdf->Cell(60, 6, strtoupper($data['PengajuanCuti']['masa_kerja']), "TLR", 1,"L", true);

        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //JENIS CUTI YANG DIAMBIL
        $CT = $CS = $CB = $CM = $CAP = $CTLN = "";
        if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CT') $CT = 'V';
        if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CB') $CB = 'V';
        if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CS') $CS = 'V';
        if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CAP') $CAP = 'V';
        if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CM') $CM = 'V';
        if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CTLN') $CTLN = 'V';

        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 6, 'II.', "TL", 0,"L", true);
        $pdf->Cell(187, 6, 'JENIS CUTI YANG DIAMBIL**', "TR", 1,"L", true);

        $pdf->Cell(75, 6, '1. CUTI TAHUNAN', "TLR", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 6, $CT, "TLR", 0,"C", true);
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(70, 6, '2. CUTI BESAR ', "TL", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 6, $CB, "TLR", 1,"C", true);

        $pdf->SetFont('Arial','','8');
        $pdf->Cell(75, 6, '3. CUTI SAKIT', "TLR", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 6, $CS, "TLR", 0,"C", true);
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(70, 6, '4. CUTI MELAHIRKAN', "TL", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 6, $CM, "TLR", 1,"C", true);

        $pdf->SetFont('Arial','','8');
        $pdf->Cell(75, 6, '5. CUTI KARENA ALASAN PENTING', "TLR", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 6, $CAP, "TLR", 0,"C", true);
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(70, 6, '6. CUTI DILUAR TANGGUNGAN NEGARA', "TL", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 6, $CTLN, "TLR", 1,"C", true);

        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //ALASAN CUTI
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 6, 'III.', "TL", 0,"L", true);
        $pdf->Cell(187, 6, 'ALASAN CUTI', "TR", 1,"L", true);
        $pdf->MultiCell(195, 6, strtoupper($data['PengajuanCuti']['alasan_cuti']), "TLR", 1,"L", true);
        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //LAMANYA CUTI
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 6, 'IV.', "TL", 0,"L", true);
        $pdf->Cell(187, 6, 'LAMANYA CUTI', "TR", 1,"L", true);
        $pdf->Cell(20, 6, 'Selama', "TLR", 0,"C", true);
        $pdf->Cell(45, 6, $data['PengajuanCuti']['jumlah_cuti'].'  (Hari/Bulan/Tahun)*', "TLR", 0,"C", true);
        $pdf->Cell(30, 6, 'Mulai Tanggal', "TLR", 0,"C", true);
        $pdf->Cell(45, 6, date('d M y', strtotime($data['PengajuanCuti']['dari_tanggal'])), "TLR", 0,"C", true);
        $pdf->Cell(10, 6, 's/d', "TLR", 0,"C", true);
        $pdf->Cell(45, 6, date('d M y', strtotime($data['PengajuanCuti']['sampai_tanggal'])), "TLR", 1,"C", true);
        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //CATATAN CUTI
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 6, 'V.', "TL", 0,"L", true);
        $pdf->Cell(187, 6, 'CATATAN CUTI***', "TR", 1,"L", true);
        $pdf->Cell(8, 6, '1.', "TL", 0,"L", true);
        $pdf->Cell(78, 6, 'CUTI TAHUNAN', "TR", 0,"L", true);
        $pdf->Cell(26, 6, 'PARAF', "TLR", 0,"C", true);
        $pdf->Cell(58, 6, '2. CUTI BESAR', "TLR", 0,"L", true);
        $pdf->Cell(25, 6, '', "TLR", 1,"C", true);
        
        $pdf->Cell(15, 6, 'TAHUN', "TLR", 0,"C", true);
        $pdf->Cell(15, 6, 'SISA', "TLR", 0,"C", true);
        $pdf->Cell(56, 6, 'KETERANGAN', "TLR", 0,"C", true);
        $pdf->Cell(26, 6, 'PETUGAS CUTI', "LR", 0,"C", true);
        $pdf->Cell(58, 6, '3. CUTI SAKIT', "TLR", 0,"L", true);
        $pdf->Cell(25, 6, '', "TLR", 1,"C", true);

        $year = date('Y');
        $year1 = $year - 1;
        $year2 = $year - 2;

        $pdf->Cell(15, 6, $year2, "TLR", 0,"C", true);
        $pdf->Cell(15, 6, '0', "TLR", 0,"C", true);
        $pdf->Cell(56, 6, '', "TLR", 0,"C", true);
        $pdf->Cell(26, 18, '', "TLR", 0,"C", true);
        $pdf->Cell(58, 6, '4. CUTI MELAHIRKAN', "TLR", 0,"L", true);
        $pdf->Cell(25, 6, '', "TLR", 1,"C", true);

        $ket1 = $sisaTL-$cutiAmbil1;
        $pdf->Cell(15, 6, $year1, "TLR", 0,"C", true);
        $pdf->Cell(15, 6, $sisaTL, "TLR", 0,"C", true);
        $pdf->Cell(56, 6, $sisaTL.' - '.$cutiAmbil1. ' = '.$ket1, "TLR", 0,"C", true);
        $pdf->Cell(26, 18, '', "", 0,"C", false);
        $pdf->Cell(58, 6, '5. CUTI KARENA ALASAN PENTING', "TLR", 0,"L", true);
        $pdf->Cell(25, 6, '', "TLR", 1,"C", true);

        $ket2 = 12-$cutiAmbil2;
        $pdf->Cell(15, 6, $year, "TLR", 0,"C", true);
        $pdf->Cell(15, 6, '12', "TLR", 0,"C", true);
        $pdf->Cell(56, 6, '12 - '.$cutiAmbil2.' = '.$ket2, "TLR", 0,"C", true);
        $pdf->Cell(26, 18, '', "", 0,"C", FALSE);
        $pdf->Cell(58, 6, '6. CUTI DI LUAR TANGGUNGAN NEGARA', "TLR", 0,"L", true);
        $pdf->Cell(25, 6, '', "TLR", 1,"C", true);
            
        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //Perubahan I

		$pdf->Output('Formulir Cuti.pdf','I');

		ob_end_flush();
		exit;

?>