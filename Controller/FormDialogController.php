<?php
App::uses('AppController', 'Controller');
class FormDialogController extends AppController{
	var $name='FormDialog';
	var $components=array('Master');
	var $uses=array();

	function index(){
		$model='FormDialog';
		
		$this->helpers[]='Master';
		$data['field'][]=array('product_code'=>array('style'=>'width:100px;', 'readonly'=>true));
		$data['field'][]=array('name');
		$data['field'][]=array('kemasan');
		$data['field'][]=array('sale_price'=>array('label'=>'Harga'));
		$data['field'][]=array('fieldset'=>array(
									'legend'=>'Geografis',
									'Kelurahan',
									'Kecamatan',
									'kota'=>array('label'=>'Kota/Kabupaten', 'style'=>'width:100px;'),
									'Propinsi'							
						));

		$data['field'][]=array('fieldset'=>array(
									'legend'=>'Bahan',
									'__div1'=>array(
										'coloumn'=>2,
										'asal'=>array('label'=>'Bahan', 'style'=>'width:200px;'),
										'tekstur'=>array('style'=>'width:200px;'),
										'umur'=>array('style'=>'width:200px;'),
										'motif'=>array('style'=>'width:200px;'),
										'warna'=>array('style'=>'width:200px;')										
									)									
						));
		$data['field'][]=array('fieldset'=>array(
									'legend'=>'Design',
									'__div2'=>array(
										'coloumn'=>3,
										'vendor'=>array('style'=>'width:200px;'),
										'alamat_vendor'=>array('label'=>'Alamat', 'style'=>'width:200px;'),
										'designer'=>array('style'=>'width:200px;'),
										'tool'=>array('style'=>'width:200px;'),
										'lanscape'=>array('style'=>'width:200px;'),
										'warna'=>array('style'=>'width:200px;')
									)									
						));

		$this->set(compact('data','model'));
		$this->render('/Elements/Masters/form');
	}
}
?>
