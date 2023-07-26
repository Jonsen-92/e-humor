<?php
App::uses('AppModel', 'Model');
class Group extends AppModel {
	public $name = 'Group';
	public $useTable='groups';
	public $actsAs=array('GenerateDelete');

}
