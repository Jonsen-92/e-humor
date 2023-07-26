<?php
/**
 * Class of General
 *
 * Class for general purpose like
 * - Display filtering form
 * - getValue on View or List Index
 * - Goggle Map
 *
 *
 * @package cake
 * @subpackage cake.app.view.helper
 * @since mtemplate 1.1 version
 * @access public
 */
class GeneralHelper extends AppHelper {
	var $helpers=array('Html','Form','Image');

   /**
    * form filtering
    *
    *
    * @param array $data
    * @return string html form filtering
    * @since mtemplate 1.0 version
    * @access public
    */
      
        
        
    function searchfiltering ($data) {
        
        $onclick="$('.filter').toggle();";
        
        $out ='<h4 style="cursor:pointer"><a onclick="'.$onclick.'" name="filter"><i class="fa  fa-search"></i> Search Filtering</a></h4>';
        
            $out.=$this->Form->create('Filter', array('url'=>'/'.$this->request->url, 'class'=>'filter form-horizontal col-sm-7', 'name'=>'formfilter', 'style'=>'display:none'));
            $out.=$this->Form->input('url',array('type'=>'hidden','default'=>$this->request->url));


        foreach ($data as $i=>$v) {
            $t=is_numeric($i)?$v:$i;
            $label=Inflector::humanize((is_array($v) and isset($v['label']))?$v['label']:$t);
            $out.='<input type="hidden" value="'.$t.'" name="data[Filter][field][]"/>';
			$type=(is_array($v) and isset($v['type']))?$v['type']:'string';
			$out.=$this->Form->input("Filter.____$t", array('type'=>'hidden','default'=>$type));

        //start per elemen div                    
            $out.='<div class="form-group" style="height:18px">';
            
        //label    
            $out.='<label for="'.$t.'" class="col-sm-3 control-label"><small>'.$label.'</small></label>';
			
                    // div form - row
                        $out.= '<div class="col-sm-9">';
                        $out.= '<div class="row">';
                    
			// Create Operator
			$optionOpt=array('='=>'=','<>'=>'<>');
                        
			if (is_array($v) and isset($v['type']) and $v['type']<>'string') {
				$optionOpt+=array('<'=>'<','>'=>'>','<='=>'<=','>='=>'>=');	
			}
			else $optionOpt+=array('LIKE'=>'LIKE');
			$optionOpt+=array('IN'=>'IN','NOT IN'=>'NOT IN' );
			$op=array('div'=>false,'label'=>false, 'options'=>$optionOpt,'class' => 'form-control input-sm');
			if (!is_numeric($i) and isset($v['op'])) $op['options']=$v['op'];
			if (!is_numeric($i) and isset($v['op_default'])) $op['default'] = $v['op_default'];  
                        
                        // operator ----
                        $out.= '<div class="col-sm-3">';
			$out.=$this->Form->input('Filter.op'.$t, $op);
			$out.= '</div>';
                        
                        $style= '';
			//$style='float:left;margin-top:0px;';
			//$style.=isset($v['width'])?'width:'.$v['width'].';':'';
                        
                        // input field ----
                        $out.= '<div class="col-sm-7">';
			if(isset($v['type'])){
				if($v['type'] == 'select'){
					$out.=$this->Form->input("Filter.$t", array('style'=>$style, 'label'=>false, 'div'=>false,'type'=>$typeValue,'options'=>$v['options']));		
				}
			}else
			{
				$out.=$this->Form->input("Filter.$t", array('class'=>'form-control input-sm','style'=>$style, 'label'=>false, 'div'=>false, 'type'=>'text'));
			}
		//	$out.=$this->Form->input("Filter.$t", array('class'=>'form-control input-sm','style'=>$style, 'label'=>false, 'div'=>false, 'type'=>'text'));
			
			if (!is_numeric($i) and isset($v['next'])) {
				$opNext=array('options'=>array('AND'=>'AND','OR'=>'OR'),'style'=>'float:left','div'=>false,'label'=>false);
				if (isset($v['next']['op'])) $opNext['options']=$v['next']['op'];
				if (isset($v['next']['op_default'])) $opNext['default']=$v['next']['op_default'];
				$out.=$this->Form->input('Filter.next'.$t, $opNext);

				$opNext2=array('div'=>false,'label'=>false, 'options'=>$optionOpt, 'style'=>'width:60px;');
				if (isset($v['next']['op_next'])) $opNext2['options']=$v['next']['op_next'];
				if (isset($v['next']['op_next_default'])) $opNext2['default']=$v['next']['op_next_default'];
				$out.=$this->Form->input('Filter.op'.$t.'is_next2', $opNext2);
				
				$out.='<input type="hidden" value="'.$t.'is_next2" name="data[Filter][field][]" style="float:left;"/>';
				//$style='float:left;margin-top:0px;';
				//$style.=isset($v['width'])?'width:'.$v['width'].';':'';
                                
				$out.=$this->Form->input('Filter.'.$t.'is_next2', array('class'=>'form-control input-sm','style'=>$style, 'label'=>false, 'div'=>false));
			}
                        // end div input form
                        $out.= '</div>';
                        // end div form - row
                        $out.= '</div></div>';
        // end div                
            $out.='</div>';
				
        }
        
        $finish = array(
            'label' => 'Filter',
            'class' => 'btn btn-primary btn-sm pull-right',
            'div' => array(
                'class' => 'col-sm-12',
            )
        );
        $out.= '<div class="col-sm-11">';
        $out.=$this->Form->end($finish);
        $out.= '</div>';
	$out.='<div style="margin-top:30px;">&nbsp;</div>';
        return $out;
    }

