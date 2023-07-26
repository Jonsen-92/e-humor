<?php
App::uses('AppHelper', 'View');
class MasterHelper extends AppHelper{
	var $helpers=array('Form');
	var $out;

	function getFormMaster($data, $model, $view=false){

            //echo 'getFormMaster';
            $this->__getField($data, $model, $awal=1, $view);
            return $this->out;
	}

	function __getField($data, $model, $awal=1, $view=false){
            //echo '__getField';
            //echo '<pre>'; print_r($data);
			

		foreach ($data as $i=>$v){
			if ($awal) foreach ($v as $index=>$value) {$i=$index;$v=$value;}
                    // integer -------------------------------------------------------------------
                        if (is_integer($i)) {

				if (!$view) $this->out.= $this->Form->input($v, array('class'=>'inputField'));
				else $this->out.= $this->Form->input($v, array('class'=>'inputField', 'disabled'=>true));
			}
                    // fieldset -------------------------------------------------------------------
			elseif ($i=='fieldset') {
                                //echo 'fieldset';
				$this->out.= '<fieldset';
				if (isset($v['class'])) { $this->out.= " class='{$v['class']}'";}
				//if (isset($v['style'])) { $this->out.= " style='{$v['style']}'"; unset($v['style']);}
				$this->out.= '>';
				if (isset($v['legend']) and !empty($v['legend']) and $v['legend']) {
					$this->out.= "<legend";
					if (is_array($v['legend'])){
						if (isset($v['legend']['class'])) $this->out.= " class='{$v['legend']['class']}'";
						//if (isset($v['legend']['style'])) $this->out.= " style='{$v['legend']['style']}'";
						$legend=$v['legend']['value'];
						unset($v['legend']);
					}
					else $legend=$v['legend'];

					$this->out.= ">$legend</legend>";
					unset($legend); unset($v['legend']);
				}
				if (is_array($v)) $this->__getField($v, $model, 0, $view);
				$this->out.= '</fieldset>';
			}
                    // dua kolom -------------------------------------------------------------------

			elseif (substr($i,0,5)=='__div') {

                                //echo '   '.$i;
                                //echo '__div';

                                //div secara keseluruhan
				//yellow
				$this->out .= '<div class="form-group" >';
				$coloumn=1;
				if (isset($v['coloumn'])) { $coloumn=$v['coloumn'];unset($v['coloumn']); }
				$widthRow=array('100%');
				if (isset($v['widthRow'])) {$widthRow=array_merge($widthRow,$v['widthRow']);unset($v['widthRow']);}

				if (is_array($v)) {

					$c=1;
					foreach ($v as $in=>$val){
						$widthColoumn='';
						$this->out.= "<div ".(isset($val["hiddenField"]) ? "class='hidden'" : "").">";
						if (substr($in,0,5)=='__div') $this->__getField($val, $model, 0, $view);
						else $this->out.=$this->__createInputFieldDetail($model, $in, $val, $forceModel=false, $row=null, $view);
						$this->out.= '</div>';
						if ($c%$coloumn==0) {
							$this->out.= "</div><div class='form-group'>";
						}
						$c++;
					}
					$this->out.= '</div>';
				}
			}
                    // input text biasa -------------------------------------------------------------------
			else {
                            //input field
				$v['div']=false;
                                $this->out.=$this->__createInputField($model, $i, $v, $forceModel=false, $row=null, $view);

			}
		}
	}

