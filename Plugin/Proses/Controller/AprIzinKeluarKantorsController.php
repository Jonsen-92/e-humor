<?php
App::uses('AppController', 'Controller');
class AprIzinKeluarKantorsController extends AppController {

	public $name = 'AprIzinKeluarKantors';
	var $model='IzinKeluarKantor';
	// public $uses=array('SysConfig.AprIzinKeluarKantor');
	var $components=array('Master','General');

	function  beforeFilter()
    {
		parent::beforeFilter();
        $this->set('menuTitle', 'Approval Izin Keluar Kantor');
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

        $conditions[]=' nip_pemberi_izin = "'.$nip.'"';

		$data = $this->paginate('IzinKeluarKantor', implode(' AND ', $conditions), array(), 'nip_pemohon asc');
		$server = $_SERVER['SERVER_NAME'];
		$base = substr($this->base, 1);
		$this->set(compact('data', 'server', 'base','model','nip'));

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

        $data['conditions']= "IzinKeluarKantor.nip_pemberi_izin IN ('{$nip}')";
        $data['title_header']='Izin Keluar Kantor';
        $this->Master->__view($model, $id, $data,$data['conditions']);
    }

    function approve($id)
    {
        $this->loadModel('IzinKeluarKantor');

        $date = date('Y-m-d');
        $status['apr_pemberi_izin'] = "'DISETUJUI'";
        $status['tanggal_persetujuan'] = "'$date'";

        $this->IzinKeluarKantor->updateAll($status, array('id' => $id));
        $this->Session->setFlash('Izin Keluar Kantor Berhasil di Setujui');
        $this->redirect('index');
    }


    function disapprove($id)
    {
        $this->loadModel('IzinKeluarKantor');
        $alasan = $this->request->data['IzinKeluarKantor']['alasan_tolak'];
        $status['apr_pemberi_izin'] = "'DITOLAK'";
        $status['alasan_apr'] = "'$alasan'";
// pr($status);exit;
        $this->IzinKeluarKantor->updateAll($status, array('id' => $id));
        $this->Session->setFlash('Izin Keluar Kantor Berhasil di Tolak');
        $this->redirect('index');
    }

}