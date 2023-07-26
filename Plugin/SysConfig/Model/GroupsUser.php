<?php
App::uses('AppModel', 'Model');
class GroupsUser extends AppModel {
	public $name = 'GroupsUser';
	public $useTable='groups_users';
	public $actsAs=array('GenerateDelete');

}
