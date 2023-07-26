<?php

App::uses('Model', 'Model');

class GlobalHelper extends AppHelper {

	public function getColumnUsingId($column, $table, $field, $id){
		$model = new $table();
		$options['fields'] = array($column);
        $options['conditions'] = array($field => $id);
        $tables = $model->find('first', $options);
        return $tables[$table][$column];
	}

}
