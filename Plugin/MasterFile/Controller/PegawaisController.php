<?php
App::uses('AppController', 'Controller');
class PegawaisController extends AppController
{

    var $name = 'Pegawais';
    var $model = 'Pegawai';
    var $uses = array('MasterFile.Pegawai');
    var $components = array('MasterDetail','General','Master');

    function  beforeFilter()
    {
		parent::beforeFilter();
        $this->set('menuTitle', 'Data Pegawai');
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

		$test = $this->paginate($model, implode(' AND ', $conditions), array(), 'nip asc');
		$server = $_SERVER['SERVER_NAME'];
		$base = substr($this->base, 1);

		$this->set(compact('test', 'server', 'base','model','nip'));
	}



    function add()
    {
        $model = $this->model;
		$this->helpers[] = 'General';
        $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base;

        $pangkat = array(''=>'Pilih Pangkat/Golongan',
                         'PPNPN'=>'PPNPN',
                         'Juru Muda (Ia)'=>'Juru Muda (Ia)',
                         'Juru Muda Tingkat I (Ib)'=>'Juru Muda Tingkat I (Ib)',
                         'Juru (Ic)'=>'Juru (Ic)',
                         'Juru Tingkat I (Id)'=>'Juru Tingkat I (Ia)',
                         'Pengatur Muda (IIa)'=>'Pengatur Muda (IIa)',
                         'Pengatur Muda Tingkat I (IIb)'=>'Pengatur Muda Tingkat I (IIb)',
                         'Pengatur (IIc)'=>'Pengatur (IIc)',
                         'Pengatur Tingkat I (IId)'=>'Pengatur Tingkat I (IId)',
                         'Penata Muda (IIIa)'=>'Penata Muda (IIIa)',
                         'Penata Muda Tingkat I (IIIb)'=>'Penata Muda Tingkat I (IIIb)',
                         'Penata (IIIc)'=>'Penata (IIIc)',
                         'Penata Tingkat I (IIId)'=>'Penata Tingkat I (IIId)',
                         'Pembina (IVa)'=>'Pembina (IVa)',
                         'Pembina Tingkat I (IVb)'=>'Pembina Tingkat I (IVb)',
                         'Pembina Muda (IVc)'=>'Pembina Muda (IVc)',
                         'Pembina madya (IVd)'=>'Pembina madya (IVd)',
                         'Pembina utama (IVe)'=>'Pembina utama (IVe)');

		$userid = $this->Session->read('Auth.User.id');
		if ($this->request->is('post') || $this->request->is('put')){
			$this->request->data[$model]['create_by'] = $userid;
            $this->request->data[$model]['tmt_cpns'] = date('Y-m-d', strtotime($this->request->data[$model]['tmt_cpns']));
            
            $nohp =  $this->request->data[$model]['no_hp_wa'];
            $ttd = $this->request->data['Pegawai']['ttd'];
            $ttd_name = $this->request->data[$model]['nip'].'ttd.'.substr($ttd['name'], -4);
            $path = WWW_ROOT . 'img' . DS;

            if(!preg_match("/[^+0-9]/",trim($nohp))){
                // cek apakah no hp karakter ke 1 dan 2 adalah angka 62
                if(substr(trim($nohp), 0, 2)=="62"){
                    $hp    =trim($nohp);
                }
                // cek apakah no hp karakter ke 1 adalah angka 0
                else if(substr(trim($nohp), 0, 1)=="0"){
                    $hp    ="62".substr(trim($nohp), 1);
                }
            }

            if(is_uploaded_file($ttd['tmp_name'])){
				move_uploaded_file($ttd['tmp_name'], $path.$ttd_name);
			}
            $this->request->data[$model]['no_hp_wa'] = $hp;
            $this->request->data[$model]['ttd'] = $ttd_name;

			// pr($this->request->data);exit;
			$this->Master->__add('Pegawai', 'Pegawai', 'index');

		}

		$this->set(compact('model','pangkat','url'));
    }



