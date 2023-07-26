<?php
App::uses('MasterHelper', 'View/Helper');
class MasterDetailHelper extends MasterHelper{
    
	var $helpers=array('Form');
	var $out;

        /*  original ------------------------------------------- */
	function getFormMasterDetail($data, $model, $view=false){
            $this->__getField($data['field'], $model, $awal=1, $view);
            $this->__getDetail($data, $model, $view);
            return $this->out;
	}
        
        /* untuk menu PenerimaanFisikAksesoris ------------------------------------------- */
        function getFormMasterDetailPFA($data, $model, $view=false){
            $this->__getField($data['field'], $model, $awal=1, $view);
            $this->__getDetailPFA($data, $model, $view);
            return $this->out;
	}
        
        function __getDetailPFA($data, $modelHeader, $view=false){
                
		//pr($data);		
		foreach ($data['detail'] as $model=>$v){
			$footerField= false;
			if (isset($v['footerField'])) {$footerField=$v['footerField']; unset($v['footerField']);}			
			if (isset($data['title_detail']))$this->out.='<h1>'.Inflector::humanize($data['title_detail']).'</h1>';
			else $this->out.='<h1>'.''.'</h1>';
                
                    //cetak table ---------------------------------------------------------------------------------
                        
			//$this->out.='<table'; 
                        if (isset($v['style'])) {$this->out.=''; unset($v['style']);}
                        //'.isset($v['class']) ? $v['class'] 
                        
                        $this->out.= '<div class="table-responsive">';
                                
                        $this->out.='<table class="table-bordered table-striped table-hover col-sm-12" id="'.$model.'" style=" overflow-x; auto; " ';
                        unset($v['class']);                        
			$this->out.='><tr>';
                        
			if (!$view) $html='<tr class=\"tr tr'.$model.'\" id=\"tr'.$model.'::row::\">';
			$totalField=sizeof($v);
                
                // LABEL th ------------------------------------------------------
                
                        //main foreach
                        
                        $counter = 0;
                        $arrTotal = count($v);
			foreach ($v as $in=>$val){
                                //echo " ".$val['label'];
				if (!$view) {
					$index=$in;
					$value=$val;
                                        
                                        $html.='<td>';
					if (is_integer($index)){
						$index=$value;
						$value=array('label'=>false);
					}
					else {
                                            //benerin array
                                            if(is_array($value)){
                                                $value['label']=false; 
                                            }
                                        }	
                                        
                                        $html.=str_replace('"', '\"', $this->__createInputFieldTable($model, $index, $value, 1, '::row::'));
                                        
                                        //tambahin button
                                        $counter++;
                                        // if($counter == $arrTotal && $val['label' == 'Qty']){
                                        //     $html.='<input style="margin-left:1em" type="button" onclick="getdetail" class="btn btn-primary btn-xs" value="Detail">';
                                        // }
                                        $html.='</td>';
				}
                                
                                if(is_array($value)){
                                    if(array_key_exists('lov', $value)){
                                        $classlov=' class="col-md-2" ';
                                    }else{
                                        $classlov='';
                                    }                                    
                                }
                                
                                
				$this->out.='<th';
                                $this->out.= $classlov;
				$this->out.='>';
				if (is_integer($in)) $this->out.=Inflector::humanize($val);
				elseif (isset($val['label']) and !is_array($val['label'])) $this->out.=$val['label'];
				elseif (isset($val['label']['value'])) $this->out.=$val['label']['value'];
				else $this->out.=Inflector::humanize($in);
				$this->out.='</th>';   
                                
			}
                        
			if (!$view) {

				$html.='<td style="width:20px;"><input type="button"  value="-" id="delrow'.$model.'::row::" class="btn btn-danger pull-right" style="width:20px;" onclick=" ';
				if (isset($data['beforeDeleteScript'])) $html.=$data['beforeDeleteScript']; 
				$html.='deleteRow(this.id);';
				if (isset($data['afterDeleteScript'])) $html.=$data['afterDeleteScript'];
				$html.='" rel="tr'.$model.'::row::"/>';
                                
			        $html.='</td>';
                                
                                
                                
				$html.='</tr>';
				
				
				// REMOVE ENTER
				$html = preg_replace ( '/[^(\x20-\x7F)]*/', "", $html ); 
				$html = str_replace ( "\n", "", $html );//remove Enter
				$html = trim($html);
				// --------------------------------------------------------------
				
				$html = htmlentities($html);
				// echo $html;
				
				$onclick='';
				if (isset($data['beforeInsertScript'])) $onclick.=$data['beforeInsertScript'];
				$onclick.="addRow('$model','$html');";
				if (isset($data['afterInsertScript'])) $onclick.=$data['afterInsertScript'];
				$this->out.='<th style="width:20px;"><input id="addrow" style="width:20px;" type="button" class="btn btn-danger pull-right" value="+" onclick="'.$onclick.'"/></th></tr>';
			}

			
			if (isset($this->request->data[$model])){
				$row=0;				
				foreach ($this->request->data[$model] as $iData=>$valData){
					$this->out.=$this->__getRowDetailPFA($modelHeader, $model, $data, $v, $iData, $valData, $view);	
					$row=$iData;				
				}
				$this->out.='<tr id="trHiddenCounter'.$model.'" style="display:none;"><input type="hidden" id="tr_d_counter'.$model.'" value="'.$row.'"/></tr>';
			}
			else {
				$this->out.=$this->__getRowDetailPFA($modelHeader, $model, $data, $v, $row=0, $dataEdit=false, $view);
				$this->out.='<tr id="trHiddenCounter'.$model.'" style="display:none;"><input type="hidden" id="tr_d_counter'.$model.'" value="0"/></tr>';
			}
			
			if ($footerField){
				$key=array_keys($footerField);
				$model=$key[0]; unset($key);
                                
                                
                    //start value ---------------------------------------------------------------------------------
                                
				foreach ($footerField[$model] as $i=>$v){
                                        
					$this->out.='<tr>';					
					if (substr($i,0,10)=='multiField') {
						for ($c=1;$c<=$totalField;$c++){
                                                        
							$this->out.='<td>';
							foreach ($v as $in=>$val) {	
								if ($c==$val['kolom'] and isset($val['label']) and $val['label']==false) {
									$this->out.=$this->__createInputFieldTable($model, $in, $val,  $forceModel=1, $row=null, $view);
								}
								elseif (!isset($val['label']) or (isset($val['label']) and $val['label']!=false)) {					
									if ($c==$val['kolom']) {
										$this->out.='<label style="width:95%;';
										if (isset($val['label']['style'])) $this->out.=$val['label']['style'];						
										$this->out.='">';
										if (isset($val['label']) and !is_array($val['label'])) $this->out.=$val['label'];
										elseif (isset($val['label']['value'])) $this->out.=$val['label']['value'];
										else $this->out.=Inflector::humanize($in);
										$this->out.='</label>';
									}
									elseif ($c==$val['kolom']+1){
										unset($val['kolom']);
										$val['label']=false;
										$this->out.=$this->__createInputFieldTable($model, $in, $val,  $forceModel=1, $row=null, $view);
									}
									else $this->out.='&nbsp;';
								}
								else $this->out.='&nbsp;';						
							}
							$this->out.='</td>';
						}
					} else {
                                            
						$kolom=isset($v['kolom'])?$v['kolom']:($totalField-1);
						unset($v['kolom']);
						for ($c=1;$c<=$totalField;$c++){
							$this->out.='<td>';
							if ($c==$kolom) {
								$this->out.='<label style="width:95%;';
								if (isset($v['label']['style'])) $this->out.=$v['label']['style'];						
								$this->out.='">';
								if (isset($v['label']) and !is_array($v['label'])) $this->out.=$v['label'];
								elseif (isset($v['label']['value'])) $this->out.=$v['label']['value'];
								else $this->out.=Inflector::humanize($i);
								$this->out.='</label>';
							}
							elseif ($c==$kolom+1){
								$v['label']=false;
								$this->out.=$this->__createInputFieldTable($model, $i, $v, $forceModel=1, $row=null, $view);
							}
							else $this->out.='&nbsp;';						
							$this->out.='</td>';
						}
					}
					$this->out.='</tr>';				
				}
			}
			
			$this->out.='</table>';
                        $this->out.='</div>';
		}// end foreach		
	}

