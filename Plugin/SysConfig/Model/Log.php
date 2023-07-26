<?php
App::uses('AppModel', 'Model');
class Log extends AppModel {
	public $name = 'Log';
	public $displayField = 'id';
	public $validate = array(
		'aco_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_change_data' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $belongsTo = array(
		'Controller' => array(
			'className' => 'Aco',
			'foreignKey' => 'parent_aco_id',
			'conditions' => '',
			'fields' => array('alias'),
			'order' => ''
		),
		'Action' => array(
			'className' => 'Aco',
			'foreignKey' => 'aco_id',
			'conditions' => '',
			'fields' => array('alias'),
			'order' => ''
		),
		'UserAccount' => array(
			'className' => 'SysConfig.UserAccount',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => 'name',
			'order' => ''
		)
	);
}
