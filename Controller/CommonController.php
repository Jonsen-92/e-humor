<?php
App::uses('Controller','Controller');
class CommonController extends Controller {
	var $layout=false;
	var $autoRender=false;
	var $uses=array();
	var $components=array('General');

	function beforeFilter(){
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
	}

	function autocomplete($model, $field, $kondisi=null){
		$keyword=$_POST['keyword'];
		if (isset($_POST['limit'])) $limit=$_POST['limit'];
		$this->loadModel($model);
		$options['conditions']=is_null($kondisi)?"$field LIKE '$keyword%'":$kondisi." AND $field LIKE '$keyword%'";
		$options['fields']=array($this->$model->primaryKey,$field);
		if (isset($limit)) $options['limit']=$limit;
		$temp=$this->$model->find('list', $options);
		if (!empty($temp)) foreach ($temp as $i=>$j) $data[]=array('id'=>$i, 'label'=>$j);
		//else $data[]=array('id'=>null, 'label'=>'no match data');

		// penghapusan sampah
		unset($temp); unset($options); unset($model); unset($field); unset($kondisi);
		echo json_encode($data);
	}

	function image($id, $model, $imageField, $typeField){
		$this->loadModel($model);
		$options=array();
		$options['conditions']="{$this->$model->primaryKey} IN ('$id')";
		$options['fields']=array($imageField, $typeField);

		$image=$this->$model->find('first', $options);
		header("Content-type: {$image[$model][$typeField]}");
		print $image[$model][$imageField];
	}

	function underconstruction($message=null){
		$this->layout='default';
		if (is_null($message)) $message='Halaman ini masih dalam tahap pengembangan';
		$this->set(compact('message'));
		$this->render('/elements/underconstruction');
	}

	function getoptionchild($model, $parentField, $parentId, $title=null){
		$this->loadModel($model);
		$options=$this->$model->find('list', array('conditions'=>"$model.$parentField IN ('$parentId')"));
		echo "<option value='0'>-- select ";
		echo is_null($title)?'option':$title;
		echo " --</option>";
		foreach($options as $i=>$v) echo "<option value='$i'>$v</option>";
	}

	function xlov(){
		if (isset($_POST['model'])) {
			//echo 2;
			//pr($_POST);exit;
			$this->passedArgs['model']=$_POST['model'];

			$this->passedArgs['recursive']=isset($_POST['recursive'])?$_POST['recursive']:-1;
			if (isset($_POST['order'])) $this->passedArgs['porder']=$_POST['order'];

			$this->passedArgs['limit']=isset($_POST['limit'])?$_POST['limit']:10;
			//pr($_POST);
			$aFilter=$aField=$cond=array();
			foreach ($_POST as $index=>$value){
				$prefix=substr($index,0,7);
				if ($prefix=='fixCond' or $prefix=='filCond') {
					if (is_string($value)){
						if ($value == 'LIKE "%%"' || $value == 'LIKE "%undefined%"')
							continue;
					}
					$index=substr($index,8);
					$index=str_replace('__','.',$index);
					$temp=explode('.',$index);
					if (isset($temp[1])) $temp=$index.' '.$value;
					else $temp=$this->passedArgs['model'].'.'.$index.' '.$value;
					$cond[]=$temp;
					unset($temp);
					$value=str_replace('"','____',$value);
					$value=str_replace('%','yyy',$value);
					$this->passedArgs[$prefix.'_'.$index]=$value;
				}
				elseif ($prefix=='sFilter') $this->passedArgs[$index]=$aFilter[$index]=$value;
				elseif ($prefix=='aField_') $this->passedArgs[$index]=$aField[$index]=$value;
				elseif ($prefix=='aGroup_') $this->passedArgs[$index]=$aGroup[$index]=$value;
				//pr( $this->passedArgs);

			}
		}
		else {
			//echo 1;exit;
			//pr($this->passedArgs);
			$aFilter=$aField=$cond=array();
			foreach ($this->passedArgs as $index=>$value){
				$prefix=substr($index,0,7);
				if ($prefix=='fixCond' or $prefix=='filCond') {
					$value=str_replace('____','"',$value);
					$value=str_replace('yyy','%',$value);
					$index=substr($index,8);
					$index=str_replace('__','.',$index);
					$temp=explode('.',$index);
					if (isset($temp[1])) $temp=$index.' '.$value;
					else $temp=$this->passedArgs['model'].'.'.$index.' '.$value;

					$cond[]=$temp;
					unset($temp);
				}
				elseif ($prefix=='sFilter') $aFilter[$index]=$value;
				elseif ($prefix=='aField_') $aField[$index]=$value;
			}
		}

		$condition=implode(' AND ', $cond);
		$condition=str_replace('"',"'",$condition);
		$order=isset($this->passedArgs['porder'])?$this->passedArgs['porder']:null;
		//pr($order);
		//pr($this->passedArgs);
		$this->General->__indexajaxlov($this->passedArgs['model'],$aField,$aFilter,$condition,$this->passedArgs['recursive'],$this->passedArgs['limit'],$order);
	}
}
?>
