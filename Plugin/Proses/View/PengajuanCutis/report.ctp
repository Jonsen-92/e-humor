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
        $pdf->Cell(8, 4, 'I.', "TL", 0,"L", true);
        $pdf->Cell(187, 4, 'DATA PEGAWAI', "TR", 1,"L", true);

        $pdf->Cell(25, 4, 'NAMA', "TLR", 0,"L", true);
        $pdf->Cell(85, 4, strtoupper($data['PengajuanCuti']['nama']), "TLR", 0,"L", true);
        $pdf->Cell(25, 4, 'NIP', "TL", 0,"L", true);
        $pdf->Cell(60, 4, $data['PengajuanCuti']['nip'], "TLR", 1,"L", true);

        $pdf->Cell(25, 4, 'JABATAN', "TLR", 0,"L", true);
        $pdf->Cell(85, 4, strtoupper($data['PengajuanCuti']['jabatan']), "TLR", 0,"L", true);
        $pdf->Cell(25, 4, 'GOL. RUANG', "TL", 0,"L", true);
        $pdf->Cell(60, 4, $data['PengajuanCuti']['golongan'], "TLR", 1,"L", true);

        $pdf->Cell(25, 4, 'UNIT KERJA', "TLR", 0,"L", true);
        $pdf->Cell(85, 4, strtoupper($data['PengajuanCuti']['unit_kerja']), "TLR", 0,"L", true);
        $pdf->Cell(25, 4, 'MASA KERJA', "TL", 0,"L", true);
        $pdf->Cell(60, 4, strtoupper($data['PengajuanCuti']['masa_kerja']), "TLR", 1,"L", true);

        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //JENIS CUTI YANG DIAMBIL
        $CT = $CS = $CB = $CM = $CAP = $CTLN = "";
        if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CT') {$CT = 'V';}
        else if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CB') {$CB = 'V';}
        else if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CS') {$CS = 'V';}
        else if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CAP') {$CAP = 'V';}
        else if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CM') {$CM = 'V';}
        else if($data['PengajuanCuti']['kode_jenis_cuti'] == 'CTLN') {$CTLN = 'V';}

        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 4, 'II.', "TL", 0,"L", true);
        $pdf->Cell(187, 4, 'JENIS CUTI YANG DIAMBIL**', "TR", 1,"L", true);

        $pdf->Cell(75, 4, '1. CUTI TAHUNAN', "TLR", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 4, $CT, "TLR", 0,"C", true);
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(70, 4, '2. CUTI BESAR ', "TL", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 4, $CB, "TLR", 1,"C", true);

        $pdf->SetFont('Arial','','8');
        $pdf->Cell(75, 4, '3. CUTI SAKIT', "TLR", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 4, $CS, "TLR", 0,"C", true);
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(70, 4, '4. CUTI MELAHIRKAN', "TL", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 4, $CM, "TLR", 1,"C", true);

        $pdf->SetFont('Arial','','8');
        $pdf->Cell(75, 4, '5. CUTI KARENA ALASAN PENTING', "TLR", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 4, $CAP, "TLR", 0,"C", true);
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(70, 4, '6. CUTI DILUAR TANGGUNGAN NEGARA', "TL", 0,"L", true);
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(25, 4, $CTLN, "TLR", 1,"C", true);

        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //ALASAN CUTI
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 4, 'III.', "TL", 0,"L", true);
        $pdf->Cell(187, 4, 'ALASAN CUTI', "TR", 1,"L", true);
        $pdf->MultiCell(195, 6, strtoupper($data['PengajuanCuti']['alasan_cuti']), "TLR", 1,"L", true);
        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //LAMANYA CUTI
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 4, 'IV.', "TL", 0,"L", true);
        $pdf->Cell(187, 4, 'LAMANYA CUTI', "TR", 1,"L", true);
        $pdf->Cell(20, 4, 'Selama', "TLR", 0,"C", true);
        $pdf->Cell(45, 4, $data['PengajuanCuti']['jumlah_cuti'].'  (Hari/Bulan/Tahun)*', "TLR", 0,"C", true);
        $pdf->Cell(30, 4, 'Mulai Tanggal', "TLR", 0,"C", true);
        $pdf->Cell(45, 4, date('d F Y', strtotime($data['PengajuanCuti']['dari_tanggal'])), "TLR", 0,"C", true);
        $pdf->Cell(10, 4, 's/d', "TLR", 0,"C", true);
        $pdf->Cell(45, 4, date('d F Y', strtotime($data['PengajuanCuti']['sampai_tanggal'])), "TLR", 1,"C", true);
        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //CATATAN CUTI
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 4, 'V.', "TL", 0,"L", true);
        $pdf->Cell(187, 4, 'CATATAN CUTI***', "TR", 1,"L", true);
        $pdf->Cell(8, 4, '1.', "TL", 0,"L", true);
        $pdf->Cell(78, 4, 'CUTI TAHUNAN', "TR", 0,"L", true);
        $pdf->Cell(26, 4, 'PARAF', "TLR", 0,"C", true);
        $pdf->Cell(58, 4, '2. CUTI BESAR', "TLR", 0,"L", true);
        $pdf->Cell(25, 4, '', "TLR", 1,"C", true);
        
        $pdf->Cell(15, 4, 'TAHUN', "TLR", 0,"C", true);
        $pdf->Cell(15, 4, 'SISA', "TLR", 0,"C", true);
        $pdf->Cell(56, 4, 'KETERANGAN', "TLR", 0,"C", true);
        $pdf->Cell(26, 4, 'PETUGAS CUTI', "LR", 0,"C", true);
        $pdf->Cell(58, 4, '3. CUTI SAKIT', "TLR", 0,"L", true);
        $pdf->Cell(25, 4, '', "TLR", 1,"C", true);

        $year = date('Y');
        $year1 = $year - 1;
        $year2 = $year - 2;

        $pdf->Cell(15, 6, $year2, "TLR", 0,"C", true);
        $pdf->Cell(15, 6, '0', "TLR", 0,"C", true);
        $pdf->Cell(56, 6, '', "TLR", 0,"C", true);
        $pdf->Cell(26, 18,'', "TLR", 0,"C", true);
        $pdf->Cell(58, 6, '4. CUTI MELAHIRKAN', "TLR", 0,"L", true);
        $pdf->Cell(25, 6, '', "TLR", 1,"C", true);

        // $ket1 = $sisaTL-$cutiAmbil1;
        $pdf->Cell(15, 6, $year1, "TLR", 0,"C", true);
        $pdf->Cell(15, 6, $CTL, "TLR", 0,"C", true);
        $pdf->Cell(56, 6, $SCTL, "TLR", 0,"C", true);
        $pdf->Cell(26, 18, '', "", 0,"C", false);
        $pdf->Cell(58, 6, '5. CUTI KARENA ALASAN PENTING', "TLR", 0,"L", true);
        $pdf->Cell(25, 6, '', "TLR", 1,"C", true);

        // $ket2 = 12-$cutiAmbil2;
        $pdf->Cell(15, 6, $year, "TLR", 0,"C", true);
        $pdf->Cell(15, 6, '12', "TLR", 0,"C", true);
        $pdf->Cell(56, 6, $SCTI, "TLR", 0,"C", true);
        $pdf->Cell(26, 18, $pdf->image('img/'.$nip_PC.'ttd..png',101,141,16,16), "", 0,"C", false);
        $pdf->Cell(58, 6, '6. CUTI DI LUAR TANGGUNGAN NEGARA', "TLR", 0,"L", true);
        $pdf->Cell(25, 6, '', "TLR", 1,"C", true);
            
        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //ALAMAT MENJALANKAN CUTI
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 4, 'VI.', "TL", 0,"L", true);
        $pdf->Cell(187, 4, 'ALAMAT SELAMA MENJALANKAN CUTI', "TR", 1,"L", true);
        $pdf->MultiCell(98, 4, strtoupper($data['PengajuanCuti']['alamat_menjalankan_cuti']), "TLR", 1,"L", true);
        $pdf->SetY(166);
        $pdf->Cell(98, 4, '', "L", 0,"L", false);
        $pdf->Cell(35, 4, 'TELP', "TLR", 0,"C", true);
        $pdf->Cell(62, 4, '+62'.$data['PengajuanCuti']['hp'], "TLR", 1,"C", true);
        $pdf->Cell(98, 4, '', "L", 0,"L", false);
        $pdf->Cell(97, 4, 'HORMAT SAYA', "TLR", 1,"C", true);
        $pdf->Cell(98, 18, '', "L", 0,"L", false);
        $pdf->Cell(97, 18, $pdf->image('img/'.$data['PengajuanCuti']['nip'].'ttd..png',148,175,16,16), "TLR", 1,"C", false);
        $pdf->Cell(98, 4, '', "L", 0,"L", false);
        $pdf->Cell(97, 4, strtoupper($data['PengajuanCuti']['nama']), "LR", 1,"C", true);
        $pdf->Cell(98, 4, '', "L", 0,"L", false);
        $pdf->Cell(97, 4, 'NIP. '.$data['PengajuanCuti']['nip'], "LR", 1,"C", true);
        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //PERTIMBANGAN ATASAN LANGSUNG
        $ST=$DT=$DTG="";
        if($data['PengajuanCuti']['status_apr_atasan']== 'DISETUJUI'){
            $ST = 'V';
        }
        else if($data['PengajuanCuti']['status_apr_atasan']== 'DITOLAK'){
            $DT = 'V';
        }
        else if($data['PengajuanCuti']['status_apr_atasan']== 'DITANGGUHKAN'){
            $DTG = 'V';
        }
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 4, 'VII.', "TL", 0,"L", true);
        $pdf->Cell(187, 4, 'PERTIMBANGAN ATASAN LANGSUNG**', "TR", 1,"L", true);
        $pdf->Cell(30, 4, 'DISETUJUI', "TLR", 0,"C", true);
        $pdf->Cell(34, 4, 'PERUBAHAN****', "TLR", 0,"C", true);
        $pdf->Cell(34, 4, 'DITANGGUHKAN****', "TLR", 0,"C", true);
        $pdf->Cell(97, 4, 'TIDAK DISETUJUI****', "TLR", 1,"C", true);
        $pdf->Cell(30, 4, $ST, "TLR", 0,"C", true);
        $pdf->Cell(34, 4, '', "TLR", 0,"C", true);
        $pdf->Cell(34, 4, $DTG, "TLR", 0,"C", true);
        $pdf->Cell(97, 4, $DT, "TLR", 1,"C", true);
        $pdf->MultiCell(98, 4, strtoupper($data['PengajuanCuti']['alasan_setuju_atasan']), "TLR", 1,"L", true);
        $pdf->SetY(216);
        $pdf->Cell(98, 4, '', "L", 0,"L", false);
        $pdf->Cell(97, 4, 'ATASAN LANGSUNG', "TLR", 1,"C", true);
        $pdf->Cell(98, 18, '', "L", 0,"L", false);
        $pdf->Cell(97, 18, $pdf->image('img/'.$data['PengajuanCuti']['nip_atasan'].'ttd..png',148,222,16,16), "TLR", 1,"C", false);
        $pdf->Cell(98, 4, '', "L", 0,"L", false);
        $pdf->Cell(97, 4, strtoupper($data['PengajuanCuti']['nama_atasan']), "LR", 1,"C", true);
        $pdf->Cell(98, 4, '', "L", 0,"L", false);
        $pdf->Cell(97, 4, 'NIP. '.$data['PengajuanCuti']['nip_atasan'], "LR", 1,"C", true);
        $pdf->Cell(195, 4, '', "T", 1,"L", true);

        //PERTIMBANGAN PEJABAT YANG BERWENANG
        $ST=$DT=$DTG="";
        if($data['PengajuanCuti']['status_apr_ppk']== 'DISETUJUI'){
            $ST = 'V';
        }
        else if($data['PengajuanCuti']['status_apr_ppk']== 'DITOLAK'){
            $DT = 'V';
        }
        else if($data['PengajuanCuti']['status_apr_ppk']== 'DITANGGUHKAN'){
            $DTG = 'V';
        }
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(8, 4, 'VIII.', "TL", 0,"L", true);
        $pdf->Cell(187, 4, 'KEPUTUSAN PEJABAT YANG BERWENANG MEMBERI CUTI**', "TR", 1,"L", true);
        $pdf->Cell(30, 4, 'DISETUJUI', "TLR", 0,"C", true);
        $pdf->Cell(34, 4, 'PERUBAHAN****', "TLR", 0,"C", true);
        $pdf->Cell(34, 4, 'DITANGGUHKAN****', "TLR", 0,"C", true);
        $pdf->Cell(97, 4, 'TIDAK DISETUJUI****', "TLR", 1,"C", true);
        $pdf->Cell(30, 4, $ST, "TLR", 0,"C", true);
        $pdf->Cell(34, 4, '', "TLR", 0,"C", true);
        $pdf->Cell(34, 4, $DTG, "TLR", 0,"C", true);
        $pdf->Cell(97, 4, $DT, "TLR", 1,"C", true);
        $pdf->MultiCell(98, 4, strtoupper($data['PengajuanCuti']['alasan_setuju_ppk']), "TLR", 1,"L", true);
        $pdf->SetY(262);
        $pdf->Cell(98, 4, '', "L", 0,"L", false);
        $pdf->Cell(97, 4, 'PEJABAT YANG BERWENANG', "TLR", 1,"C", true);
        $pdf->Cell(98, 18, '', "L", 0,"L", false);
        $pdf->Cell(97, 18, $pdf->image('img/'.$data['PengajuanCuti']['nip_ppk'].'ttd..png',148,268,16,16), "TLR", 1,"C", false);
        $pdf->Cell(98, 4, '', "L", 0,"L", false);
        $pdf->Cell(97, 4, strtoupper($data['PengajuanCuti']['nama_ppk']), "LR", 1,"C", true);
        $pdf->Cell(98, 4, '', "L", 0,"L", false);
        $pdf->Cell(97, 4, 'NIP. '.$data['PengajuanCuti']['nip_ppk'], "LR", 1,"C", true);
        $pdf->Cell(195, 1, '', "T", 1,"L", true);
        $pdf->Ln(4);

        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(35, 4, 'Catatan  :', "", 1,"L", true);
        $pdf->SetFont('Arial','','8');
        $pdf->Cell(10, 4, '*', "", 0,"L", true);
        $pdf->Cell(185, 4, 'Coret yang tidak perlu', "", 1,"L", true);
        $pdf->Cell(10, 4, '**', "", 0,"L", true);
        $pdf->Cell(185, 4, 'Pilih salah satu dengan memberi tanda centang (V)', "", 1,"L", true);
        $pdf->Cell(10, 4, '***', "", 0,"L", true);
        $pdf->Cell(185, 4, 'Diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti', "", 1,"L", true);
        $pdf->Cell(10, 4, '****', "", 0,"L", true);
        $pdf->Cell(185, 4, 'Diberi tanda centang dan alasannya', "", 1,"L", true);


		$pdf->Output('formulir_cuti.pdf','I');

		ob_end_flush();
		exit;

?>