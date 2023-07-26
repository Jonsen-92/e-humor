<?php
App::uses('AppController', 'Controller');
class DataCutiPegawaisController extends AppController
{
    var $name = 'DataCutiPegawais';
    var $model = 'DataCutiPegawai';
    var $uses = array('MasterFile.DataCutiPegawai');
    var $components = array('MasterDetail','General','Master');

    function  beforeFilter()
    {
		parent::beforeFilter();
        $this->set('menuTitle', 'Data Cuti Pegawai');
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
		//paginate = menampilkan isi dari halaman index

								//('nama model')
		$test = $this->paginate($model, implode(' AND ', $conditions), array(), 'nip asc');
		$server = $_SERVER['SERVER_NAME'];
		$base = substr($this->base, 1);

		$this->set(compact('test', 'server', 'base','model','nip'));

	}

    function add()
	{
		$model = $this->model;
		$this->loadModel('DataCutiPegawai');
		$this->helpers[] = 'General';

        $userid = $this->Session->read('Auth.User.id');
        if ($this->request->is('post') || $this->request->is('put')){
			$this->request->data[$model]['create_by'] = $userid;
			// pr($this->request->data);exit;
			$this->Master->__add('DataCutiPegawai', 'DataCutiPegawai', 'index');
		}

		$this->set(compact('model'));
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

	function edit($id)
	{
		$model = $this->model;
        $this->helpers[] = 'General';

        $userid = $this->Session->read('Auth.User.id');

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->request->data[$model]['modi_by'] = $userid;
                // pr($this->request->data); exit;
                $this->Master->__edit('DataCutiPegawai', $id, 'DataCutiPegawai', '', 'index');
            }

        $data = $this->$model->find('first', array('conditions' => array('DataCutiPegawai.id' => $id)));
        $this->set(compact('model', 'data'));
	}

    function lovNip(){
		$this->passedArgs['plabel']='List Pegawai';
	    $aField=$aFilter=$options=$cond=array();
		$aField=array('nip'=>array('label'=>'N I P'),
                      'nama'=>array('label'=>'N A M A')
		);
		$aFilter=$aField;
		// $cond=array('nip'=> ' = "'.$this->Session->read('Auth.User.nip').'" group by indikator');
		$options['recursive']=-1;
		$options['limit']=20;
		$options['order']='nip ASC';

		$this->General->__indexlov('Pegawai', $aField,$aFilter,$cond, $options);

	}

	function getData($nip, $tahun)
	{
		$this->layout=false;
		$this->autoRender=false;
		$this->loadModel('Proses.DataCutiPegawai');

		$data = $this->DataCutiPegawai->field('sisa_cuti_tahun_berjalan', 'nip IN ("'.$nip.'") AND tahun IN ("'.$tahun.'")');
		echo json_encode($data);
	}

}