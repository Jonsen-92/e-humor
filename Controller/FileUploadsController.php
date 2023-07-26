<?php
App::uses('AppController', 'Controller');
class FileUploadsController extends AppController{
	var $name='FileUploads';	
	var $model='FileUpload';
	var $components=array('Master');

	function index(){
		$data['field']=array(
			'file'=>array('type'=>'file'),
			'FileCategory.title'=>array('label'=>'File Category'),
			'title',
			'description'=>array('function'=>'wordcut')
		);
		$data['order']='FileUpload.id DESC';
		$this->Master->__index($this->model,$data);
	}

	function view($id = null) {
		$data['field']=array(
			'file'=>array('type'=>'file'),
			'FileCategory.title'=>array('label'=>'File Category'),
			'title',
			'description'=>array('function'=>'wordcut'),
			'created',
			'modified'
		);
		$this->Master->__view($this->model, $id, $data);
	}

	function add() {
		$model=$this->model;
		$aSaveFile['file']=array('folder'=>'files/uploads', 'parentFolder'=>'app');
		$this->Master->__add($model, null, null, false, $aSaveFile);
		$this->helpers[]='Master';
		$fileCategories=$this->$model->FileCategory->generateTreeList(null,null,null,'---');
		$data['field']=array();
		$data['field'][]=array('file_category_id');
		$data['field'][]=array('title');
		$data['field'][]=array('description');
		$data['field'][]=array('file'=>array('type'=>'file'));
		$data['option']['type']='file';
		$data['fckeditor']=array($model.'Description'=>array('fckWidth'=>'100%','fckHight'=>'400px'));

		$this->set(compact('data','model','fileCategories'));
		$this->render('/Elements/Masters/form');
	}
	
	function edit($id=null) {
		$model=$this->model;
		$aSaveFile['file']=array('folder'=>'files/uploads','parentFolder'=>'app');
		$this->Master->__edit($model,$id,null,0,'index',$aSaveFile);
		$this->helpers[]='Master';
		$fileCategories=$this->$model->FileCategory->generateTreeList(null,null,null,'---');
		$data['field']=array();
		$data['field'][]=array('file_category_id');
		$data['field'][]=array('title');
		$data['field'][]=array('description');
		$data['field'][]=array('new_file'=>array('type'=>'file'));
		$data['field'][]=array('cur_file'=>array('div'=>false, 'label'=>false, 'type'=>'hidden', 'default'=>$this->data[$model]['file']));
		$data['option']['type']='file';
		$data['fckeditor']=array($model.'Description'=>array('fckWidth'=>'100%','fckHight'=>'400px'));

		$this->set(compact('data','model','fileCategories'));
		$this->render('/Elements/Masters/form');
	}

	function delete($id=null){
		$aSaveFile['file']=array('folder'=>'files/uploads','parentFolder'=>'app');
		$this->Master->__delete($this->model, $id, null, null, false, $aSaveFile);
	}

	function download($id){
		$this->viewClass = 'Media';
		$file=$this->FileUpload->find('first', array('recursive'=>-1,'fields'=>array('file','title'),'conditions'=>"id IN ('$id')"));
		$params = array(
            'id'        => $file['FileUpload']['file'],
            'name'      => $file['FileUpload']['title'],
            'download'  => true,
            'extension' => substr($file['FileUpload']['file'],-3),
            'path'      => APP.'files/uploads/'
        );
        $this->set($params);
	}
}
