<?php
App::uses('AppModel', 'Model');

class Divisi extends AppModel
{
  public $name = 'Divisi';
  public $actsAs = array('GenerateDelete');
  public $useTable = 'divisis';

  public $hasMany = array(
    'DivisiDetail' => array(
      'className' => 'MasterFile.DivisiDetail',
      'foreignKey' => 'divisi_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
  );
}
?>
