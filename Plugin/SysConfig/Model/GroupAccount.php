<?php
App::uses('AppModel', 'Model');
class GroupAccount extends AppModel {
	public $name = 'GroupAccount';
	public $useTable = 'groups';
	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'title group tidak boleh kosong',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $hasAndBelongsToMany = array(
		'UserAccount' => array(
			'className' => 'SysConfig.UserAccount',
			'joinTable' => 'groups_users',
			'foreignKey' => 'group_id',
			'associationForeignKey' => 'user_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	public $actsAs = array('Acl' => array('type' => 'requester'), 'GenerateDelete');
 
	public function parentNode() {
		return null;
	}

}