	function edit($id)
	{
		$model = $this->model;
        $this->helpers[] = 'General';
        $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base;

        $pangkat = array(''=>'Pilih Pangkat/Golongan',
                         'PPNPN'=>'PPNPN',
                         'Juru Muda (Ia)'=>'Juru Muda (Ia)',
                         'Juru Muda Tingkat I (Ib)'=>'Juru Muda Tingkat I (Ib)',
                         'Juru (Ic)'=>'Juru (Ic)',
                         'Juru Tingkat I (Id)'=>'Juru Tingkat I (Ia)',
                         'Pengatur Muda (IIa)'=>'Pengatur Muda (IIa)',
                         'Pengatur Muda Tingkat I (IIb)'=>'Pengatur Muda Tingkat I (IIb)',
                         'Pengatur (IIc)'=>'Pengatur (IIc)',
                         'Pengatur Tingkat I (IId)'=>'Pengatur Tingkat I (IId)',
                         'Penata Muda (IIIa)'=>'Penata Muda (IIIa)',
                         'Penata Muda Tingkat I (IIIb)'=>'Penata Muda Tingkat I (IIIb)',
                         'Penata (IIIc)'=>'Penata (IIIc)',
                         'Penata Tingkat I (IIId)'=>'Penata Tingkat I (IIId)',
                         'Pembina (IVa)'=>'Pembina (IVa)',
                         'Pembina Tingkat I (IVb)'=>'Pembina Tingkat I (IVb)',
                         'Pembina Muda (IVc)'=>'Pembina Muda (IVc)',
                         'Pembina madya (IVd)'=>'Pembina madya (IVd)',
                         'Pembina utama (IVe)'=>'Pembina utama (IVe)');

        $userid = $this->Session->read('Auth.User.id');

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->request->data[$model]['modi_by'] = $userid;
                $this->request->data[$model]['tmt_cpns'] = date('Y-m-d', strtotime($this->request->data[$model]['tmt_cpns']));

                $nohp =  $this->request->data[$model]['no_hp_wa'];
                $ttd = $this->request->data['Pegawai']['ttd'];
                $ttd_name = $this->request->data[$model]['nip'].'ttd.'.substr($ttd['name'], -4);
                $path = WWW_ROOT . 'img' . DS;

                if(!preg_match("/[^+0-9]/",trim($nohp))){
                    // cek apakah no hp karakter ke 1 dan 2 adalah angka 62
                    if(substr(trim($nohp), 0, 2)=="62"){
                        $hp    =trim($nohp);
                    }
                    // cek apakah no hp karakter ke 1 adalah angka 0
                    else if(substr(trim($nohp), 0, 1)=="0"){
                        $hp    ="62".substr(trim($nohp), 1);
                    }
                }

                //cek input file untuk SIUP
			if(is_uploaded_file($ttd['tmp_name'])){
				//cek file di db
				if(is_file($path.$ttd_name)){
					//hapus file di db
					// foreach ($siup_files as $sf) {
						// pr($sf);
						unlink($path.$ttd_name);
					// }
					// exit;
					// unlink($path.$siup_name);
				}
				//move file input ke db
                move_uploaded_file($ttd['tmp_name'], $path.$ttd_name);
			}

                $this->request->data[$model]['no_hp_wa'] = $hp;
                $this->request->data[$model]['ttd'] = $ttd_name;
                // pr($this->request->data); exit;
                $this->Master->__edit('Pegawai', $id, 'Pegawai', '', 'index');
            }

        $data = $this->$model->find('first', array('conditions' => array('Pegawai.id' => $id)));
        $this->set(compact('model', 'data','pangkat','url'));
	}

	function view($id = null){
        $model=$this->model;
        $data['field'] = array(
            'nip'=>array('label'=>'N I P :'),
            'nama'=>array('label'=>'Nama Pegawai :'),
            'jabatan'=>array('label'=>'Jabatan :'),
            'pangkat_golongan'=>array('label'=>'Pangkat/Golongan :'),
            'tmt_cpns'=>array('label'=>'TMT CPNS :'),
            'unit_kerja'=>array('label'=>'Instansi :'),
            'email'=>array('label'=>'Email :'),
            'no_hp_wa'=>array('label'=>'No. HP (WA) :'),
            'status_pegawai'=>array('label'=>'Status Pegawai :'),
        );

        $data['conditions']="";
        $data['title_header']='Data Pegawai';
        $this->Master->__view($model, $id, $data,$data['conditions']);

    }

    function delete($id = null){
        $model = $this->model;
        $this->Master->__delete($this->model, $id);
    }

	function getNip($nip)
	{
		$this->layout=false;
		$this->autoRender=false;
		$model = $this->model;
		$nip = $this->$model->field('nip', 'nip IN ("'.$nip.'")');
		echo json_encode($nip);
	}
}