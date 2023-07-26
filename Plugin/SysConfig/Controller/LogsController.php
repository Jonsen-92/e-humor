<?php
App::uses('AppController', 'Controller');
class LogsController extends AppController {

	var $name = 'Logs';
	var $uses=array('SysConfig.Log');
	var $components=array('Master');

	function index() {
		$this->paginate=array('fields'=>array('Log.*','Action.alias','Controller.alias','UserAccount.name'), 'order'=>'Log.id DESC');
		
		$data['filter']=array(
			'UserAccount.name'=>array('label'=>'user'), 
			'Controller.alias'=>array('label'=>'modul'), 
			'Action.alias'=>array('label'=>'action'), 
			'created'=>array('type'=>'date')
		);
		
		$data['field']=array(
			'Controller.alias'=>array('label'=>'Modul'),
			'Action.alias'=>array('label'=>'action'), 
			'UserAccount.name'=>array('label'=>'user'),
			'is_change_data'=>array('label'=>'Change Data', 'YN'=>true ), 
			'created'
		);
		
		$data['url']=array();
			
		$this->Master->__index('Log', $data);
	}
}
