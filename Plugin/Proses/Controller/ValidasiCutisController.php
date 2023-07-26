<?php
App::uses('AppController', 'Controller');
class ValidasiCutisController extends AppController {

	public $name = 'ValidasiCutis';
	var $model='PengajuanCuti';
	// public $uses=array('SysConfig.ValidasiCuti');
	var $components=array('Master');

	function  beforeFilter()
    {
		parent::beforeFilter();
        $this->set('menuTitle', 'Validasi Cuti');
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

		$data = $this->paginate('PengajuanCuti', implode(' AND ', $conditions), array(), 'nip asc');
		$server = $_SERVER['SERVER_NAME'];
		$base = substr($this->base, 1);
		$this->set(compact('data', 'server', 'base','model','nip'));
	}

    function view($id = null){
        $model=$this->model;
        $this->loadModel('PengajuanCuti');
		// $nip = $this->Session->read('Auth.User.nip');
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

        $data['conditions']= "";
        $data['title_header']='Data Pengajuan Cuti Pegawai';
        $this->Master->__view('PengajuanCuti', $id, $data,$data['conditions']);

    }

    function valid($id)
    {
        $this->loadModel('PengajuanCuti');
        $status['status_validasi'] = "'VALID'";
        $this->PengajuanCuti->updateAll($status, array('id' => $id));
        $this->Session->setFlash('Pengajuan Cuti Berhasil di Validasi');
        $this->redirect('index');
    }

    function notvalid($id)
    {
        $this->loadModel('PengajuanCuti');
        $alasan = $this->request->data['PengajuanCuti']['alasan_validasi'];
        $status['status_validasi'] = "'TIDAK VALID'";
        $status['alasan_validasi'] = "'$alasan'";
        
        $this->PengajuanCuti->updateAll($status, array('id' => $id));
        $this->Session->setFlash('Pengajuan Cuti Berhasil di Tolak');
        $this->redirect('index');
    }
}