	function getTreeView($aOpt, $model, $id, $data, $counter=0, $level=0){
        if (!isset($output)) $output='';
        foreach ($data as $v) {
            $class = null;
            if ($counter++ % 2 == 0) $class = ' class="altrow"';
            $output.='<tr'.$class.' id="'.$counter.'" rel="'.$v[$model][$id].'">';
            if (isset($aOpt['selectAll'])) $output.= '<td style="width:20px;">'.$this->Form->input('selectAll['.$v[$model][$id].']', array('type'=>'checkbox', 'class'=>'selectAll','label'=>false, 'div'=>false)).'</td>';
            
            foreach ($aOpt['field'] as $i=>$w){
                $output.='<td id="'.$i.$counter.'" style="';
                if (isset($w['align'])) $output.='text-align:'.$w['align'].';';
                if (isset($w['width'])) $output.= 'width:'.$w['width'].';';
                $output.='">';
                $field=is_array($w)?$i:$w;
                if ($aOpt['displayField']==$field) {
                    for($tab=0;$tab<$level;$tab++) $output.='&nbsp; &nbsp; &nbsp; &nbsp; ';
                    $output.='-- ';
                }
                $output.=$this->getValue($i,$w,$v,$aOpt,$model);
                $output.=' &nbsp;</td>';
            }
            if (isset($aOpt['url']) and !empty($aOpt['url'])) {
				$output.='<td class="actions">';
                $output.=$this->__getLinkAction($model, $id, $v, $aOpt['url']);
				$output.='</td>';
            }
            $output.='</tr>';
            if (!empty($v['children'])) {
                $level++;
                $output.=$this->getTreeView ($aOpt, $model, $id, $v['children'], $counter++, $level);
                $level--;
            }
        }
        return $output;
    }

