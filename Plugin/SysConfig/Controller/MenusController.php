<?php
App::uses('AppController', 'Controller');
class MenusController extends AppController {

	public $name = 'Menus';
	var $uses=array('SysConfig.Menu');
	var $components=array('Master','General');

	function index() {
		// if ($this->Session->read('Auth.User.kode_group') != 77) {
		// 	echo '<script>window.history.back();</script>';
		// 	return false;
		// }

        $data['url']=array('add','edit','delete');
		// $opt = array('1'=>'Mobil', '2'=>'Motor', '3'=>'Mobil & Motor');
		$data['field']['title']=array('label'=>'Judul Menu');
		$data['field']['description']=array('label'=>'Keterangan');
		$data['field']['url']=array('label'=>'URL');
		// $data['field']['type_menu']=array('label'=>'Type Menu', 'options'=>$opt);
	    $options=array('order'=>array('description ASC'),'conditions'=>array('Menu.type_menu NOT IN (1)'));
        $this->General->__indexTree('Menu', $data, $options);

	}

	function add() {
		// if ($this->Session->read('Auth.User.kode_group') != 77) {
		// 	echo '<script>window.history.back();</script>';
		// 	return false;
		// }

		if ($this->request->is('post') and empty($this->request->data['Menu']['parent_id'])){
			unset($this->request->data['Menu']['parent_id']);
		}
		// pr($this->request->data);
		// exit;
		$this->Master->__add('Menu','Menu','index');

		$this->helpers[]='Master';
		$conditions='Menu.type_menu NOT IN (1)';
		$parents=array(null=>'-- ROOT MENU --');
		$parents+=$this->Menu->generateTreeList($conditions,null,null,'---');

		$data['field']=array();
		$data['field'][]=array('parent_id'=>array('type'=>'select', 'options'=>$parents));
		$data['field'][]=array('title'=>array('type'=>'text'));
		$data['field'][]=array('description'=>array('type'=>'text'));
		$data['field'][]=array('url'=>array('type'=>'text'));
		// $data['field'][]=array('type_menu'=>array('type'=>'select', 'options'=>array('1'=>'BU1','2'=>'BU2','3'=>'BU3','4'=>'BU4','5'=>'All BU')));

		// $data['field'][]=array('is_publish'=>array('type'=>'checkbox'));
		$data['field'][]=array('is_publish'=>array('type'=>'checkbox','label'=>'Is Publish?','boolean'=>'checkbox','style'=>'width:30px'));
		$model='Menu';
		$this->set(compact('data','model','parents'));
		$this->render('/Elements/Masters/form');
	}

	function edit($id = null) {
		// if ($this->Session->read('Auth.User.kode_group') != 77) {
		// 	echo '<script>window.history.back();</script>';
		// 	return false;
		// }

	    if (($this->request->is('post') or $this->request->is('put')) and empty($this->request->data['Menu']['parent_id'])){
			$this->request->data['Menu']['parent_id']= 0;
		}


		$this->helpers[]='Master';
		$conditions='Menu.type_menu NOT IN (1)';
		$parents=array(null=>'-- ROOT MENU --');
		$parents+=$this->Menu->generateTreeList($conditions,null,null,'---');

		$data['field']=array();
		$data['field'][]=array('parent_id'=>array('type'=>'select', 'options'=>$parents));
		$data['field'][]=array('title'=>array('type'=>'text'));
		$data['field'][]=array('description'=>array('type'=>'text'));
		$data['field'][]=array('url'=>array('type'=>'text'));
		// $data['field'][]=array('type_menu'=>array('type'=>'select', 'options'=>array('2'=>'Motor','3'=>'Mobil & Motor')));
		// $data['field'][]=array('type_menu'=>array('type'=>'select', 'options'=>array('1'=>'BU1','2'=>'BU2','3'=>'BU3','4'=>'BU4','5'=>'All BU')));
		$data['field'][]=array('is_publish'=>array('type'=>'checkbox'));
		$this->Master->__edit('Menu', $id, null, 1,'index');
		$model='Menu';
		$this->set(compact('data','model','parents'));
		$this->render('/Elements/Masters/form');
	}

	function delete($id = null) {
		if ($this->Session->read('Auth.User.kode_group') != 77) {
			echo '<script>window.history.back();</script>';
			return false;
		}

		$this->Master->__delete('Menu', $id);
	}
}