	function __getDetail($data, $modelHeader, $view=false){
            
        $hidden_columns = array();
		foreach ($data['detail'] as $model=>$v){
                    
			$footerField= false;
			if (isset($v['footerField'])) {$footerField=$v['footerField']; unset($v['footerField']);}			
			if (isset($data['title_detail']))$this->out.='<h1>'.Inflector::humanize($data['title_detail']).'</h1>';
			else $this->out.='<h1>'.''.'</h1>';
                
                    //cetak table ---------------------------------------------------------------------------------
                        
			//$this->out.='<table'; 
                        if (isset($v['style'])) {$this->out.=''; unset($v['style']);}
                        //'.isset($v['class']) ? $v['class'] 
                        
                        
                        $this->out.= '<div class="table-responsive">';
                                
                        $this->out.='<table class="table-bordered table-striped table-hover col-sm-12" id="'.$model.'" style=" overflow-x; auto; " ';
                        unset($v['class']);                        
			$this->out.='><tr>';
                        
			if (!$view) $html='<tr class=\"tr tr'.$model.'\" id=\"tr'.$model.'::row::\">';
			$totalField=sizeof($v);
                
                // LABEL th ------------------------------------------------------
                
                        //main foreach
			$rowIndex = 0;
			foreach ($v as $in=>$val){
				if (!$view) {
					$index=$in;
					$value=$val;

					if ((isset($val['hidden']) && $val['hidden'] == true) || (isset($val['type']) && $val['type'] == 'hidden'))
						{
							$html.='<td class="hidden">';
							$hidden_columns[$rowIndex] = 'hidden';
						}
					else
                		{
                			$html.='<td>';
                			$hidden_columns[$rowIndex] = '';
                		}
            		$rowIndex++;
					if (is_integer($index)){
						$index=$value;
						$value=array('label'=>false);
					}
					else {
                                            //benerin array
                                            if(is_array($value)){
                                                $value['label']=false; 
                                            }
                                            
                                        }	
					$html.=str_replace('"', '\"', $this->__createInputFieldTable($model, $index, $value, 1, '::row::'));
					$html.='</td>';
				}
                                
                                if(is_array($value)){
                                    if(array_key_exists('lov', $value)){
                                        $classlov=' class="col-md-2" ';
                                    }else{
                                        $classlov='';
                                    }                                    
                                }
                                
                if ((isset($val['hidden']) && $val['hidden'] == true) || (isset($val['type']) && $val['type'] == 'hidden'))
                	$this->out.='<th class="hidden"';
                else
					$this->out.='<th';
				//if (isset($val['label']['class'])) $this->out.=' class="'.$val['label']['class'].'"';
				//if (isset($val['label']['style'])) $this->out.=' style="'.$val['label']['style'].'"';
                                $this->out.= $classlov;
				$this->out.='>';
				if (is_integer($in)) $this->out.=Inflector::humanize($val);
				elseif (isset($val['label']) and !is_array($val['label'])) $this->out.=$val['label'];
				elseif (isset($val['label']['value'])) $this->out.=$val['label']['value'];
				else $this->out.=Inflector::humanize($in);
				$this->out.='</th>';   
                                
			}

			if (!$view) {
                            
                            
				$html.='<td style="width:20px;"><input type="button"  value="-" id="delrow'.$model.'::row::" class="btn btn-danger pull-right" style="width:20px;" onclick=" ';
				if (isset($data['beforeDeleteScript'])) $html.=$data['beforeDeleteScript']; 
				$html.='deleteRow(this.id);';
				if (isset($data['afterDeleteScript'])) $html.=$data['afterDeleteScript'];
				$html.='" rel="tr'.$model.'::row::"/></td>';
			
				$html.='</tr>';
				
				
				// REMOVE ENTER
				$html = preg_replace ( '/[^(\x20-\x7F)]*/', "", $html ); 
				$html = str_replace ( "\n", "", $html );//remove Enter
				$html = trim($html);
				// --------------------------------------------------------------
				
				$html = htmlentities($html);
				// echo $html;
				// button + yang ada di head
				$onclick='';
				if (isset($data['beforeInsertScript'])) $onclick.=$data['beforeInsertScript'];
				$onclick.="addRow('$model','$html');";
				if (isset($data['afterInsertScript'])) $onclick.=$data['afterInsertScript'];
				$this->out.='<th style="width:20px;"><input id="addrow" style="width:20px;" type="button" class="btn btn-danger pull-right" value="+" onclick="'.$onclick.'"/></th></tr>';
			}

			
			if (isset($this->request->data[$model])){
				$row=0;				
				foreach ($this->request->data[$model] as $iData=>$valData){
					$this->out.=$this->__getRowDetail($modelHeader, $model, $data, $v, $iData, $valData, $view);	
					$row=$iData;				
				}
				$this->out.='<tr id="trHiddenCounter'.$model.'" style="display:none;"><input type="hidden" id="tr_d_counter'.$model.'" value="'.$row.'"/></tr>';
			}
			else {
				$this->out.=$this->__getRowDetail($modelHeader, $model, $data, $v, $row=0, $dataEdit=false, $view);
				$this->out.='<tr id="trHiddenCounter'.$model.'" style="display:none;"><input type="hidden" id="tr_d_counter'.$model.'" value="0"/></tr>';
			}
			
			if ($footerField){
				$key=array_keys($footerField);
				$model=$key[0]; unset($key);
                                
                                
                    //start value ---------------------------------------------------------------------------------
				foreach ($footerField[$model] as $i=>$v){

					$this->out.='<tr>';	
					if (substr($i,0,10)=='multiField') {
						for ($c=1;$c<=$totalField;$c++){
							if (isset($hidden_columns[$c-1]) && $hidden_columns[$c-1] == 'hidden')
								$this->out.='<td class="hidden">';
							else
								$this->out.='<td>';
							foreach ($v as $in=>$val) {	
								if ($c==$val['kolom'] and isset($val['label']) and $val['label']==false) {
									$this->out.=$this->__createInputFieldTable($model, $in, $val,  $forceModel=1, $row=null, $view);
								}
								elseif (!isset($val['label']) or (isset($val['label']) and $val['label']!=false)) {					
									if ($c==$val['kolom']) {
										$this->out.='<label style="width:95%;';
										if (isset($val['label']['style'])) $this->out.=$val['label']['style'];						
										$this->out.='">';
										if (isset($val['label']) and !is_array($val['label'])) $this->out.=$val['label'];
										elseif (isset($val['label']['value'])) $this->out.=$val['label']['value'];
										else $this->out.=Inflector::humanize($in);
										$this->out.='</label>';
									}
									elseif ($c==$val['kolom']+1){
										unset($val['kolom']);
										$val['label']=false;
										$this->out.=$this->__createInputFieldTable($model, $in, $val,  $forceModel=1, $row=null, $view);
									}
									else $this->out.='&nbsp;';
								}
								else $this->out.='&nbsp;';						
							}
							$this->out.='</td>';
						}
					}
					else {  
						$kolom=isset($v['kolom'])?$v['kolom']:($totalField-1);
						unset($v['kolom']);
						for ($c=1;$c<=$totalField;$c++){
							if (isset($hidden_columns[$c-1]) && $hidden_columns[$c-1] == 'hidden')
								$this->out.='<td class="hidden">';
							else
								$this->out.='<td>';
							if ($c==$kolom) {
								$this->out.='<label style="width:95%;';
								if (isset($v['label']['style'])) $this->out.=$v['label']['style'];						
								$this->out.='">';
								if (isset($v['label']) and !is_array($v['label'])) $this->out.=$v['label'];
								elseif (isset($v['label']['value'])) $this->out.=$v['label']['value'];
								else $this->out.=Inflector::humanize($i);
								$this->out.='</label>';
							}
							elseif ($c==$kolom+1){
								$v['label']=false;
								$this->out.=$this->__createInputFieldTable($model, $i, $v, $forceModel=1, $row=null, $view);
							}
							else $this->out.='&nbsp;';						
							$this->out.='</td>';
						}
					}
					$this->out.='</tr>';				
				}
			}
			
			$this->out.='</table>';
                        $this->out.='</div>';
		}// end foreach		
	}
        
