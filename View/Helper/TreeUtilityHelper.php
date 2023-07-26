<?php
	class TreeUtilityHelper extends AppHelper{
		
		function listtree($aArrayAsli, $_model, $class=null, $active=0, $title='title', $id='id', $link='url', $parentId='parent_id', $curParent=0){
			//pr($this);
			$data=array();
			foreach ($aArrayAsli as $v){
				if (empty($v[$_model][$parentId])) $v[$_model][$parentId]=0;
				$data[$v[$_model][$parentId]][]=$v;
			}
			$output=$this->loop($data, $_model, $class, $active=0, $title='title', $id='id', $link='url', $parentId='parent_id', $curParent=0);
			return $this->output($output);
		}
		
		function loop($data, $_model, $class=null, $active=0, $title='title', $id='id', $link='url', $parentId='parent_id', $curParent=0){
			if(isset($data[$curParent])){ // jika ada anak dari menu maka tampilkan
				/* setiap menu ditampilkan dengan tag <ul> dan apabila nilai $curParent bukan 0 maka sembunyikan element 
				 * karena bukan merupakan menu utama melainkan sub menu */
				//$str = '<ul class="'.$class.'" id="parent'.$curParent.'" style="display:'.($curParent>1?'none':'').'">';
				$str = '<ul class="'.$class.'" id="parent'.$curParent.'" >';				
				foreach($data[$curParent] as $v){ //pr($v); echo '<hr/>';
					/* variable $child akan bernilai sebuah string apabila ada sub menu dari masing-masing menu utama
					 * dan akan bernilai negatif apabila tidak ada sub menu */
					$child = $this->loop($data,$_model, $class, $active, $title, $id, $link, $parentId, $v[$_model][$id]); 
					$str .= '<li>';
					/* beri tanda sebuah folder dengan warna yang mencolok apabila terdapat sub menu di bawah menu utama 	  	   
					 * dan beri juga event javascript untuk membuka sub menu di dalamnya */
					$str .= ($child) ? '<a href="javascript:openTree('.$v[$_model][$id].')">':'';
					$str .= '<div style="float:left;width:20px;margin-right:5px;text-align:center;" id="span'.$v[$_model][$id].'">';
					//if ($child and $v[$_model][$id]<>1) $str .= '+';
					//elseif ($child) $str .= '-';
					if ($child) $str .= '-';
					else $str .= '&nbsp;';
					$str .= '</div>';
					$str .= ($child)?'</a>':'';
					$str .= (!empty($v[$_model][$link]))?'<a href="'.$this->base.$v[$_model][$link].'" >'.$v[$_model][$title].'</a>':$v[$_model][$title];
					$str .= '</li>';
					if($child) $str .= $child;
				}
				$str .= '</ul>';
				return $str;
			}else return false;	 
			
		}
		
		function checkboxtree($aArrayAsli, $_model, $title='title', $script=null, $active=array(), $id='id', $parentId='parent_id', $curParrent=0, $classParrent=array()){
			if (!isset($output)) $output='';
			$output.='<ul class="ulTree">';
			foreach ($aArrayAsli as $i=>$v){
				unset($aArrayAsli[$i]); 
				if ($curParrent==$v[$_model][$parentId]) {	
					if (!in_array($curParrent, $classParrent) and $curParrent<>0) $classParrent[]=$curParrent;
					$output.='<li>&nbsp; <input class="'.implode(' ', $classParrent).'" type="checkbox" name="acotree['.$v[$_model][$id].']" id="'.$v[$_model][$id].'" ';
					if (!is_null($script)) $output.=' onclick="'.$script.'(this.id)" '; 
					if (in_array($v[$_model][$id], $active)) $output.=' checked ';
					$output.=' />'.$v[$_model][$title].'</li>';
					$output.=$this->checkboxtree($aArrayAsli, $_model, $title, $script, $active, $id, $parentId, $v[$_model][$id], $classParrent);						
				}
			}
			$output.='</ul>';
			
			$output=str_replace('<ul class="ulTree"></ul>','',$output);
			
			return $this->output($output);
		}
		
		function optionsTree($aArrayAsli, $_model, $active=null, $title='title', $id='id', $parentId='parent_id', $curParrent=0, $i=0){
			
			foreach ($aArrayAsli as $j=>$v){
				unset($aArrayAsli[$j]); 
				if ($curParrent==$v[$_model][$parentId]) {	
					$output.='<option value="'.$v[$_model][$id].'"';
					if ($v[$_model][$id]==$active) $output.=' selected ';
					$output.='>';
					for($t=0;$t<$i;$t++) $output.=' - - - ';
					$output.=$v[$_model][$title].'</option>';
					if ($curParrent==$v[$_model][$parentId]) {	
						$output.=$this->optionsTree($aArrayAsli, $_model, $active, $title, $id, $parentId, $v[$_model][$id], $i+1);						
					}
				}
			}
			return $this->output($output);
			
		}
	}
?>