        // function create input text
	function __createInputField($model, $in, $val, $forceModel=false, $row=null, $view=false){

                $curOut='';

                if (is_integer($in)) {
			$field='';
			if ($forceModel) $field.=$model;
			if (!is_null($row)) $field.=".$row";
			$field=empty($field)?$val:"$field.$val";
			$opt=array('div'=>false,'class'=>'inputField');
			if (!is_null($row)) $opt['row']=$row;
			if ($view) $opt['disabled']=true;
			$curOut.= $this->Form->input($field, $opt);
		}

        // LOV - original ------------------------------------------------------------------------------------------------

		elseif (isset($val['lov'])){

			// Create LABEL ---------------------------------------------------------
                        $curOut.= '<div class="form-group">';

			if (!isset($val['label']) or $val['label']<>false) {
				$curOut.= "<label for='$model".Inflector::camelize($in)."'";
				$label=Inflector::humanize($in);
                                
				if (isset($val['label'])) {
					if (is_array($val['label'])){
						if (isset($val['label']['class'])) $curOut.= " class='{$val['label']['class']}'";
						if (isset($val['label']['style'])) $curOut.= " style='{$val['label']['style']}'";
						$label=$val['label']['value'];
					}
					else $label=$val['label'];
				}
                                
				$curOut.=" class='col-sm-2 control-label'>$label</label>";
				//matiin unset label biar bisa dipake lagi
                                $labelcetak = $label;
                                unset($label);
                                unset($val['label']);
                                //cetak label
			}

                        // Create LABEL ---------------------------------------------------------

			// Create idLOV
			$urlLov=WEB_PROTOCOL.'://'.$_SERVER['SERVER_NAME'].$this->base;
			$urlLov.=isset($val['lov']['url'])?$val['lov']['url']:'/';
			$urlLov.='/pid:'.$model.$row.(Inflector::camelize($in)).'/pname:'.$model.$row.(Inflector::camelize($in.'_desc'));
			if (!is_null($row)) $urlLov.='/pcounter:'.$row;
			$urlLov.='/pmodel:'.$model;

			$idLov=array();

                    //start column sbelah kanan / abis label ---------------------------
                        $curOut.= '<div class="col-sm-7">';
                        $curOut.= '<div class="row">';

                    // create input text sbelah kiri ---------------------------
			$val['lov']['idLov']['label']=false;
			$val['lov']['idLov']['div']=false;
			$val['lov']['idLov']['type']='text';
			if (!isset($val['lov']['lovButton']) or (isset($val['lov']['lovButton']) and $val['lov']['lovButton']==0)) {
				$idLov['class']='thickbox inputField col-sm-2 form-control';
				$idLov['alt']=$idLov['rel']=$urlLov;
			}
			if (isset($val['lov']['idLov'])){

                                //$val['lov']['idLov']['class'] = 'col-sm-2 control-label';
				if (isset($val['lov']['idLov']['alt'])) unset($val['lov']['idLov']['alt']);
				if (isset($val['lov']['idLov']['options'])) unset($val['lov']['idLov']['options']);
				if (isset($val['lov']['idLov']['class'])) {
					$idLov['class'].=' '.$val['lov']['idLov']['class'].' form-control';
					unset($val['lov']['idLov']['class']);
				}
				$idLov+=$val['lov']['idLov'];
			}
			$field='';
			if ($forceModel) $field.=$model;
			if (!is_null($row)) $field.=".$row";
			$field=empty($field)?$in:"$field.$in";
			if (!is_null($row)) $idLov['row']=$row;

			if ($view) $idLov['disabled']=true;
                        //unset seluruh style
                        unset($idLov['style']);

                        $curOut.= '<div class="col-sm-3">';
                        $idLov['class'] = 'form-control';
			$curOut.= $this->Form->input($field, $idLov);
                        $curOut.= '</div>';

			if (isset($val['lov']['idLov']['autocomplete']) and !$view) $curOut.=$this->__getAutoComplete($model,$in,$val['lov']['idLov'],$row);

                    // Create Button LOV ---------------------------
			if (isset($val['lov']['lovButton']) and $val['lov']['lovButton']==1 and !$view) {
				//$btnLov=array('class'=>'thickbox', 'alt'=>$urlLov, 'rel'=>$urlLov, 'type'=>'button', 'label'=>false, 'div'=>false);
				//$this->out.= $this->Form->input('...', $btnLov);
				$idButton=explode('.',$field);
				$idButton=$idButton[(sizeof($idButton)-1)];

				//$curOut.='<input class="thickbox" id="'.$model.$row.Inflector::camelize($idButton).'Button" rel="'.$urlLov.'" alt="'.$urlLov.'" type="button" value="..." style="width:30px;"/>';
                                $curOut.= '<div class="col-sm-1">';
                                    $curOut.='<input type="button" class="thickbox btn btn-primary btn-sm form-control" rel="'.$urlLov.'" alt="'.$urlLov.'" id="'.$model.$row.Inflector::camelize($idButton).'Button" value="...">';
                                $curOut.= '</div>';
                        }

                    // text sebelah kanan ---------------------------
			if (isset($val['lov']['descLov'])){
				$descLov=array();
				$val['lov']['descLov']['label']=false;
				$val['lov']['descLov']['div']=false;
				$val['lov']['descLov']['type']='text';
				$descLov['class']='inputField form-control';
				if (isset($val['lov']['descLov'])){
					if (isset($val['lov']['descLov']['alt'])) unset($val['lov']['descLov']['alt']);
					if (isset($val['lov']['descLov']['options'])) unset($val['lov']['descLov']['options']);
					if (isset($val['lov']['descLov']['class'])) {
						$descLov['class'].=' '.$val['lov']['descLov']['class'];
						unset($val['lov']['descLov']['class']);
					}
					$descLov+=$val['lov']['descLov'];
				}

				$field='';
				if ($forceModel) $field.=$model;
				if (!is_null($row)) $field.=".$row";
				$field=empty($field)?$in.'_desc':$field.'.'.$in.'_desc';
				if (!is_null($row)) $descLov['row']=$row;
				if ($view) $descLov['disabled']=true;

                                //matiin style
                                unset($descLov['style']);

                                $curOut.= '<div class="col-sm-4">';
                                    $curOut.= $this->Form->input($field, $descLov);
                                $curOut.= '</div>';

				if (isset($val['lov']['descLov']['autocomplete']) and !$view) $curOut.=$this->__getAutoComplete($model,$in,$val['lov']['descLov'], $row);
			}
                            //tutup row
                            $curOut.='</div>';
                            $curOut.='</div>';
                        $curOut.='</div>';
                        //echo $curOut;

		}

    // INPUT TEXT - original ------------------------------------------------------------------------------------------------

		else {

                        if($val['type']=='hidden'){
                            $curOut = '<div>';
                        }else{
                            $curOut = '<div class="form-group">';
                        }

			$val['div']=false;
			// Create LABEL ----------------------------------------------------------
			if (!isset($val['label']) or $val['label']<>false or $val['type']!='hidden') {
                                //tambahin form-group
                                $curOut = '<div class="form-group">';
                                //cetak label
				$curOut.= "<label for='$model".Inflector::camelize($in)."'";
				$label=Inflector::humanize($in);

				if (isset($val['label'])) {
					if (is_array($val['label'])){
						if (isset($val['label']['class'])) $curOut.= " class='{$val['label']['class']}'";
						if (isset($val['label']['style'])) $curOut.= " style='{$val['label']['style']}'";
						$label=isset($val['label']['value'])?$val['label']['value']:$label;
					}
					else $label=$val['label'];
				}

                                //tambahin class bootstrap - label -------------------------
				$curOut.="class='col-sm-2 control-label'>$label</label>";
				unset($label); unset($val['label']);
			}

                        // END : Create LABEL ----------------------------------------------------------

			$val['label']=false;
			$field='';
			if ($forceModel) $field.=$model;
			if (!is_null($row)) $field.=".$row";
			$field=empty($field)?$in:"$field.$in";
			if (isset($val['class'])) $val['class'].=' form-control inputField';
			else $val['class']='form-control inputField';
			if (!is_null($row)) $val['row']=$row;

			if ($view) $val['disabled']=true;

			if(isset($val['class'])){
				$classForced = explode(" ",$val['class']);
				if(!empty($classForced)){
					if(in_array("currency",$classForced)) {
						if(@$val['type'] !='hidden'){
							$val['type']='text';
						}
					}
				}
			}


                        // form input -------------------------

                        if($val['type'] != 'hidden'){
                            if($val['type'] == "radio"){
                                $val['class']='';
                                $curOut.= '<div class="col-sm-7">';
                                $curOut.= '<div class="btn-group btn-group-vertical">';
                                $curOut.= $this->Form->input($field, $val);
                                $curOut.= '</div>';
                                $curOut.= '</div>';
                            }else{
                                $curOut.= '<div class="col-sm-4">';
                                //matiin style
                                unset($val['style']);
                                $curOut.= $this->Form->input($field, $val);
                                $curOut.= '</div>';
                            }



                        }

                        else{
                            $curOut.= $this->Form->input($field, $val);
                        }


			// label setelah input form
			if (isset($val['label2']) and $val['label2']) {
				$curOut.= '<span';
				if (is_array($val['label2'])){
					if (isset($val['label2']['class'])) $curOut.= " class='{$val['label2']['class']}'";
					if (isset($val['label2']['style'])) $curOut.= " style='{$val['label2']['style']}'";
					$val['label2']=$val['label2']['value'];
				}
				$curOut.=">{$val['label2']}</span>";
				unset($val['label2']);
			} // end create label 2

                         //tutup form-group
                        $curOut.= '</div>';

			// Auto Complete
			if (isset($val['autocomplete']) and !$view) $curOut.=$this->__getAutoComplete($model,$in,$val,$row);
                        //echo 'masuk auto complete';
		}

                //echo '<br/>'. htmlspecialchars($curOut).'<hr/>';
		return $curOut;
	}


// form untuk penggunaan 2 kolom -------------------------------------