	function __getLinkAction($model, $id, $v, $url){
        $output='';
        if (in_array('view', $url)) $output.= $this->Html->link(__('VIEW', true), array('action' => 'view', $v[$model][$id]));
		if (in_array('edit', $url)) $output.= $this->Html->link(__('EDIT', true), array('action' => 'edit', $v[$model][$id]));
        if (in_array('delete', $url)) $output.= $this->Form->postLink(__('DELETE'), array('action' => 'delete', $v[$model][$id]), null, __('Are you sure you want to delete # %s?', $v[$model][$id]));

        foreach ($url as $x=>$y) {
            if (is_array($y) and !in_array($x, array('add','view','edit','delete'))) {
                $option_link=array();
				$fid=isset($y['fid'])?$y['fid']:$id;

				if (is_array($fid)) {
		        	$isId=array();
		            foreach ($fid as $t) $isId[]=$v[$model][$t];
		            $sFid=implode('::',$isId);
				}
				else $sFid=$v[$model][$fid];

				foreach ($y as $k=>$l){
		        	if (!is_array($l)) {
		        		$l=str_replace('%s',$sFid,$l);
						$isXCode=explode('%#',$l);
						if (isset($isXCode[1])) $l=str_replace('%#'.$isXCode[1].'%#',$v[$model][$isXCode[1]], $l);
						if (!in_array($k, array('uac','alert'))) $option_link[$k]=$l;
		        	}
				}

				$option_url=array();
				$option_url['action']=$y['uac'];
				if (is_array($fid)) {
		        	foreach ($fid as $t) $option_url[]=$v[$model][$t];
				}
				else $option_url[]=$v[$model][$fid];

		        if (isset($y['alert'])) $output.= $Html->link(__($x, true), $option_url, $option_link, sprintf(__($y['alert']. ' # %s?', true), $v[$model][$fid]));
				else $output.= $Html->link(__($x, true), $option_url, $option_link);
			}
		}

		return $output;
	}

	function getValue($i,$w,$v,$aOpt,$model,$id=null){		
		$t=(is_array($w))?$i:$w;
		$aMod=explode('.',$t);
		if (sizeof($aMod)>2) $val=$v[$aMod[0]][$aMod[1]][$aMod[2]];
		elseif (sizeof($aMod)>1) $val=$v[$aMod[0]][$aMod[1]];
		else $val=$v[$model][$aMod[0]]; 
				
		if (isset($aOpt['currency']) and is_array($aOpt['currency']) and in_array($t, $aOpt['currency'])) 
			$val=number_format($val, 2, ',', '.');
		elseif (is_array($w) and isset($w['currency']) and $w['currency']==true)	$val=number_format($val, 2, ',', '.');
		elseif (is_array($w) and isset($w['YN']) and $w['YN']) $val=($val)?'Yes':'No';
		elseif (is_array($w) and isset($w['Y/N']) and $w['Y/N']) $val=($val)?'Y':'N';
		elseif (is_array($w) and isset($w['options']) and is_array($w['options'])) $val=$w['options'][$val];

		if (isset($w['function']) and $w['function']=='wordcut'){
			$val=$this->wordcut($val,200);
		}
		
		if (is_array($w) and isset($w['link']) and !empty($w['link'])) {
			if (preg_match('/%s%/', $w['link'])) {
				if (sizeof($aMod)>1) $curId=$v[$model][$inf->underscore($aMod[0]).'_id'];
				else $curId=$v[$model][$id];

				$w['link']=str_replace('%s%',$curId, $w['link']);
			}
			$val=$this->Html->link($val,$w['link']);
		}

		if (isset($w['type']) and $w['type']=='datetime'){
			$sDateFormat='d-m-Y';
			if (isset($w['dateFormat'])) $sDateFormat=$w['dateFormat'];
			if (isset($w['timeFormat'])) $sDateFormat.=' '.$w['timeFormat'];
			$val=($val == '')?'':date($sDateFormat, strtotime($val));
		}
		elseif (isset($w['type']) and $w['type']=='file'){
			$val=$this->Html->link($val, array('action'=>'download', $v[$model][$id]));
		}
		elseif (isset($w['type']) and $w['type']=='image'){
			if (isset($w['crop']) and $w['crop']==true) $val = $this->Image->crop($val, $w['folder'], THUMB_WIDTH, THUMB_WIDTH);
			else $val = $this->Image->resize($val, $w['folder']);
		}
				
		return $val;
	}

	function wordcut($text, $maxlen) {
		$text = strip_tags ($text);
		if(strlen($text) > $maxlen){
			$string = wordwrap($text, $maxlen, '[cut]', 1);
			$string = explode('[cut]', $string);
			$text = $string[0];
		}
		return $text;
	}
}
