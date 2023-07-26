<?php
	class GenerateDeleteBehavior extends ModelBehavior {
		public function toDelete(&$Model, $id){
			$error=0; $errorMessage='';
			
			foreach ($Model->hasMany as $i=>$v) $Model->hasMany[$i]['limit']=1;
			$curData=$Model->read(null, $id);
			
			if (isset($Model->hasOne)) {
				foreach ($Model->hasOne as $i=>$v){
					if(isset($curData[$i][$Model->$i->primaryKey])) {
						$error=1; 
						$errorMessage='Tidak bisa menghapus '. $Model->name.' karena sudah ada transaksi untuk '.$i;
						break;
					}
				}
			}
			if (!$error and isset($Model->hasMany)){
				foreach ($Model->hasMany as $i=>$v){
					if(isset($curData[$i][0][$Model->$i->primaryKey])) {
						$error=1; 
						$errorMessage='Tidak bisa menghapus '. $Model->name.' karena sudah ada transaksi untuk '.$i;
						break;
					}
				}
			}
			
			if (!$error and isset($Model->hasAndBelongsToMany)){
				foreach ($Model->hasAndBelongsToMany as $i=>$v){
					if (isset($curData[$i]) and !empty($curData[$i])) {
						if(isset($curData[$i][0][$Model->$i->primaryKey])) {
							$error=1; 
							$errorMessage='Tidak bisa menghapus '. $Model->name.' karena sudah ada transaksi untuk '.$i;
							break;
						}
					}
				}
			}
			return array($error,$errorMessage);
			
		}
	}
?>
