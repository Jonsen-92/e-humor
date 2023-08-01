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

		$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base;
		$dataUser = $this->Session->read('Auth.User');
		$nip = $this->Session->read('Auth.User.nip');
		$jenisCuti[''] = 'Pilih Jenis Cuti';
		$dataAtasan[''] = 'Pilih Atasan Langsung';
		$jenisCuti += $this->JenisCuti->find('list', array('fields'=>array('kode_cuti','nama_cuti')));
		$dataAtasan += $this->Pegawai->find('list', array('fields'=>array('nip','nama'),'conditions'=>array('nip NOT IN ("'.$nip.'")'),'order'=>'nama ASC'));
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

			$SS = $this->request->data[$model]['surat_sakit'];
            $ss_name = $nomor.'surat_sakit'.substr($SS['name'], -4);
            $path = WWW_ROOT . 'files' . DS;

			if(is_uploaded_file($SS['tmp_name'])){
				move_uploaded_file($SS['tmp_name'], $path.$ss_name);
			}
			
            $this->request->data[$model]['surat_sakit'] = $ss_name;
			// pr($this->request->data);exit;
			// $this->send('1 pengajuan cuti');
			$this->Master->__add('PengajuanCuti', 'PengajuanCuti', 'index');
		}
		
		$this->set(compact('model','dataUser','jenisCuti','masaKerja','telp','dataAtasan','url'));

	}

	function edit($id)
	{
		$model = $this->model;
		$this->loadModel('JenisCuti');
		$this->loadModel('Pegawai');
		$this->loadModel('Proses.DataCutiPegawai');
        $this->helpers[] = 'General';

		$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base;
        $userid = $this->Session->read('Auth.User.id');
		$jenisCuti[''] = 'Pilih Jenis Cuti';
		$dataAtasan[''] = 'Pilih Atasan Langsung';
		$jenisCuti += $this->JenisCuti->find('list', array('fields'=>array('kode_cuti','nama_cuti')));
		$dataAtasan += $this->Pegawai->find('list', array('fields'=>array('nip','nama')));
		$data = $this->$model->find('first', array('conditions' => array($model.'.id' => $id)));

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->request->data[$model]['modi_by'] = $userid;
                $this->request->data[$model]['modified'] = date('Y-m-d H:i:s');
				
				$this->request->data[$model]['nama_atasan'] = $this->Pegawai->field('nama', 'nip IN ("'.$this->request->data[$model]['nip_atasan'].'")');
				$this->request->data[$model]['nama_ppk'] = $this->Pegawai->field('nama', 'nip IN ("'.$this->request->data[$model]['nip_ppk'].'")');
				$this->request->data[$model]['dari_tanggal'] = date('Y-m-d', strtotime($this->request->data[$model]['dari_tanggal']));
				$this->request->data[$model]['sampai_tanggal'] = date('Y-m-d', strtotime($this->request->data[$model]['sampai_tanggal']));

				$SS = $this->request->data[$model]['surat_sakit'];
				$ss_name = $data[$model]['nomor_pengajuan'].'surat_sakit'.substr($SS['name'], -4);
				$path = WWW_ROOT . 'files' . DS;

				     //cek input file untuk SIUP
				if(is_uploaded_file($SS['tmp_name'])){
					//cek file di db
					if(is_file($path.$ss_name)){
						//hapus file di db
						// foreach ($siup_files as $sf) {
							// pr($sf);
							unlink($path.$ss_name);
						// }
						// exit;
						// unlink($path.$siup_name);
					}
					//move file input ke db
					move_uploaded_file($SS['tmp_name'], $path.$ss_name);
				}
				$this->request->data[$model]['surat_sakit'] = $ss_name;
                // pr($this->request->data); exit;
                $this->Master->__edit('PengajuanCuti', $id, 'PengajuanCuti', '', 'index');
            }

		$data['PengajuanCuti']['dari_tanggal'] = date('m/d/Y', strtotime($data['PengajuanCuti']['dari_tanggal']));
		$data['PengajuanCuti']['sampai_tanggal'] = date('m/d/Y', strtotime($data['PengajuanCuti']['sampai_tanggal']));
		$data['PengajuanCuti']['sisa_cuti'] = $this->DataCutiPegawai->field('sisa_cuti_tahun_berjalan', 'nip IN ("'.$data['PengajuanCuti']['nip'].'")');
		$telp = $this->Pegawai->field('no_hp_wa', 'nip IN ("'.$data['PengajuanCuti']['nip'].'")');
        $this->set(compact('model', 'data','jenisCuti','telp','dataAtasan','url'));
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
		$PC = $this->UserAccount->query('SELECT nip,status_jabatan FROM users UA JOIN groups_users GU ON UA.id = GU.user_id
																		WHERE GU.group_id = 55 AND status_jabatan = "Plh" ');
		if(!$PC){
			$PC = $this->UserAccount->query('SELECT nip,status_jabatan FROM users UA JOIN groups_users GU ON UA.id = GU.user_id
			WHERE GU.group_id = 55');
		}
		$nip_PC = $PC[0]['UA']['nip'];
		
		$SCTL = $CTL = 0;
		$CTI = $SCTI = 12;
		if($dataSisaCuti){
			$CSD = $dataSisaCuti['DataCutiPegawai']['jumlah_cuti_sudah_diambil'];
			$CTL = $dataSisaCuti['DataCutiPegawai']['sisa_cuti_tahun_lalu'];
	
			if($CSD >= $CTL){
				$SCTI = $CTI + $CTL - $CSD; 
			}
			else{
				$SCTL = $CTL - $CSD;
			}
		}
		
// pr($SCTL);pr($SCTI);exit;
		$this->set(compact('data','SCTL','SCTI','CTL','nip_PC'));
	}

	function delete($id = null){
        $model = $this->model;
        $this->Master->__delete($this->model, $id);
    }

}