        function __createInputFieldDetail($model, $in, $val, $forceModel=false, $row=null, $view=false){

		$curOut='';
		if (is_integer($in)) {
			$field='';
			if ($forceModel) $field.=$model;
			if (!is_null($row)) $field.=".$row";
			$field=empty($field)?$val:"$field.$val";
			$opt=array('div'=>false,'class'=>'inputField');
			if (!is_null($row)) $opt['row']=$row;
			if ($view) $opt['disabled']=true;
			$curOut.= $this->Form->input($field, $opt);
		}
		elseif (isset($val['lov'])){

			// Create LABEL ---------------------------------------------------------

			if (!isset($val['label']) or $val['label']<>false) {
				$curOut.= "<label for='$model".Inflector::camelize($in)."'";
				$label=Inflector::humanize($val['label']);

				$curOut.=" class='col-sm-2 control-label'>$label</label>";
                                $labelcetak = $label;
                                unset($label);
                                unset($val['label']);
                                //cetak label
			}

			// Create idLOV
			$urlLov=WEB_PROTOCOL.'://'.$_SERVER['SERVER_NAME'].$this->base;
			$urlLov.=isset($val['lov']['url'])?$val['lov']['url']:'/';
			$urlLov.='/pid:'.$model.$row.(Inflector::camelize($in)).'/pname:'.$model.$row.(Inflector::camelize($in.'_desc'));
			if (!is_null($row)) $urlLov.='/pcounter:'.$row;
			$urlLov.='/pmodel:'.$model;

			$idLov=array();

                    //start column sbelah kanan / abis label ---------------------------
                        $curOut.= '<div class="col-sm-4">';
                        $curOut.= '<div class="row">';


			$val['lov']['idLov']['label']=false;
			$val['lov']['idLov']['div']=false;
			$val['lov']['idLov']['type']='text';
			if (!isset($val['lov']['lovButton']) or (isset($val['lov']['lovButton']) and $val['lov']['lovButton']==0)) {
				$idLov['class']='thickbox inputField col-sm-2 form-control';
				$idLov['alt']=$idLov['rel']=$urlLov;
			}
			if (isset($val['lov']['idLov'])){

				if (isset($val['lov']['idLov']['alt'])) unset($val['lov']['idLov']['alt']);
				if (isset($val['lov']['idLov']['options'])) unset($val['lov']['idLov']['options']);
				if (isset($val['lov']['idLov']['class'])) {
					$idLov['class'].=' '.$val['lov']['idLov']['class'].' form-control';
					unset($val['lov']['idLov']['class']);
				}
				$idLov+=$val['lov']['idLov'];
			}
			$field='';
			if ($forceModel) $field.=$model;
			if (!is_null($row)) $field.=".$row";
			$field=empty($field)?$in:"$field.$in";
			if (!is_null($row)) $idLov['row']=$row;

			if ($view) $idLov['disabled']=true;
                        //unset seluruh style
                        unset($idLov['style']);

                    // create input text sbelah kiri ---------------------------

                        $curOut.= '<div class="col-sm-4">';
                        $idLov['class'] = 'form-control';
			$curOut.= $this->Form->input($field, $idLov);
                        $curOut.= '</div>';

			if (isset($val['lov']['idLov']['autocomplete']) and !$view) $curOut.=$this->__getAutoComplete($model,$in,$val['lov']['idLov'],$row);

                    // Create Button LOV ---------------------------
			if (isset($val['lov']['lovButton']) and $val['lov']['lovButton']==1 and !$view) {
				//$btnLov=array('class'=>'thickbox', 'alt'=>$urlLov, 'rel'=>$urlLov, 'type'=>'button', 'label'=>false, 'div'=>false);
				//$this->out.= $this->Form->input('...', $btnLov);
				$idButton=explode('.',$field);
				$idButton=$idButton[(sizeof($idButton)-1)];

				$curOut.= '<div class="col-sm-2">';
                                    $curOut.='<input type="button" class="thickbox btn btn-primary btn-xs form-control " rel="'.$urlLov.'" alt="'.$urlLov.'" id="'.$model.$row.Inflector::camelize($idButton).'Button" value="...">';
                                $curOut.= '</div>';
                        }else{
                            //disabled button search
                           // $curOut.= '<div class="col-sm-3">';
                                   // $curOut.='<button disabled class="btn btn-primary btn-xs form-control" value="...">';
                          //  $curOut.=
                            '</div>';
                        }

                    // text sebelah kanan ---------------------------

			if (isset($val['lov']['descLov'])){

				$descLov=array();
				$val['lov']['descLov']['label']=false;
				$val['lov']['descLov']['div']=false;
				$val['lov']['descLov']['type']='text';
				$descLov['class']='inputField form-control';
				if (isset($val['lov']['descLov'])){
					if (isset($val['lov']['descLov']['alt'])) unset($val['lov']['descLov']['alt']);
					if (isset($val['lov']['descLov']['options'])) unset($val['lov']['descLov']['options']);
					if (isset($val['lov']['descLov']['class'])) {
						$descLov['class'].=' '.$val['lov']['descLov']['class'];
						unset($val['lov']['descLov']['class']);
					}
					$descLov+=$val['lov']['descLov'];
				}

				$field='';
				if ($forceModel) $field.=$model;
				if (!is_null($row)) $field.=".$row";
				$field=empty($field)?$in.'_desc':$field.'.'.$in.'_desc';
				if (!is_null($row)) $descLov['row']=$row;
				if ($view) $descLov['disabled']=true;

                unset($descLov['style']);

                                $curOut.= '<div class="col-sm-4">';
                                    $curOut.= $this->Form->input($field, $descLov);
                                $curOut.= '</div>';

				if (isset($val['lov']['descLov']['autocomplete']) and !$view) $curOut.=$this->__getAutoComplete($model,$in,$val['lov']['descLov'], $row);
			}
                            //tutup row
                            //$curOut.='</div>';
                            $curOut.='</div>';
                        $curOut.='</div>';
                        //echo $curOut;

		}

		else {

                //create input text - dua kolom
			
			//cien, 20170201, pindahkan create div untuk form input keatas sebelum label agar style utk label saat disabled/hidden ikut terbawa, begin
			/*if (($val['type'] != "radio") && ($val['type'] != "hidden"))
			{
				//hapus style
                unset($val['style']);

                $curOut.= '<div class="col-sm-4">';
			}
			*/
			//cien, 20170201, pindahkan create div untuk form input keatas sebelum label agar style utk label saat disabled/hidden ikut terbawa, end
			
			$val['div']=false;
			// Create LABEL
			if (!isset($val['label']) or $val['label']<>false) {
				$curOut.= "<label class='col-sm-2 label-form' for='$model".Inflector::camelize($in)."'";
				$label= $val['label'];

                                /*
				if (isset($val['label'])) {
					if (is_array($val['label'])){
						if (isset($val['label']['class'])) $curOut.= " class='{$val['label']['class']}'";
						if (isset($val['label']['style'])) $curOut.= " style='{$val['label']['style']}'";
						$label=isset($val['label']['value'])?$val['label']['value']:$label;
					}
					else $label=$val['label'];
				}*/
				$curOut.=">$label</label>";
				unset($label); unset($val['label']);
			}
			$val['label']=false;
			$field='';
			if ($forceModel) $field.=$model;
			if (!is_null($row)) $field.=".$row";
			$field=empty($field)?$in:"$field.$in";

			$val['class']='sm-col-4 form-control lovfield inputField';

                        if (!is_null($row)) $val['row']=$row;

			if ($view) $val['disabled']=true;



                        //kalau radio button, ada perlakuan berbeda
                        if($val['type'] == "radio"){
                            $val['class']='';
                            $curOut.= '<div class="col-sm-4">';
                            $curOut.= '<div class="btn-group btn-group-vertical">';
                            $curOut.= $this->Form->input($field, $val);
                            $curOut.= '</div>';
                            $curOut.= '</div>';

                        }elseif ($val['type'] == "hidden"){
                            $curOut.= '<div class="col-sm-6">';
							$curOut.= $this->Form->hidden($field, $val); 
                            $curOut.= '</div>';
                        }else{
                            //cien, 20170201, pindahkan create div untuk form input keatas sebelum label agar style utk label saat disabled/hidden ikut terbawa
                            
							//hapus style
                            unset($val['style']);

                            $curOut.= '<div class="col-sm-4">';
							
                            $curOut.= $this->Form->input($field, $val);
                            $curOut.= '</div>';
                        }
                        unset($val['style']);
			// Create label 2
			if (isset($val['label2']) and $val['label2']) {
				$curOut.= '<span';
				if (is_array($val['label2'])){
					if (isset($val['label2']['class'])) $curOut.= " class='{$val['label2']['class']}'";
					//if (isset($val['label2']['style'])) $curOut.= " style='{$val['label2']['style']}'";
					$val['label2']=$val['label2']['value'];
				}
				$curOut.=">{$val['label2']}</span>";
				unset($val['label2']);
			} // end create label 2

			// Auto Complete
			if (isset($val['autocomplete']) and !$view) $curOut.=$this->__getAutoComplete($model,$in,$val,$row);
		}
		return $curOut;
	}

// create input text untuk di table ----------------------