        function __getRowDetailPFA($modelHeader, $model, $data, $v, $row=0, $dataEdit=false, $view=false){ 
            
            $out='';
            $out.='<tr class="tr tr'.$model.'" id="tr'.$model.$row.'">';

            $c=1;
            $counter = 0;
            $arrTotal = count($v);

            foreach ($v as $in=>$val) {
                    $out.='<td';
                    $out.='>';
                    if ($dataEdit and $c==1 and isset($dataEdit[$this->_View->Helpers->Form->_models[$modelHeader]->$model->primaryKey])){
                            $out.=$this->__createInputFieldTable($model, 'id', array('label'=>false), 1, $row, $view);
                    }
                    if (is_integer($in)){
                            $in=$val;
                            $val=array('label'=>false);
                    }
                    
                    else {
                            if(is_array($value)){
                                $value['label']=false; 
                            }
                    }

                    

                    $out.=$this->__createInputFieldTable($model, $in, $val, 1, $row, $view);

                   
                    $counter++;
                    // if($counter == $arrTotal && $val['label' == 'Qty']){
                    //     $out.='<input style="margin-left:1em" type="button" onclick="getdetail" class="btn btn-primary btn-xs">';
                    // }

                    $out.='</td>';
                    $c++;
            }

          


            if (!$view) {



                    $out.='<td style="width:20px;"><input type="button" class="btn btn-danger pull-right" value="-" id="delrow'.$model.$row.'" style="width:20px;" onclick="';
                    if (isset($data['beforeDeleteScript'])) $out.=$data['beforeDeleteScript']; 
                    $out.='deleteRow(this.id);';
                    if (isset($data['afterDeleteScript'])) $out.=$data['afterDeleteScript'];
                    $out.='" rel="tr'.$model.$row.'"/></td></tr>';
            }
            return $out;
	}
	

