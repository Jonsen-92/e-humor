<?php
App::uses('AppModel', 'Model');

class Pegawai extends AppModel {
	public $name='Pegawai';
	public $actsAs=array('GenerateDelete');
	// public $useDbConfig = 'default';
	public $useTable = 'pegawais';


}
?>
