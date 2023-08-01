<?php

App::uses('AppController', 'Controller');

class IzinKeluarKantorsController extends AppController {



	public $name = 'IzinKeluarKantors';

	var $model='IzinKeluarKantor';

	public $uses=array('SysConfig.UserAccount');

	var $components=array('Master','General');



	function  beforeFilter()

    {

		parent::beforeFilter();

        $this->set('menuTitle', 'Izin Keluar Kantor');

    }



    function index()

	{

		$this->helpers[] = 'General';

		$model = $this->model;

        $this->loadModel('IzinKeluarKantor');

		$conditions = array();

		$kondisi1 = $this->__filteringSearch($this->Session->read('Auth.Filter'));

        $nip = $this->Session->read('Auth.User.nip');

		

		if (isset($kondisi1) and !empty($kondisi1)) $conditions[] = $kondisi1;

			unset($kondisi1);

            

		$conditions[]=' nip_pemohon = "'.$nip.'"';

		$data = $this->paginate('IzinKeluarKantor', implode(' AND ', $conditions), array(), 'nip_pemohon asc');

		$server = $_SERVER['SERVER_NAME'];

		$base = substr($this->base, 1);

		$this->set(compact('data', 'server', 'base','model','nip'));

	

    }



    function add()

    {

        $model = $this->model;
		$this->loadModel('IzinKeluarKantor');
		$this->loadModel('Pegawai');
		$this->helpers[] = 'General';

        $nip = $this->Session->read('Auth.User.nip');
		$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base;
        $dataUser = $this->Session->read('Auth.User');
        $dataAtasan[''] = 'Pilih Atasan';
		$dataAtasan += $this->Pegawai->find('list', array('fields'=>array('nip','nama'),'conditions'=>array('nip NOT IN ("'.$nip.'") AND jenis_pegawai NOT IN ("PPNPN")'),'order'=>'nama ASC'));

		if ($this->request->is('post') || $this->request->is('put')){
			$nomor = $this->General->__sinchronizeID('', 0, 3, array('val_id', 'val_char', 'val_value'));
			$this->request->data[$model]['nomor_izin'] = $nomor;
			$this->request->data[$model]['tanggal_izin'] = date('Y-m-d', strtotime($this->request->data[$model]['tanggal_izin']));
			// pr($this->request->data);exit;
			$this->Master->__add('IzinKeluarKantor', 'IzinKeluarKantor', 'index');
		}
        $this->set(compact('model','dataUser','url','dataAtasan'));

    }

	function edit($id)
	{
		$model = $this->model;
		$this->loadModel('Pegawai');
		$this->loadModel('IzinKeluarKantor');
        $this->helpers[] = 'General';

		$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base;
        $userid = $this->Session->read('Auth.User.id');
		$dataAtasan[''] = 'Pilih Atasan Langsung';
		$dataAtasan += $this->Pegawai->find('list', array('fields'=>array('nip','nama')));
		$data = $this->IzinKeluarKantor->find('first', array('conditions' => array('IzinKeluarKantor.id' => $id)));

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->request->data[$model]['modi_by'] = $userid;
                $this->request->data[$model]['modified'] = date('Y-m-d H:i:s');
				$this->request->data[$model]['tanggal_izin'] = date('Y-m-d', strtotime($this->request->data[$model]['tanggal_izin']));

                // pr($this->request->data); exit;
                $this->Master->__edit('IzinKeluarKantor', $id, 'IzinKeluarKantor', '', 'index');
            }

		$data['IzinKeluarKantor']['tanggal_izin'] = date('m/d/Y', strtotime($data['IzinKeluarKantor']['tanggal_izin']));
        $this->set(compact('model', 'data','dataAtasan','url'));
	}

	function view($id = null){
        $model=$this->model;
		$this->loadModel('IzinKeluarKantor');
		$nip = $this->Session->read('Auth.User.nip');
        $data['field'] = array(
            'nip_pemohon'=>array('label'=>'N I P Pemohon :'),
            'nama_pemohon'=>array('label'=>'Nama Pemohon :'),
            'tanggal_izin'=>array('label'=>'Tanggal Izin :'),
            'jam_mulai'=>array('label'=>'Dari Jam :'),
            'jam_akhir'=>array('label'=>'Sampai Jam :'),
            'nama_pemberi_izin'=>array('label'=>'Atasan :'),
            'jabatan_pemberi_izin'=>array('label'=>'Jabatan Atasan :'),
            'keterangan'=>array('label'=>'Keperluan :')
        );

        $data['conditions']= "IzinKeluarKantor.nip_pemohon IN ('{$nip}')";
        $data['title_header']='Izin Keluar Kantor';
        $this->Master->__view($model, $id, $data,$data['conditions']);
    }

	function getDataAtasan($nip)
	{
		$this->layout=false;
		$this->autoRender=false;
		$this->loadModel('MasterFile.Pegawai');

		$data = $this->Pegawai->find('first', array('conditions' => array('Pegawai.nip' => $nip)));
		echo json_encode($data);
	}

	function delete($id = null){
		$this->loadModel('IzinKeluarKantor');
        $this->Master->__delete('IzinKeluarKantor', $id);
    }

	function report($id = null)
	{
		$model = $this->model;
		$this->loadModel('IzinKeluarKantor');

		$data = $this->IzinKeluarKantor->find('first', array('conditions' => array('IzinKeluarKantor.id' => $id)));
		$this->set(compact('data'));
	}

}