    function __createInputFieldTable($model, $in, $val, $forceModel=false, $row=null, $view=false){

                //pr($val);
		$curOut='';
		if (is_integer($in)) {
			$field='';
			if ($forceModel) $field.=$model;
			if (!is_null($row)) $field.=".$row";
			$field=empty($field)?$val:"$field.$val";
			$opt=array('div'=>false,'class'=>'inputField');
			if (!is_null($row)) $opt['row']=$row;
			if ($view) $opt['disabled']=true;
			$curOut.= $this->Form->input($field, $opt);
		}
		elseif (isset($val['lov'])){
			// LOV
			// Create LABEL
                    
			// if (!isset($val['label']) or $val['label']<>false) {
			// 	$curOut.= "<label for='$model".Inflector::camelize($in)."'";
			// 	$label=Inflector::humanize($in);
			// 	if (isset($val['label'])) {
			// 		if (is_array($val['label'])){
			// 			if (isset($val['label']['class'])) $curOut.= " class='{$val['label']['class']}'";
			// 			if (isset($val['label']['style'])) $curOut.= " style='{$val['label']['style']}'";
			// 			$label=$val['label']['value'];
			// 		}
			// 		else $label=$val['label'];
			// 	}
			// 	$curOut.=">$label</label>";
			// 	unset($label); unset($val['label']);
			// }
			
			// Create idLOV
			$urlLov=WEB_PROTOCOL.'://'.$_SERVER['SERVER_NAME'].$this->base;
			$urlLov.=isset($val['lov']['url'])?$val['lov']['url']:'/';
			$urlLov.='/pid:'.$model.$row.(Inflector::camelize($in)).'/pname:'.$model.$row.(Inflector::camelize($in.'_desc'));
			if (!is_null($row)) $urlLov.='/pcounter:'.$row;
			$urlLov.='/pmodel:'.$model;

			$curOut.= '<div class="form-inline">';

			$idLov=array();
			$val['lov']['idLov']['label']=false;
			$val['lov']['idLov']['div']=false;
			$val['lov']['idLov']['type']='text';
			if (!isset($val['lov']['lovButton']) or (isset($val['lov']['lovButton']) and $val['lov']['lovButton']==0)) {
				$idLov['class']='thickbox inputField';
				$idLov['alt']=$idLov['rel']=$urlLov;
			}
			if (isset($val['lov']['idLov'])){
				if (isset($val['lov']['idLov']['alt'])) unset($val['lov']['idLov']['alt']);
				if (isset($val['lov']['idLov']['options'])) unset($val['lov']['idLov']['options']);
				if (isset($val['lov']['idLov']['class'])) {
					$idLov['class'].=' '.$val['lov']['idLov']['class'].' ';
					unset($val['lov']['idLov']['class']);
                                }else{
                                    $idLov['class'].=' ';
					unset($val['lov']['idLov']['class']);
                                }
				$idLov+=$val['lov']['idLov'];
			}
			$field='';
			if ($forceModel) $field.=$model;
			if (!is_null($row)) $field.=".$row";
			$field=empty($field)?$in:"$field.$in";
			if (!is_null($row)) $idLov['row']=$row;

			if ($view) $idLov['disabled']=true;

                        //matiin style untuk di table
                        $idLov['style'] = 'width:30px';


                        //lov di sebelah kiri - table
			$curOut.= $this->Form->input($field, $idLov);

			if (isset($val['lov']['idLov']['autocomplete']) and !$view) $curOut.=$this->__getAutoComplete($model,$in,$val['lov']['idLov'],$row);

			// Create Button LOV
			if (isset($val['lov']['lovButton']) and $val['lov']['lovButton']==1 and !$view) {
				//$btnLov=array('class'=>'thickbox', 'alt'=>$urlLov, 'rel'=>$urlLov, 'type'=>'button', 'label'=>false, 'div'=>false);
				//$this->out.= $this->Form->input('...', $btnLov);
				$idButton=explode('.',$field);
				$idButton=$idButton[(sizeof($idButton)-1)];
				$curOut.='<input class="thickbox " id="'.$model.$row.Inflector::camelize($idButton).'Button" rel="'.$urlLov.'" alt="'.$urlLov.'" type="button" value="..." style="width:30px;"/>';
			}

			if (isset($val['lov']['descLov'])){

				$descLov=array();
				$val['lov']['descLov']['label']=false;
				$val['lov']['descLov']['div']=false;
				$val['lov']['descLov']['type']='text';
				$descLov['class']='inputField';

				if (isset($val['lov']['descLov'])){
					if (isset($val['lov']['descLov']['alt'])) unset($val['lov']['descLov']['alt']);
					if (isset($val['lov']['descLov']['options'])) unset($val['lov']['descLov']['options']);
					if (isset($val['lov']['descLov']['class'])) {
						$descLov['class'].=' '.$val['lov']['descLov']['class'].' form-control';
						unset($val['lov']['descLov']['class']);
					}else{
                                            $descLov['class'].='  ';
                                            unset($val['lov']['idLov']['class']);
                                        }
					$descLov+=$val['lov']['descLov'];
				}

				$field='';
				if ($forceModel) $field.=$model;
				if (!is_null($row)) $field.=".$row";
				$field=empty($field)?$in.'_desc':$field.'.'.$in.'_desc';
				if (!is_null($row)) $descLov['row']=$row;
				if ($view) $descLov['disabled']=true;
                                $descLov['style'] = 'width:90px ';


				$curOut.= $this->Form->input($field, $descLov);
				if (isset($val['lov']['descLov']['autocomplete']) and !$view) $curOut.=$this->__getAutoComplete($model,$in,$val['lov']['descLov'], $row);
			}
		}
		else {
                //pr($val);
                    //input text table

                        //benerin array
			$val['div']=false;
                        //$val=array('div'=>false);

			// matiin label kusus untuk table
                        /*
			if (!isset($val['label']) or $val['label']<>false) {
				$curOut.= "<label for='$model".Inflector::camelize($in)."'";
				$label=Inflector::humanize($in);
				if (isset($val['label'])) {
					if (is_array($val['label'])){
						if (isset($val['label']['class'])) $curOut.= " class='{$val['label']['class']}'";
						if (isset($val['label']['style'])) $curOut.= " style='{$val['label']['style']}'";
						$label=isset($val['label']['value'])?$val['label']['value']:$label;
					}
					else $label=$val['label'];
				}
				$curOut.=">$label</label>";
				unset($label); unset($val['label']);
			}
                        */


                        //pr($val);
			$val['label']=false;
			$field='';
			if ($forceModel) $field.=$model;
			if (!is_null($row)) $field.=".$row";
			$field=empty($field)?$in:"$field.$in";
                        if (isset($val['class'])) {
                            $val['class'].=' inputField col-md-10 lovfield ';
                        }
			else $val['class']=' col-md-10 ';
			if (!is_null($row)) $val[ 'row']=$row;

			if ($view) $val['disabled']=true;

			if(isset($val['class'])){
				$classForced = explode(" ",$val['class']);
				if(!empty($classForced)){
					if(in_array("currency",$classForced)) {
						if(@$val['type'] !='hidden'){
							$val['type']='text';
						}
					}
				}
			}

                    unset($val['style']);

			$curOut.= $this->Form->input($field,$val);

			// Create label 2
			if (isset($val['label2']) and $val['label2']) {
				$curOut.= '<span';
				if (is_array($val['label2'])){
					if (isset($val['label2']['class'])) $curOut.= " class='{$val['label2']['class']}'";
					if (isset($val['label2']['style'])) $curOut.= " style='{$val['label2']['style']}'";
					$val['label2']=$val['label2']['value'];
				}
				$curOut.=">{$val['label2']}</span>";
				unset($val['label2']);
			} // end create label 2

			// Auto Complete
			if (isset($val['autocomplete']) and !$view) $curOut.=$this->__getAutoComplete($model,$in,$val,$row);
		}
		return $curOut;
	}


