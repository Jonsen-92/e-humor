<?php
App::uses('AppModel', 'Model');

class DivisiDetail extends AppModel {
    public $name='DivisiDetail';
	public $actsAs=array('GenerateDelete');
	// public $useDbConfig = 'default';
    public $useTable = 'divisi_details';

	public $belongsTo = array(
	'Divisi' => array(
		'className' => 'MasterFile.Divisi',
		'foreignKey' => 'divisi_id',
		'conditions' => '',
		'fields' => '',
		'order' => ''
	)
	);
}
?>