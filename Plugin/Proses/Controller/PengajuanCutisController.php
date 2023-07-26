<?php
App::uses('AppController', 'Controller');
class PengajuanCutisController extends AppController {

	public $name = 'PengajuanCutis';
	var $model='PengajuanCuti';
	public $uses=array('SysConfig.PengajuanCuti');
	var $components=array('Master','General');

	function  beforeFilter()
    {
		parent::beforeFilter();
        $this->set('menuTitle', 'Pengajuan Cuti');
    }

    function index()
	{
		$this->helpers[] = 'General';
		$model = $this->model;
		$conditions = array();
		$kondisi1 = $this->__filteringSearch($this->Session->read('Auth.Filter'));
        $nip = $this->Session->read('Auth.User.nip');
		
		if (isset($kondisi1) and !empty($kondisi1)) $conditions[] = $kondisi1;
			unset($kondisi1);

		$conditions[]=' nip = "'.$nip.'"';
		$test = $this->paginate($model, implode(' AND ', $conditions), array(), 'nip asc');
		$server = $_SERVER['SERVER_NAME'];
		$base = substr($this->base, 1);
		$this->set(compact('test', 'server', 'base','model','nip'));
	}

	function add()
	{
		$model = $this->model;
		$this->loadModel('JenisCuti');
		$this->loadModel('Pegawai');
		$this->helpers[] = 'General';

		$dataUser = $this->Session->read('Auth.User');
		$nip = $this->Session->read('Auth.User.nip');
		$jenisCuti[''] = 'Pilih Jenis Cuti';
		$dataAtasan[''] = 'Pilih Atasan Langsung';
		$jenisCuti += $this->JenisCuti->find('list', array('fields'=>array('kode_cuti','nama_cuti')));
		$dataAtasan += $this->Pegawai->find('list', array('fields'=>array('nip','nama'),'conditions'=>array('nip NOT IN ("'.$nip.'")')));
		$tmtCpns = $this->Pegawai->field('tmt_cpns', 'nip IN ("'.$dataUser['nip'].'")');
		$now = date('Y-m-d');
		$a = new DateTime($tmtCpns);
		$b = new DateTime($now);
		$masaKerja = $b->diff($a);
		$telp = $this->Pegawai->field('no_hp_wa', 'nip IN ("'.$dataUser['nip'].'")');

		if ($this->request->is('post') || $this->request->is('put')){
			$nomor = $this->General->__sinchronizeID('', 0, 3, array('val_id', 'val_char', 'val_value'));
			$this->request->data[$model]['nama_atasan'] = $this->Pegawai->field('nama', 'nip IN ("'.$this->request->data[$model]['nip_atasan'].'")');
			$this->request->data[$model]['nama_ppk'] = $this->Pegawai->field('nama', 'nip IN ("'.$this->request->data[$model]['nip_ppk'].'")');
			$this->request->data[$model]['dari_tanggal'] = date('Y-m-d', strtotime($this->request->data[$model]['dari_tanggal']));
			$this->request->data[$model]['sampai_tanggal'] = date('Y-m-d', strtotime($this->request->data[$model]['sampai_tanggal']));
			$this->request->data[$model]['nomor_pengajuan'] = $nomor;
			$this->request->data[$model]['tanggal_pengajuan'] = date('Y-m-d');
			// pr($this->request->data);exit;
			$this->send('1 pengajuan cuti');
			$this->Master->__add('PengajuanCuti', 'PengajuanCuti', 'index');
		}
		
		$this->set(compact('model','dataUser','jenisCuti','masaKerja','telp','dataAtasan'));

	}

