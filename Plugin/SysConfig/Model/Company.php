<?php
App::uses('AppModel', 'Model');
class Company extends AppModel {
	public $name = 'Company';
	public $validate = array(
		'company_code' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Kode Perusahaan tidak boleh kosong',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isExisting'=>array('rule'=>'isExisting', 'message'=>'Kode Perusahaan sudah ada dalam database')
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Nama Perusahaan tidak boleh kosong',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'address' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Alamat perusahaan tidak boleh kosong',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'npwp' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'NPWP tidak boleh kosong',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'phone_num' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'No Tlp perusahaan tidak boleh kosong',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	public $actsAs=array('GenerateDelete');
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $hasMany = array(
		'Branch' => array(
			'className' => 'SysConfig.Branch',
			'foreignKey' => 'company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Region' => array(
			'className' => 'SysConfig.Region',
			'foreignKey' => 'company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
