<?php
App::uses('AppModel', 'Model');
class Installation extends AppModel {
	public $name = 'Installation';
	public $useTable=false;
	public $validate = array(
		'username_administrator'=>array(
			'minLength' => array('rule' => array('minLength',8),'message' => 'Username minimal 8 karakter'),
		),
		'password_administrator'=>array(
			'minLength' => array('rule' => array('minLength',8),'message' => 'Password minimal 8 karakter'),
		),
		'email_administrator'=>array(
			'email' => array('rule' => array('email',true),'message' => 'Silahkan isi Email Administrator yang valid'),
		),
		'nama_perusahaan'=>array(
			'notempty' => array('rule' => array('notempty'),'message' => 'Nama Perusahaan tidak boleh kosong'),
		),
		'alamat_perusahaan'=>array(
			'notempty' => array('rule' => array('notempty'),'message' => 'Alamat Perusahaan tidak boleh kosong'),
		),
		'kota_perusahaan'=>array(
			'notempty' => array('rule' => array('notempty'),'message' => 'Kota Perusahaan tidak boleh kosong'),
		),
		'telp_perusahaan'=>array(
			'notempty' => array('rule' => array('notempty'),'message' => 'Telp Perusahaan tidak boleh kosong'),
		),
		'nama_aplikasi'=>array(
			'notempty' => array('rule' => array('notempty'),'message' => 'Nama Aplikasi tidak boleh kosong'),
		),
		'email_helpdesk'=>array(
			'email' => array('rule' => array('email', true),'message' => 'Silahkan isi Email Helpdesk yang valid.'),
		),
		'multi_branch'=>array(
			'inList' => array('rule' => array('inList', array('YES','NO')), 'message' => 'Silahkan pilih Multi Branch.'),
		)
	);
}