	function __getAutoComplete($model,$in,$val, $row=null){

            //echo '__getAutoComplete';

		$curOut='';
		$autoc=$val['autocomplete'];
		$curOut.='<script type="text/javascript">';
			$curOut.='$(function() {';
				$curOut.='var id;';
				$curOut.='$( "#'.$model.Inflector::camelize($in).$row.'" ).autocomplete({';
					$curOut.='source: function( request, response ) {';
						$curOut.='$.ajax({';
							$url=$this->base.'/common/autocomplete/'.$autoc['model'].'/'.$autoc['field'];
							if (isset($autoc['kondisi'])) $url.='/'.$autoc['kondisi'];
							$curOut.='url: "'.$url.'",';
							unset($url);
							$curOut.='dataType: "json",';
							$curOut.='type: "POST",';
							$curOut.='data: {';
								$curOut.='keyword: request.term';
								if (isset($autoc['limit'])) $curOut.='limit: '.$autoc['limit'].',';
							$curOut.='},';
							$curOut.='success: function( data ) {response(data);}';
						$curOut.='});';
					$curOut.='},';

					if (isset($autoc['minLength'])) $curOut.='minLength: '.$autoc['minLength'].',';
					else $curOut.='minLength: 2,';

					$curOut.='select: function(event, ui){';
						$curOut.='id=ui.item.id;';
						if (isset($autoc['script'])) $curOut.=$autoc['script'];
					$curOut.='}';
				$curOut.='});';
			$curOut.='});';
		$curOut.='</script>';
		unset($autoc);

		return $curOut;
	}
}
