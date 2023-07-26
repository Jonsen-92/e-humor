<?php
App::uses('AppController', 'Controller');
class FileCategoriesController extends AppController {

	public $name = 'FileCategories';
	var $model='FileCategory';
	var $components=array('Master','General');

	function index() {
        $data['url']=array('add','edit','delete');
        $data['field']=array('title');
		//$this->General=$this->Components->load('General');
        $this->General->__indexTree($this->model, $data);
	}

	function add() {
		$model=$this->model;
		if ($this->request->is('post') and empty($this->request->data[$model]['parent_id'])){
			unset($this->request->data[$model]['parent_id']);
		}		
		$this->Master->__add($model);
		
		$this->helpers[]='Master';
		$parents=array(null=>'-- ROOT MENU --');
		$parents+=$this->$model->generateTreeList(null,null,null,'---');

		$data['field']=array();
		$data['field'][]=array('parent_id');
		$data['field'][]=array('title');
		$this->set(compact('data','model','parents'));
		$this->render('/Elements/Masters/form');
	}

	function edit($id = null) {
		$model=$this->model;
		if (($this->request->is('post') or $this->request->is('put')) and empty($this->request->data[$model]['parent_id'])){
			$this->request->data[$model]['parent_id']= 0;
		}
		$this->Master->__edit($model, $id, null, 0, 'index');

		$this->helpers[]='Master';
		$parents=array(null=>'-- ROOT MENU --');
		$parents+=$this->$model->generateTreeList(null,null,null,'---');

		$data['field']=array();
		$data['field'][]=array('parent_id');
		$data['field'][]=array('title');
		$this->set(compact('data','model','parents'));
		$this->render('/Elements/Masters/form');
	}

	function delete($id = null) {
		$this->Master->__delete($this->model, $id);
	}
}
