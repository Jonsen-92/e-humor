<?php
App::uses('AppController', 'Controller');
class WelcomesController extends AppController {

	public $name = 'Welcomes';
	// var $model='WelcomeAccount';
	// public $uses=array('SysConfig.WelcomeAccount');
	var $components=array('Master');

	public function welcome(){
        $this->loadModel('PengajuanCuti');
		$this->layout = false;
		$now = date('Y-m-d');

		$data = $this->PengajuanCuti->query("SELECT * FROM pengajuan_cutis WHERE dari_tanggal >= '$now' AND status_apr_ppk = 'DISETUJUI'");
		// pr($data);
		$this->set(compact('data'));
    }

}