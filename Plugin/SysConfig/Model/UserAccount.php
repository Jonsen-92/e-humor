<?php
App::uses('AppModel', 'Model');
class UserAccount extends AppModel {
	public $name = 'UserAccount';
	public $useTable='users';
	public $actsAs=array('GenerateDelete');

	// public $validate = array(
	// 	'name' => array(
	// 		'notempty' => array(
	// 			'rule' => array('notempty'),
	// 			'message' => 'Nama Pengguna tidak boleh kosong',
	// 			'allowEmpty' => false,
	// 		),
	// 	),
	// 	'username' => array(
	// 		'notempty'=>array('rule'=>'notEmpty', 'message'=>'Kode Pekerjaan tidak boleh kosong'),
	// 		'isExisting'=>array('rule'=>'isExisting', 'message'=>'Username sudah ada dalam database'
	// 		),
	// 	),
	// 	'password' => array(
	// 		'notempty' => array(
	// 			'rule' => array('notempty'),
	// 			'message' => 'Password tidak boleh kosong',
	// 			'allowEmpty' => false,
	// 			//'required' => false,
	// 			//'last' => false, // Stop validation after this rule
	// 			//'on' => 'create', // Limit validation to 'create' or 'update' operations
	// 		),
	// 	),
	// 	'email' => array(
	// 		'email' => array(
	// 			'rule' => array('email'),
	// 			'message' => 'Email tidak valid',
	// 			'allowEmpty' => false,
	// 			//'required' => false,
	// 			//'last' => false, // Stop validation after this rule
	// 			//'on' => 'create', // Limit validation to 'create' or 'update' operations
	// 		),
	// 	),
	// 	'is_actived' => array(
	// 		'boolean' => array(
	// 			'rule' => array('boolean'),
	// 			//'message' => 'Your custom message here',
	// 			//'allowEmpty' => false,
	// 			//'required' => false,
	// 			//'last' => false, // Stop validation after this rule
	// 			//'on' => 'create', // Limit validation to 'create' or 'update' operations
	// 		),
	// 	),
	// 	'is_login' => array(
	// 		'boolean' => array(
	// 			'rule' => array('boolean'),
	// 			//'message' => 'Your custom message here',
	// 			//'allowEmpty' => false,
	// 			//'required' => false,
	// 			//'last' => false, // Stop validation after this rule
	// 			//'on' => 'create', // Limit validation to 'create' or 'update' operations
	// 		),
	// 	)
	// );
	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
			'joinTable' => 'branches_users',
			'foreignKey' => 'user_id',
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
		'Region' => array(
			'className' => 'SysConfig.Region',
			'joinTable' => 'regions_users',
			'foreignKey' => 'user_id',
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
		'CashBank' => array(
			'className' => 'CashBank',
			'joinTable' => 'cash_banks_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'cash_bank_id',
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
		'Salesman' => array(
			'className' => 'MasterFile.Salesman',
			'joinTable' => 'salesmen_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'salesman_id',
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
		'GroupAccount' => array(
			'className' => 'SysConfig.GroupAccount',
			'joinTable' => 'groups_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'group_id',
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

	// 'Divisi'
	// public function getUserAccountByDivisi($kodeDivisi = null)
	// {
	// 	if(empty($kodeDivisi)) return false;
	//
	// 	$UserAccount = $this->find('all', array(
  //       'joins' => array(
  //            array( 'className' => 'MasterFile.Divisi',
	//							 'table' => 'MasterFile.divisis',
  //               'alias' => 'Divisi',
  //               'type' => 'INNER',
  //               'conditions' => array(
  //                   'Divisi.kode_divisi' => $kodeDivisi,
  //                   'Divisi.kode_divisi = UserAccount.kode_divisi'
  //               )
  //           )
  //       )
  //   ));
  //   return $UserAccount;
	// }

}
