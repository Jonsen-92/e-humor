<div class="print1">
    <table class="header" width="100%" style="font-size:10px;">
		<tr valign="top">
		<?php if (isset($data['header'])) : ?>
			<td align="<?php echo $data['header']['left']['align'];?>" width="<?php echo $data['header']['left']['width'];?>"><?php if (isset($data['header']['left']['headline'])) echo '<h'.$data['header']['left']['headline'].'>';?><?php echo $data['header']['left']['text'];?><?php if (isset($data['header']['left']['headline'])) echo '</h'.$data['header']['left']['headline'].'>';?></td>
			<td align="<?php echo $data['header']['center']['align'];?>" width="<?php echo $data['header']['center']['width'];?>"><?php if (isset($data['header']['center']['headline'])) echo '<h'.$data['header']['center']['headline'].'>';?><?php echo $data['header']['center']['text'];?><?php if (isset($data['header']['center']['headline'])) echo '</h'.$data['header']['center']['headline'].'>';?></td>
			<td align="<?php echo $data['header']['right']['align'];?>" width="<?php echo $data['header']['right']['width'];?>">
				<?php if (isset($data['header']['right']['headline'])) echo '<h'.$data['header']['right']['headline'].'>';?>
				<?php echo $data['header']['right']['text'];?>
				<?php if (isset($data['header']['right']['headline'])) echo '</h'.$data['header']['right']['headline'].'>';?>
			</td>
		<?php endif;?>
		</tr>
	</table>
	
    <div class="headerData">
		<div style="float:left;<?php echo (isset($data['hCol']) and $data['hCol']==2)?'width:49%;':'';?>">
			<?php $c=1; foreach ($data['hField'] as $i=>$j) : ?>
				<?php if (is_array($j)) : $t=$i; ?>
				<div style="float:left;width:185px;"><?php echo isset($j['label'])?$j['label']:$i;?></div>
				<?php else : $t=$j; ?>
				<div style="padding-left:10px;float:left;width:185px;"><?php echo ucwords(str_replace('_',' ',$j));?></div>
				<?php endif;?>
				<div style="float:left;">
					<?php
						$t=explode('.',$t);
						$val=isset($t[1])?$data['hData'][$t[0]][$t[1]]:$data['hData'][$data['hModel']][$t[0]];

						$i_other=explode('.',$i);
						if (!isset($i_other[1])) $i_other=$data['hModel'].'.'.$i;
						
						if (isset($data['hCurrency']) and is_array($data['hCurrency']) and (in_array($i, $data['hCurrency']) or in_array($i_other, $data['hCurrency'])))
							$val=number_format($val, 2, ',', '.');

						if (isset($j['type']) and $j['type']=='datetime') {
							$sDateFormat='d-m-Y';
							if (isset($j['dateFormat'])) $sDateFormat=$j['dateFormat'];
							if (isset($j['timeFormat'])) $sDateFormat.=' '.$j['timeFormat'];
							$val=date($sDateFormat, strtotime($val));
						}
						
						if (isset($j['options'])) $val=$j['options'][$val];
						
						echo $val;
					?>
				</div>
				<div style="clear:both;height:4px;">&nbsp;</div>
				
				<?php if (isset($data['hCol']) and $data['hCol']==2 and ceil(count($data['hField'])/2)==$c) echo '</div><div style="width:49%;float:right;">';?>
			<?php $c++; endforeach;?>
		</div>
		<div style="clear:both;height:0;">&nbsp;</div>
    </div>
	
	<table class="detailData" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<?php foreach ($data['dField'] as $i=>$j) : ?>
			<?php if (is_array($j)) : ?>
				<th style="border-top:1px solid #000;border-bottom:1px solid #000;<?php if (isset($j['align'])) echo 'text-align:'.$j['align'].';'; if (isset($j['width'])) echo 'width:'.$j['width'].';';?>"><?php echo $j['label'];?></th>
			<?php else :?>
				<th style="border-top:1px solid #000;border-bottom:1px solid #000;<?php if (isset($j['align'])) echo 'text-align:'.$j['align'].';'; if (isset($j['width'])) echo 'width:'.$j['width'].';';?>"><?php echo $j;?></th>
			<?php endif;?>
		<?php endforeach;?>
	</tr>
	<?php foreach ($data['dData'] as $v): ?>
	<tr valign="top">
		<?php foreach ($data['dField'] as $i=>$j) : ?>
			<?php
				$t0=(is_array($j))?$i:$j;
				$t=explode('.',$t0);
				if(isset($t[1]) and !empty($t[1])){
					$val=(isset($data['dCurrency']) and in_array($t0, $data['dCurrency']))?number_format($v[$t[0]][$t[1]],2,',','.'):$v[$t[0]][$t[1]];
				}
				else {
					$val=(isset($data['dCurrency']) and in_array($t0, $data['dCurrency']))?number_format($v[$data['dModel']][$t[0]],2,',','.'):$v[$data['dModel']][$t[0]];
				}

				if (is_array($j) and isset($j['link']) and !empty($j['link'])) {
					if (preg_match('/%s%/', $j['link'])) {
						if (sizeof($aMod)>1) $curId=$v[$data['hModel']][$inf->underscore($aMod[0]).'_id'];
						else $curId=$v[$data['hModel']][$id];

						$j['link']=str_replace('%s%',$curId, $j['link']);
					}
					$val=$this->Html->link($val,$j['link']);
				}

				if (isset($j['type']) and $j['type']=='datetime'){
					$sDateFormat='d-m-Y';
					if (isset($j['dateFormat'])) $sDateFormat=$j['dateFormat'];
					if (isset($j['timeFormat'])) $sDateFormat.=' '.$j['timeFormat'];
					$val=date($sDateFormat, strtotime($val));
				}
				elseif (isset($j['type']) and $j['type']=='file'){
					$val=$this->Html->link($val, array('action'=>'download', $v[$data['hModel']][$id]));
				}
				elseif (isset($j['type']) and $j['type']=='image'){
					if (isset($j['contentType'])) {
						$imageField=(sizeof($aMod)>1)?$aMod[1]:$aMod[0];
						$val='<img src="http://'.$_SERVER['SERVER_NAME'].'/'.$this->base.'/common/image/'.$v[$data['hModel']][$id].'/'.$data['hModel'].'/'.$imageField.'/'.$j['contentType']['db'].'" width="'.THUMB_WIDTH.'px"/>';
					}
					elseif ($j['fullpath']==true){
						$val='<img src="'.$this->Html->url($val).'" width="'.THUMB_WIDTH.'px"/>';
					}
					else $val='<img src="'.$this->Html->url(FILES_PATH.$j['folder'].'/'.$val).'" width="'.THUMB_WIDTH.'px"/>';
				}
				
				if (isset($j['options'])) $val=$j['options'][$val];
			?>
			<td style="<?php if (isset($j['align'])) echo 'text-align:'.$j['align'].';'; if (isset($j['width'])) echo 'width:'.$j['width'].';';?>"><?php echo $val;?></td>
		<?php endforeach;?>
	</tr>

	<?php endforeach; ?>
	
	<tr valign="top">
		<td  style="border-top:1px solid #000;" colspan="<?php echo (sizeof($data['dField'])-2);?>" rowspan="<?php echo (sizeof($data['fField']));?>">
			<?php if (isset($data['catatan'])) : ?>
				Catatan :<br/>
				<?php echo $data['catatan'];?>
			<?php endif;?>
		</td>
		
		<?php if (isset($data['fField'])) :?>
			<td style="border-top:1px solid #000;text-transform:capitalize;">
			<?php foreach ($data['fField'] as $i=>$j) : ?>
				<?php 
					if (is_array($j)) echo isset($j['label'])?$j['label']:$i;
					else echo ucwords(str_replace('_',' ',$j));
				?>
				<br/>
			<?php endforeach;?>
			</td>
			
			<td align="right" style="border-top:1px solid #000;">			
			<?php foreach ($data['fField'] as $i=>$j) : ?>
				<?php 
					if (is_array($j)) $t=$i; 
					else $t=$j; 
				?>
				
				<?php
					$t=explode('.',$t);
					
					if (is_array($j) and isset($j['default']) and !empty($j['default'])) $val=$j['default'];
					elseif (sizeof($t)>1) $val=$data['hData'][$t[0]][$t[1]];
					else $val=$data['hData'][$data['hModel']][$t[0]]; 
			
					$i_other=explode('.',$i);
					if (!isset($i_other[1])) $i_other=$data['hModel'].'.'.$i;
					
					if (isset($data['fCurrency']) and is_array($data['fCurrency']) and (in_array($i, $data['fCurrency']) or in_array($i_other, $data['fCurrency'])))
						$val=number_format($val, 2, ',', '.');

					if (isset($j['type']) and $j['type']=='datetime') {
						$sDateFormat='d-m-Y';
						if (isset($j['dateFormat'])) $sDateFormat=$j['dateFormat'];
						if (isset($j['timeFormat'])) $sDateFormat.=' '.$j['timeFormat'];
						$val=date($sDateFormat, strtotime($val));
					}
					
					if (isset($j['options'])) $val=$j['options'][$val];
					
					echo $val;
				?>
				<br/>
			<?php endforeach;?>
			</td>
		<?php endif;?>
	</tr>
	</table>
	<?php if (isset($data['beforeTandaTangan'])) : ?>
	<div style="text-align:<?php echo $data['beforeTandaTangan']['align'];?>"><?php echo $data['beforeTandaTangan']['text'];?></div>
	<?php endif;?>
	<?php if (isset($data['tandaTangan'])) : ?>
	<table class="tandaTangan" width="100%" style="font-size:10px;">
		<tr valign="top">
			<td align="<?php echo $data['tandaTangan']['left']['align'];?>" width="<?php echo $data['tandaTangan']['left']['width'];?>"><?php echo $data['tandaTangan']['left']['text'];?></td>
			<td align="<?php echo $data['tandaTangan']['center']['align'];?>" width="<?php echo $data['tandaTangan']['center']['width'];?>"><?php echo $data['tandaTangan']['center']['text'];?></td>
			<td align="<?php echo $data['tandaTangan']['right']['align'];?>" width="<?php echo $data['tandaTangan']['right']['width'];?>"><?php echo $data['tandaTangan']['right']['text'];?></td>
		</tr>
		<tr><td colspan="3" height="<?php echo $data['tandaTangan']['height'];?>">&nbsp;</td></tr>
		<tr valign="top">
			<td align="<?php echo $data['tandaTangan']['left']['align'];?>" width="<?php echo $data['tandaTangan']['left']['width'];?>"><?php echo $data['tandaTangan']['left']['name'];?></td>
			<td align="<?php echo $data['tandaTangan']['center']['align'];?>" width="<?php echo $data['tandaTangan']['center']['width'];?>"><?php echo $data['tandaTangan']['center']['name'];?></td>
			<td align="<?php echo $data['tandaTangan']['right']['align'];?>" width="<?php echo $data['tandaTangan']['right']['width'];?>"><?php echo $data['tandaTangan']['right']['name'];?></td>
		</tr>
	</table>
	<?php endif;?>
</div>