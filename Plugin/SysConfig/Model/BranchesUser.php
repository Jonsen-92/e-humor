<?php
App::uses('AppModel', 'Model');
class BranchesUser extends AppModel{
	public $name='BranchesUser';
	public $actsAs=array('GenerateDelete');	
}
