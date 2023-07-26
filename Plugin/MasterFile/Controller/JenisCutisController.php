<?php

App::uses('AppController', 'Controller');

class JenisCutisController extends AppController

{

    var $name = 'JenisCutis';

    var $model = 'JenisCuti';

    var $uses = array('MasterFile.JenisCuti');

    var $components = array('MasterDetail','General','Master');



    function  beforeFilter()

    {

		parent::beforeFilter();

        $this->set('menuTitle', 'Data Jenis Cuti');

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

		$test = $this->paginate($model, implode(' AND ', $conditions), array(), 'kode_cuti asc');

		$server = $_SERVER['SERVER_NAME'];

		$base = substr($this->base, 1);

		$this->set(compact('test', 'server', 'base','model','nip'));

	}



    function add()

    {

        $model = $this->model;

		$this->helpers[] = 'General';


        $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base;
		$userid = $this->Session->read('Auth.User.id');

        $jenisCuti = array(''=>'Pilih Kode Cuti',

                           'CT'=>'CT',

                           'CB'=>'CB',

                           'CS'=>'CS',

                           'CM'=>'CM',

                           'CAP'=>'CAP',

                           'CTLN'=>'CTLN');



		if ($this->request->is('post') || $this->request->is('put')){

			$this->request->data[$model]['create_by'] = $userid;



			// pr($this->request->data);exit;

			$this->Master->__add('JenisCuti', 'JenisCuti', 'index');

		}



		$this->set(compact('model','jenisCuti','url'));

    }



    function edit($id)

	{

		$model = $this->model;

        $this->helpers[] = 'General';

        $userid = $this->Session->read('Auth.User.id');



            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data[$model]['modi_by'] = $userid;

                // pr($this->request->data); exit;

                $this->Master->__edit('JenisCuti', $id, 'JenisCuti', '', 'index');

            }



        $data = $this->$model->find('first', array('conditions' => array('JenisCuti.id' => $id)));

        $this->set(compact('model', 'data'));

	}



    function view($id = null){

        $model=$this->model;

        $data['field'] = array(

            'kode_cuti'=>array('label'=>'Kode Cuti :'),

            'nama_cuti'=>array('label'=>'Nama Cuti :'),

            'lama_hari'=>array('label'=>'Jumlah (hari) :')

        );

        

        $data['conditions']="";

        $data['title_header']='Jenis Cuti';

        $this->Master->__view($model, $id, $data,$data['conditions']);

    }



    function getCuti($kode)

	{

		$this->layout=false;

		$this->autoRender=false;

		$model = $this->model;

		$data = $this->$model->field('kode_cuti', 'kode_cuti IN ("'.$kode.'")');



		echo json_encode($data);

	}

}