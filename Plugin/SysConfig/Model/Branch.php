<?php
App::uses('AppModel', 'Model');
class Branch extends AppModel {
	public $name = 'Branch';
	var $virtualFields = array(
   'description' => 'CONCAT(Branch.inisial, " - ", Branch.branch_name)'
	);
	public $validate = array(
		'id' => array(
			'notempty' => array('rule' => array('notempty'),'message' => 'Kode Branch tidak boleh kosong','allowEmpty' => false),
			'isExisting'=>array('rule'=>'isExisting', 'message'=>'Kode Branch sudah ada dalam database', 'on'=>'create')
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Nama Branch tidak boleh kosong',
				'allowEmpty' => false,				
			),
		),
		'inisial' => array(
			'isUnique' => array('rule' => array('isUnique'),'message' => 'Inisial Branch sudah dipakai/tidak boleh kosong','allowEmpty' => false, 'on'=>'create')
		),		
		'company_id'=>array(
			'pilihCompany'=>array('rule'=>'pilihCompany', 'message'=>'Silahkan pilih Perusahaan yang valid terlebih dahulu.')
		)
	);

	public function pilihCompany($check){
		return ($this->Company->field('id',"id IN ('{$check['company_id']}')"))?true:false;
		
	}
	
	public $actsAs=array('GenerateDelete');
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $belongsTo = array(
		'Company' => array(
			'className' => 'SysConfig.Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		// 'Warehouse' => array(
		// 	'className' => 'MasterFile.Warehouse',
		// 	'foreignKey' => 'warehouse_id',
		// 	'conditions' => '',
		// 	'fields' => '',
		// 	'order' => ''
		// )
	);
	
	public $hasAndBelongsToMany = array(
		'Region' => array(
			'className' => 'SysConfig.Region',
			'joinTable' => 'branches_regions',
			'foreignKey' => 'branch_id',
			'associationForeignKey' => 'region_id',
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
			'joinTable' => 'branches_users',
			'foreignKey' => 'branch_id',
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
