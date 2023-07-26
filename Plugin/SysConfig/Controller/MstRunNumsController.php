<?php
App::uses('AppController', 'Controller');
class MstRunNumsController extends AppController {

	var $name = 'MstRunNums';
	var $uses=array('SysConfig.MstRunNum');
	var $components=array('Master');

	function index() {
		$data['filter']=array(
			'val_id'=>array('type'=>'text'),
			'val_char'
		);
		$data['field']=array(
			'val_id',
			'val_char',
			'val_value'
		);
        $data['title_header']='Running Number';
		$this->paginate=array('order'=>'val_id');
		$data['url']=array('edit');
		$this->Master->__index('MstRunNum', $data);
	}
	
	function edit($id = null) {
		$this->helpers[]='Master';

        $this->Master->__edit('MstRunNum', $id, 'Running Number');
		$data['field']=array();
		$data['field'][]=array('val_id'=>array('readonly'=>true, 'type'=>'text'));
		$data['field'][]=array('val_char'=>array('readonly'=>true));
		$data['field'][]=array('val_value');
		$data['title_header']='Running Number';
        $model='MstRunNum';
		$button='EDIT';
		$this->set('menuTitle', 'Running Number');
        $this->set(compact('model','data','button'));
		$this->render('/Elements/Masters/form');
	}
}
