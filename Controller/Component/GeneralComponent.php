<?php
App::uses('Component', 'Controller');
App::uses('MstRunNum', 'SysConfig.Model');
class GeneralComponent extends Component{
	var $controller;
	var $components=array('Session','RequestHandler');
	var $MstRunNum;

	public function startup(Controller $controller){
		$this->controller=$controller;
	}

	/**
	 * To get generate number
	 */
	function __generateID($val_id, $val_char, $format, $return=array('val_char', 'val_value')){
	    $this->MstRunNum = new MstRunNum();
	    $val=$this->MstRunNum->find('first', array('conditions'=>"val_id IN ('$val_id') and val_char IN ('$val_char')"));
	    $result='';
	    if (isset($val['MstRunNum']['val_value'])) {
	        $val_value=sprintf('%0'.$format.'d',1+$val['MstRunNum']['val_value']);
	    }
	    else $val_value=sprintf('%0'.$format.'d',1);

	    foreach($return as $v){
	        $result.=$$v;
	    }
	    return $result;
	}

	/**
	 * To create generate ID and save to database
	 */
	function __sinchronizeID($val_id, $val_char, $format, $return=array('val_char', 'val_value')){

		//$this->controller->loadModel('MstRunNum');
		$this->MstRunNum = new MstRunNum();
	    $this->MstRunNum->id=null;
		$val=$this->MstRunNum->find('first', array('conditions'=>"val_id IN ('$val_id') and val_char IN ('$val_char')"));


	    $result='';
	    if (isset($val['MstRunNum']['val_value'])) {
	        $data['MstRunNum']['val_value']=1+$val['MstRunNum']['val_value'];
	        $this->MstRunNum->id=$val['MstRunNum']['id'];
	        $val_value=sprintf('%0'.$format.'d',1+$val['MstRunNum']['val_value']);
	    }
	    else {
		//	pr("test2");
		//	die;
	        $data['MstRunNum']['val_value']=1;
	        $data['MstRunNum']['val_id']=$val_id;
	        $data['MstRunNum']['val_char']=$val_char;
	        $val_value=sprintf('%0'.$format.'d',1);
		}

	    foreach($return as $v){
	        $result.=$$v;
	    }
	    return ($this->MstRunNum->save($data))?$result:0;
	}


	function __sinchronizeIDVCR($val_id, $val_char, $format, $return=array('val_char', 'val_value')){
		$this->controller->loadModel('FinActs.MstRunNum');
	    $this->controller->MstRunNum->id=null;
	    $val=$this->controller->MstRunNum->find('first', array('conditions'=>"val_id IN ('$val_id') and val_char IN ('$val_char')"));
	    $result='';
	    if (isset($val['MstRunNum']['val_value'])) {
	        $this->controller->request->data['MstRunNum']['val_value']=1+$val['MstRunNum']['val_value'];
	        $this->controller->MstRunNum->id=$val['MstRunNum']['id'];
	        $val_value=sprintf('%0'.$format.'d',1+$val['MstRunNum']['val_value']);
	    }
	    else {
	        $this->controller->request->data['MstRunNum']['val_value']=1;
	        $this->controller->request->data['MstRunNum']['val_id']=$val_id;
	        $this->controller->request->data['MstRunNum']['val_char']=$val_char;
	        $val_value=sprintf('%0'.$format.'d',1);
	    }

	    foreach($return as $v){
	        $result.=$$v;
	    }
		return ($this->controller->MstRunNum->save($this->controller->request->data))?$result:0;
	}

	/**
	 * To sinchronize from currency format to integer
	 */
	function __sinchronizecurrency($v){
	    $patterns = array();
	    $patterns[0] = '/[a-zA-Z\._\$\*]/';
	    $patterns[1] ='/\,/';
	    $replacements = array();
	    $replacements[0] = '';
	    $replacements[1] = '.';
		$v=trim(preg_replace($patterns, $replacements, $v));
		$isPositif=(substr($v,0,1)=='-')?false:true;
		if (!$isPositif) $v=trim(substr($v,1));
		$v=doubleval($v);
	    return $isPositif?$v:-$v;
	}