	function edit($id)
	{
		$model = $this->model;
		$this->loadModel('JenisCuti');
		$this->loadModel('Pegawai');
		$this->loadModel('Proses.DataCutiPegawai');
        $this->helpers[] = 'General';

        $userid = $this->Session->read('Auth.User.id');
		$jenisCuti[''] = 'Pilih Jenis Cuti';
		$dataAtasan[''] = 'Pilih Atasan Langsung';
		$jenisCuti += $this->JenisCuti->find('list', array('fields'=>array('kode_cuti','nama_cuti')));
		$dataAtasan += $this->Pegawai->find('list', array('fields'=>array('nip','nama')));
		
            if ($this->request->is('post') || $this->request->is('put')) {
                $this->request->data[$model]['modi_by'] = $userid;
                $this->request->data[$model]['modified'] = date('Y-m-d H:i:s');
				
				$this->request->data[$model]['nama_atasan'] = $this->Pegawai->field('nama', 'nip IN ("'.$this->request->data[$model]['nip_atasan'].'")');
				$this->request->data[$model]['nama_ppk'] = $this->Pegawai->field('nama', 'nip IN ("'.$this->request->data[$model]['nip_ppk'].'")');
				$this->request->data[$model]['dari_tanggal'] = date('Y-m-d', strtotime($this->request->data[$model]['dari_tanggal']));
				$this->request->data[$model]['sampai_tanggal'] = date('Y-m-d', strtotime($this->request->data[$model]['sampai_tanggal']));
                // pr($this->request->data); exit;
                $this->Master->__edit('PengajuanCuti', $id, 'PengajuanCuti', '', 'index');
            }

        $data = $this->$model->find('first', array('conditions' => array($model.'.id' => $id)));
		$data['PengajuanCuti']['dari_tanggal'] = date('m/d/Y', strtotime($data['PengajuanCuti']['dari_tanggal']));
		$data['PengajuanCuti']['sampai_tanggal'] = date('m/d/Y', strtotime($data['PengajuanCuti']['sampai_tanggal']));
		$data['PengajuanCuti']['sisa_cuti'] = $this->DataCutiPegawai->field('sisa_cuti_tahun_berjalan', 'nip IN ("'.$data['PengajuanCuti']['nip'].'")');
		$telp = $this->Pegawai->field('no_hp_wa', 'nip IN ("'.$data['PengajuanCuti']['nip'].'")');
        $this->set(compact('model', 'data','jenisCuti','telp','dataAtasan'));
	}

	function view($id = null){
        $model=$this->model;
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
            'nama_atasan'=>array('label'=>'Nama Atasan (Apr) :'),
            'nama_ppk'=>array('label'=>'Nama PPK (Apr) :'),
        );

        $data['conditions']= "PengajuanCuti.nip IN ('{$nip}')";
        $data['title_header']='Data Pengajuan Cuti Pegawai';
        $this->Master->__view($model, $id, $data,$data['conditions']);

    }

	function getSisaCuti($nip)
	{
		$this->layout=false;
		$this->autoRender=false;
		$this->loadModel('Proses.DataCutiPegawai');

		$sisaCuti = $this->DataCutiPegawai->field('sisa_cuti_tahun_berjalan', 'nip IN ("'.$nip.'")');
		echo json_encode($sisaCuti);
	}

	function getNamaCuti($kode)
	{
		$this->layout=false;
		$this->autoRender=false;
		$this->loadModel('MasterFile.JenisCuti');

		$desc = $this->JenisCuti->field('nama_cuti', 'kode_cuti IN ("'.$kode.'")');
		echo json_encode($desc);
	}

	function send($id)
	{
		// pr(ROOT);exit;
		require_once (ROOT.'/edaran/Vendor/autoload.php'); // if you use Composer
		// require_once('ultramsg.class.php'); // if you download ultramsg.class.php
    
		$token="u7gh5kvu8rzrttf0"; // Ultramsg.com token
		$instance_id="instance51828"; // Ultramsg.com instance id
		$client = new UltraMsg\WhatsAppApi($token,$instance_id);
			
		$to="+6282122707338"; 
		$body=$id; 
		$api=$client->sendChatMessage($to,$body);
		
	}

	function report($id = null)
	{
		$model = $this->model;
		$this->loadModel('Pegawai');
		$this->loadModel('UserAccount');
		$this->loadModel('Proses.DataCutiPegawai');

		$data = $this->$model->find('first', array('conditions' => array($model.'.id' => $id)));
		$data[$model]['golongan'] = $this->Pegawai->field('pangkat_golongan', 'nip IN ("'.$data[$model]['nip'].'")');
		$dataSisaCuti = $this->DataCutiPegawai->find('first', array('conditions' => array('DataCutiPegawai.nip' => $data[$model]['nip'])));

		$sisaTL = $dataSisaCuti['DataCutiPegawai']['sisa_cuti_tahun_lalu'];
		$cutiAmbil = $cutiAmbil1 = $data[$model]['jumlah_cuti'];
		
		$cutiAmbil2  = 0;
		if($cutiAmbil > $sisaTL){
			$cutiAmbil1 = $sisaTL;
			$cutiAmbil2 = $cutiAmbil - $sisaTL;
		}


		$this->set(compact('data','sisaTL','cutiAmbil1','cutiAmbil2'));
	}

	function delete($id = null){
        $model = $this->model;
        $this->Master->__delete($this->model, $id);
    }

}