	function __getRowDetail($modelHeader, $model, $data, $v, $row=0, $dataEdit=false, $view=false){ 
            
		$out='';
		$out.='<tr class="tr tr'.$model.'" id="tr'.$model.$row.'">';
		
		$c=1;
                $counter = 0;
                $arrTotal = count($v);
                
		foreach ($v as $in=>$val) {
			if ((isset($val['hidden']) && $val['hidden'] == true) || (isset($val['type']) && $val['type'] == 'hidden'))
				$out.='<td class="hidden"';
			else
				$out.='<td';
			$out.='>';
			if ($dataEdit and $c==1 and isset($dataEdit[$this->_View->Helpers->Form->_models[$modelHeader]->$model->primaryKey])){
				$out.=$this->__createInputFieldTable($model, 'id', array('label'=>false), 1, $row, $view);
			}
			if (is_integer($in)){
				$in=$val;
				$val=array('label'=>false);
			}
            else {
                    if(is_array($value)){
                        $value['label']=false; 
                    }
            }
            
            // header table
                        
			$out.=$this->__createInputFieldTable($model, $in, $val, 1, $row, $view);
                        
                   
			$out.='</td>';
			$c++;
		}
                
                //button detail
               
                
		if (!$view) {
                   
                        
                        
			$out.='<td style="width:20px;"><input type="button" class="btn btn-danger pull-right" value="-" id="delrow'.$model.$row.'" style="width:20px;" onclick="';
			if (isset($data['beforeDeleteScript'])) $out.=$data['beforeDeleteScript']; 
			$out.='deleteRow(this.id);';
			if (isset($data['afterDeleteScript'])) $out.=$data['afterDeleteScript'];
			$out.='" rel="tr'.$model.$row.'"/></td></tr>';
		}
		return $out;
	}
	
        
        
}