	/**
     * To convert integer to word of money (in indonesia)
     */
    function __terbilang($_iData){
        $bilang=array('','satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan','sepuluh','sebelas');
        //if ($_iData==0) return 'nol';
        if ($_iData<12) return $bilang[$_iData];
        elseif ($_iData<20) return $bilang[($_iData-10)].' belas';
        elseif ($_iData<100) return $bilang[floor($_iData/10)].' puluh '.$bilang[$_iData%10];
        elseif ($_iData<200) return ' seratus '.$this->__terbilang($_iData%100);
        elseif ($_iData<1000) return $bilang[floor($_iData/100)].' ratus '.$this->__terbilang($_iData%100);
        elseif ($_iData<2000) return ' seribu '.$this->__terbilang($_iData%1000);
        elseif ($_iData<1000000) return $this->__terbilang(floor($_iData/1000)).' ribu '.$this->__terbilang($_iData%1000);
        elseif ($_iData<1000000000) return $this->__terbilang(floor($_iData/1000000)).' juta '.$this->__terbilang($_iData-(floor($_iData/1000000)*1000000));
        elseif ($_iData<1000000000000) return $this->__terbilang(floor($_iData/1000000000)).' milyar '.$this->__terbilang($_iData - (floor($_iData/1000000000)*1000000000));
        elseif ($_iData<1000000000000000) return $this->__terbilang(floor($_iData/1000000000000)).' trilyun '.$this->__terbilang($_iData - (floor($_iData/1000000000000)*1000000000000));
        else return 'error! tidak dapat diinput';
    }

	/**
     * To retrive master data in index tree view page
     */
    function __indexTree($_sModel, $aOpt=null, $options=array()){
		$this->controller->helpers[]='General';
        $aOpt['url']=isset($aOpt['url'])?$aOpt['url']:array('add','view','edit','delete');
        if (!isset($options['recursive']) or !in_array($options['recursive'], array(-1,0,1,2))) $options['recursive']=-1;
        if (!isset($aOpt['displayField'])) $aOpt['displayField']=$this->controller->$_sModel->displayField;

        // Setting Kondisi Pencarian
        $conditions=array();
        if (isset($options['conditions']) and !empty($options['conditions'])) {
            if (is_array($options['conditions'])) $conditions[]=implode (' AND ', $options['conditions']);
            else $conditions[]=$options['conditions'];
        }
        $options['conditions']=implode(' AND ', $conditions);

		$this->controller->set($this->controller->name, $this->controller->$_sModel->find('threaded', $options));
		$this->controller->set('aOpt', $aOpt);
        $this->controller->set('model', $_sModel);
        $this->controller->set('id', $this->controller->$_sModel->primaryKey);

		if (isset($aOpt['render'])) $this->controller->render($aOpt['render']);
		else $this->controller->render('/Elements/Masters/index_tree');
    }

	/**
     * To retrive data in index page of LOV
     */
    function __indexlov($_sModel, $_aField, $_aFilter=array(), $_aCond=array(), $_aOptions=array(), $_aGroup=array()){

		if (empty($_aFilter)) $_aFilter=$_aField;
		$this->controller->layout='lov';
		$this->controller->helpers[]='Js';

		// print_r($_aField);
		// print_r($_aCond);
		//die;
		$this->controller->set('model', $_sModel);
		$this->controller->set('aField', $_aField);
		$this->controller->set('aFilter', $_aFilter);
        $this->controller->set('cond', $_aCond);
		$this->controller->set('options', $_aOptions);
		$this->controller->set('aGroup', $_aGroup);

		$this->controller->render('/Elements/lov');

    }

    /**
     * To retrive data in ajax index page of LOV
     */
    function __indexajaxlov($_sModel, $_aField, $_aFilter, $conditions='', $_iRecursive=-1, $_iLimit=10, $_sOrder=null, $_aGroup=null){
		$this->controller->loadModel($_sModel);

		// $this->Model->setDatasource('datasource');
		if (is_null($_sOrder)) $_sOrder="{$_sModel}.{$this->controller->$_sModel->primaryKey} ASC";
		else $this->controller->passedArgs['order']=$_sOrder;
		$this->controller->layout='lov';
		$fields=$_aField;
		foreach($fields as $i=>$v)$fields[$i]=str_replace('__','.',$v);
		$this->controller->helpers[]='Js';
		$this->controller->helpers[]='General';
		$this->controller->set('model', $_sModel);
		$this->controller->set('aField', $_aField);
		$this->controller->set('aFilter', $_aFilter);
		$this->controller->set('aOrder', $_sOrder);
		//if($_sOrder!='') $this->passedArgs['order'] = $_sOrder;
		//echo $_sOrder;pr($this->passedArgs);
		//pr($_iRecursive);
		$this->controller->paginate=array('fields'=>$fields,'recursive'=>$_iRecursive, 'order'=>$_sOrder, 'limit'=>$_iLimit, 'group'=>$_aGroup);
		$dataku=$this->controller->paginate($_sModel, $conditions);//pr($dataku);
		// pr($this->controller->$_sModel->getDataSource()->getLog(false, false));die;
		$this->controller->set($_sModel, $dataku);
		$this->controller->render('/Elements/x_lov');
    }
}
