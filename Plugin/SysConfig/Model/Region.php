<?php
App::uses('AppModel', 'Model');
class Region extends AppModel {
	public $name = 'Region';
	public $validate = array(
		'id' => array(
			'notempty' => array('rule' => array('notempty'),'message' => 'ID tidak boleh kosong','allowEmpty' => false),
			'isExisting'=>array('rule'=>'isExisting', 'message'=>'ID sudah ada dalam database')
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Keterangan tidak boleh kosong',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	
	public $actsAs=array('GenerateDelete');
	
	public $belongsTo = array(
		'Company' => array(
			'className' => 'SysConfig.Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasAndBelongsToMany = array(
		'Branch' => array(
			'className' => 'SysConfig.Branch',
			'joinTable' => 'branches_regions',
			'foreignKey' => 'region_id',
			'associationForeignKey' => 'branch_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'UserAccount' => array(
			'className' => 'SysConfig.UserAccount',
			'joinTable' => 'regions_users',
			'foreignKey' => 'region_id',
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

}
