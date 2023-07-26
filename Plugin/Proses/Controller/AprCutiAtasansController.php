<?php
App::uses('AppController', 'Controller');
class AprCutiAtasansController extends AppController {

	public $name = 'AprCutiAtasans';
	var $model='PengajuanCuti';
	// public $uses=array('SysConfig.AprCutiAtasan');
	var $components=array('Master','General');

	function  beforeFilter()
    {
		parent::beforeFilter();
        $this->set('menuTitle', 'Approval Cuti by Atasan');
    }

    function index()
	{
        $this->helpers[] = 'General';
		$model = $this->model;
        $this->loadModel('PengajuanCuti');
		$conditions = array();
		$kondisi1 = $this->__filteringSearch($this->Session->read('Auth.Filter'));
        $nip = $this->Session->read('Auth.User.nip');

		if (isset($kondisi1) and !empty($kondisi1)) $conditions[] = $kondisi1;
			unset($kondisi1);  

        $conditions[]=' nip_atasan = "'.$nip.'"';
        $conditions[]=' status_validasi = "VALID"';

		$data = $this->paginate('PengajuanCuti', implode(' AND ', $conditions), array(), 'nip asc');
		$server = $_SERVER['SERVER_NAME'];
		$base = substr($this->base, 1);
		$this->set(compact('data', 'server', 'base','model','nip'));

    }

    function view($id = null){
        $model=$this->model;
        $this->loadModel('PengajuanCuti');
		$nip = $this->Session->read('Auth.User.nip');

        $data['field'] = array(
            'nip'=>array('label'=>'N I P :'),
            'nama'=>array('label'=>'Nama Pegawai :'),
            'jabatan'=>array('label'=>'Jabatan :'),
            'masa_kerja'=>array('label'=>'Masa Kerja :'),
            'unit_kerja'=>array('label'=>'Instansi :'),
            'hp'=>array('label'=>'No. HP (WA) :'),
            'desc_jenis_cuti'=>array('label'=>'Jenis Cuti Yang Diambil :'),
            'dari_tanggal'=>array('label'=>'Tanggal Mulai :'),
            'sampai_tanggal'=>array('label'=>'Tanggal Akhir :'),
            'jumlah_cuti'=>array('label'=>'Lama Cuti :'),
            'alasan_cuti'=>array('label'=>'Alasan Cuti :'),
            'alamat_menjalankan_cuti'=>array('label'=>'Alamat Selama Cuti :'),
            'status_validasi'=>array('label'=>'Status Validasi :'),
            'status_apr_atasan'=>array('label'=>'Approval Atasan :'),
            'status_apr_ppk'=>array('label'=>'Approval PPK :'),

        );

        $data['conditions']= ' nip_atasan = "'.$nip.'"';;
        $data['title_header']='Data Pengajuan Cuti Pegawai';
        $this->Master->__view('PengajuanCuti', $id, $data,$data['conditions']);

    }



    function approve($id, $nip)
    {
        $this->loadModel('PengajuanCuti');
        $this->loadModel('Pegawai');
        $this->loadModel('DataCutiPegawai');

        $data = $this->PengajuanCuti->find('first', array('conditions' => array('PengajuanCuti.id' => $id)));
        $nipAtasan = $data['PengajuanCuti']['nip_atasan'];
        $nipPpk = $data['PengajuanCuti']['nip_ppk'];

        $status['status_apr_atasan'] = "'DISETUJUI'";

        if($nipAtasan == $nipPpk){
            $dataCuti = $this->DataCutiPegawai->find('first', array('conditions'=>array('nip'=>$nip,'tahun'=>date('Y'))));

            $cutiSudahAmbil = $dataCuti['DataCutiPegawai']['jumlah_cuti_sudah_diambil'];
            $sisaCuti = $dataCuti['DataCutiPegawai']['sisa_cuti_tahun_berjalan'];
            $jumlahPengajuan = $data['PengajuanCuti']['jumlah_cuti'];
            $totalAmbil = $cutiSudahAmbil+$jumlahPengajuan;
            $totalSisa = $sisaCuti - $jumlahPengajuan;

            $dataCutiNew['jumlah_cuti_sudah_diambil'] = "'$totalAmbil'";
            $dataCutiNew['sisa_cuti_tahun_berjalan'] = "'$totalSisa'";
            $status['status_apr_ppk'] = "'DISETUJUI'";

            $this->DataCutiPegawai->updateAll($dataCutiNew, array('nip' => $nip,'tahun'=>date('Y')));
        }
// pr($status);exit;
        $this->PengajuanCuti->updateAll($status, array('id' => $id));
        $this->Session->setFlash('Pengajuan Cuti Berhasil di Setujui');
        $this->redirect('index');
    }

    function disapprove($id)
    {
        $this->loadModel('PengajuanCuti');
        $alasan = $this->request->data['PengajuanCuti']['alasan_tolak'];
        $status['status_apr_atasan'] = "'DITOLAK'";
        $status['alasan_setuju_atasan'] = "'$alasan'";

        $this->PengajuanCuti->updateAll($status, array('id' => $id));
        $this->Session->setFlash('Pengajuan Cuti Berhasil di Tolak');
        $this->redirect('index');
    }

}