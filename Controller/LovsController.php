<?php
App::uses('Controller', 'Controller');
class LovsController extends Controller{
	var $name='Lovs';
	var $uses=array();
    public $components=array('Master','General','Session');

	function lovKendaraan(){
		$this->loadModel('StockKendaraan');
		$kode = $this->Session->read('Auth.User.kode_divisi');
		$this->passedArgs['plabel']='Daftar Kendaraan';
		$aField=$aFilter=$options=$cond=array();
		$aField=array('merek'=>array('label'=>'Merek'),'type_barang'=>array('label'=>'Tipe Kendaraan'),'no_polisi'=>array('label'=>'Nomor Polisi')
		);
		$aFilter=$aField;
		$cond=array();
		$cond=array('divisi'=> ' = "'.$kode.'"');   
		$options['recursive']=-1;
		$options['limit']=10;
		$options['order']='nama_barang ASC';
		$this->General->__indexlov('StockKendaraan', $aField,$aFilter,$cond, $options);
	}

	function lovBengkel(){
		$this->loadModel('Bengkel');
		$this->passedArgs['plabel']='Daftar Bengkel';
		$aField=$aFilter=$options=$cond=array();
		$aField=array('nama_bkl'=>array('label'=>'Nama Bengkel'),'alamat_detail'=>array('label'=>'Alamat'),
		'type'=>array('label'=>'Tipe Bengkel')
		);
		$aFilter=$aField;
		$cond=array('type'=> ' = "Internal"');
		// $cond=array();
		$options['recursive']=-1;
		$options['limit']=10;
		$options['order']='kode_bkl ASC';
		$this->General->__indexlov('Bengkel', $aField,$aFilter,$cond, $options);
	}
	
	function lovBengkel1(){
		$this->loadModel('Bengkel');
		$this->passedArgs['plabel']='Daftar Bengkel';
		$aField=$aFilter=$options=$cond=array();
		$aField=array('nama_bkl'=>array('label'=>'Nama Bengkel'),'alamat_detail'=>array('label'=>'Alamat'),
		'type'=>array('label'=>'Tipe Bengkel')
		);
		$aFilter=$aField;
		$cond=array('type'=>  'like "%Eksternal%"');
		// $cond=array();
		$options['recursive']=-1;
		$options['limit']=10;
		$options['order']='kode_bkl ASC';
		$this->General->__indexlov('Bengkel', $aField,$aFilter,$cond, $options);
	}


	function lovJasa(){
		$this->loadModel('JasaService');
		$this->passedArgs['plabel']='Daftar Jasa';
		$aField=$aFilter=$options=$cond=array();
		$aField=array('nama_jasa'=>array('label'=>'Nama Jasa'),'nilai_jasa'=>array('label'=>'Nilai Jasa')
		);
		$aFilter=$aField;
		$cond=array();
		// $cond=array();
		$options['recursive']=-1;
		$options['limit']=10;
		$options['order']='nama_jasa ASC';
		$this->General->__indexlov('JasaService', $aField,$aFilter,$cond, $options);
	}
	

};
