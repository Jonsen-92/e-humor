<?php
App::uses('AppController', 'Controller');
class IzinTidakMasukKerjasController extends AppController {

	public $name = 'IzinTidakMasukKerjas';
	var $model='IzinTidakMasukKerja';
	public $uses=array('SysConfig.UserAccount');
	var $components=array('Master','General');

	function  beforeFilter()
    {
		parent::beforeFilter();
        $this->set('menuTitle', 'Izin Tidak Masuk Kerja');
    }

    function index()
	{
		$this->helpers[] = 'General';
		$model = $this->model;
        $this->loadModel('IzinTidakMasukKerja');
		$conditions = array();
		$kondisi1 = $this->__filteringSearch($this->Session->read('Auth.Filter'));
        $nip = $this->Session->read('Auth.User.nip');

		if (isset($kondisi1) and !empty($kondisi1)) $conditions[] = $kondisi1;
			unset($kondisi1); 

		$conditions[]=' nip_pemohon = "'.$nip.'"';
		$data = $this->paginate('IzinTidakMasukKerja', implode(' AND ', $conditions), array(), 'nip_pemohon asc');
		$server = $_SERVER['SERVER_NAME'];
		$base = substr($this->base, 1);

		$this->set(compact('data', 'server', 'base','model','nip'));
    }

    function add()

    {

        $model = $this->model;
		$this->loadModel('IzinTidakMasukKerja');
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
			$this->request->data[$model]['tanggal_mulai'] = date('Y-m-d', strtotime($this->request->data[$model]['tanggal_mulai']));
			$this->request->data[$model]['tanggal_akhir'] = date('Y-m-d', strtotime($this->request->data[$model]['tanggal_akhir']));
			// pr($this->request->data);exit;
			$this->Master->__add('IzinTidakMasukKerja', 'IzinTidakMasukKerja', 'index');
		}
        $this->set(compact('model','dataUser','url','dataAtasan'));

    }

    function edit($id)
	{
		$model = $this->model;
		$this->loadModel('Pegawai');
		$this->loadModel('IzinTidakMasukKerja');
        $this->helpers[] = 'General';

		$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base;
        $userid = $this->Session->read('Auth.User.id');
		$dataAtasan[''] = 'Pilih Atasan Langsung';
		$dataAtasan += $this->Pegawai->find('list', array('fields'=>array('nip','nama')));
		$data = $this->IzinTidakMasukKerja->find('first', array('conditions' => array('IzinTidakMasukKerja.id' => $id)));

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->request->data[$model]['modi_by'] = $userid;
                $this->request->data[$model]['modified'] = date('Y-m-d H:i:s');
                $this->request->data[$model]['tanggal_mulai'] = date('Y-m-d', strtotime($this->request->data[$model]['tanggal_mulai']));
                $this->request->data[$model]['tanggal_akhir'] = date('Y-m-d', strtotime($this->request->data[$model]['tanggal_akhir']));

                // pr($this->request->data); exit;
                $this->Master->__edit('IzinTidakMasukKerja', $id, 'IzinTidakMasukKerja', '', 'index');
            }

		$data['IzinTidakMasukKerja']['tanggal_mulai'] = date('d-m-Y', strtotime($data['IzinTidakMasukKerja']['tanggal_mulai']));
        $data['IzinTidakMasukKerja']['tanggal_akhir'] = date('d-m-Y', strtotime($data['IzinTidakMasukKerja']['tanggal_akhir']));
        $this->set(compact('model', 'data','dataAtasan','url'));
	}

    function view($id = null){
        $model=$this->model;
		$this->loadModel('IzinTidakMasukKerja');
		$nip = $this->Session->read('Auth.User.nip');
        $data['field'] = array(
            'nip_pemohon'=>array('label'=>'N I P Pemohon :'),
            'nama_pemohon'=>array('label'=>'Nama Pemohon :'),
            'tanggal_mulai'=>array('label'=>'Dari Jam :'),
            'tanggal_akhir'=>array('label'=>'Sampai Jam :'),
            'nama_pemberi_izin'=>array('label'=>'Atasan :'),
            'jabatan_pemberi_izin'=>array('label'=>'Jabatan Atasan :'),
            'keterangan'=>array('label'=>'Keperluan :')
        );

        $data['conditions']= "IzinTidakMasukKerja.nip_pemohon IN ('{$nip}')";
        $data['title_header']='Izin Tidak Masuk Kerja';
        $this->Master->__view($model, $id, $data,$data['conditions']);
    }

    function delete($id = null){
		$this->loadModel('IzinTidakMasukKerja');
        $this->Master->__delete('IzinTidakMasukKerja', $id);
    }

    function report($id = null)
	{
        $model = $this->model;
		$this->loadModel('IzinTidakMasukKerja');

		$data = $this->IzinTidakMasukKerja->find('first', array('conditions' => array($model.'.id' => $id)));

        $this->set(compact('data','model'));
    }
}