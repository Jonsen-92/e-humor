<?php if (isset($aOpt['script'])) echo $aOpt['script'];?>
<?php $inf=new Inflector(); $controllername=$inf->variable($this->name);?>
<div class="<?php echo $controllername;?> index">
	<h2><?php __((isset($aOpt['title_header']) and !empty($aOpt['title_header']))?$aOpt['title_header']:$this->name);?></h2>
	<?php if (!isset($aOpt['display_filter']) or (isset($aOpt['display_filter']) and $aOpt['display_filter']==1)) :?>
	<?php echo $this->General->searchfiltering(isset($aOpt['filter'])?$aOpt['filter']:$aOpt['field']);?>
	<?php endif;?>
	
	<?php if (isset($aOpt['title_group'])) :?>
	<?php echo $aOpt['title_group'];?>
	<?php endif;?>
	
	<?php if (isset($aOpt['selectAll'])) echo $this->Form->create($aOpt['selectAll']['model'], array('id'=>'selectAllForm', 'url'=>$aOpt['selectAll']['url']));?>
	
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<?php if (isset($aOpt['selectAll'])) echo '<th style="width:20px;">'.$this->Form->input('selectAllMaster', array('type'=>'checkbox', 'onchange'=>"$('.selectAll').attr('checked',$(this).attr('checked'))", 'label'=>false,'div'=>false)).'</th>';?>
	
		<?php foreach ($aOpt['field'] as $i=>$v) : ?>
			<?php
				if (is_array($v) and isset($v['label'])) $w=$v['label'];
				else {
					if (is_array($v)) $w=$i;
					else $w=$v;
					$w=$inf->humanize($w);
				}
			?>
			<?php $sort=(is_array($v))?$i:$v;?>
			<th style="padding-left:5px;<?php if (isset($v['align'])) echo 'text-align:'.$v['align'].';'; if (isset($v['width'])) echo 'width:'.$v['width'].';';?>"><?php echo $w?></th>
		<?php endforeach; ?>
		<?php if (isset($aOpt['url']) and !empty($aOpt['url'])) : ?><th class="actions"><?php __('Actions');?></th><?php endif;?>
	</tr>
	<?php
	$counter = 0;
	foreach ($$controllername as $v): //pr($v);
		$class = null;
		if ($counter++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?> id="<?php echo $counter;?>" rel="<?php echo $v[$model][$id];?>">
		<?php if (isset($aOpt['selectAll'])) echo '<td style="width:20px;">'.$this->Form->input('selectAll['.$v[$model][$id].']', array('type'=>'checkbox', 'class'=>'selectAll','label'=>false, 'div'=>false)).'</td>';?>
	
		<?php foreach ($aOpt['field'] as $i=>$w) : ?>
		<td id="<?php echo $i.$counter;?>" style="<?php if (isset($w['align'])) echo 'text-align:'.$w['align'].';'; if (isset($w['width'])) echo 'width:'.$w['width'].';';?>">
			<?php  
				$t=(is_array($w))?$i:$w;
				$aMod=explode('.',$t);
				if (sizeof($aMod)>2) $val=$v[$aMod[0]][$aMod[1]][$aMod[2]];
				elseif (sizeof($aMod)>1) $val=$v[$aMod[0]][$aMod[1]];
				else $val=$v[$model][$aMod[0]]; 
				
				if (isset($aOpt['currency']) and is_array($aOpt['currency']) and in_array($t, $aOpt['currency'])) 
					$val=number_format($val, 2, ',', '.');
				elseif (is_array($w) and isset($w['YN']) and $w['YN']) $val=($val)?'Yes':'No';

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
					$val=date($sDateFormat, strtotime($val));
				}
				elseif (isset($w['type']) and $w['type']=='file'){
					$val=$this->Html->link($val, array('action'=>'download', $v[$model][$id]));
				}
				elseif (isset($w['type']) and $w['type']=='image'){
					if (isset($w['contentType'])) {
						$imageField=(sizeof($aMod)>1)?$aMod[1]:$aMod[0];
						$val='<img src="'.WEB_PROTOCOL.'://'.$_SERVER['SERVER_NAME'].'/'.$this->base.'/common/image/'.$v[$model][$id].'/'.$model.'/'.$imageField.'/'.$w['contentType']['db'].'" width="'.THUMB_WIDTH.'px"/>';
					}
					elseif ($w['fullpath']==true){
						$val='<img src="'.$this->Html->url($val).'" width="'.THUMB_WIDTH.'px"/>';
					}
					else $val='<img src="'.$this->Html->url(FILES_PATH.$w['folder'].'/'.$val).'" width="'.THUMB_WIDTH.'px"/>';
				}
				
				if (isset($w['options']) and is_array($w['options'])) $val=$w['options'][$val];
				echo $val;
			?>
		&nbsp;</td>
		<?php endforeach; ?>
		<?php if (isset($aOpt['url']) and !empty($aOpt['url'])) : ?>
		<td class="actions">
			<?php if (in_array('view', $aOpt['url'])) : ?>
			<?php echo $this->Html->link(__('VIEW', true), array('action' => 'view', $v[$model][$id])); ?>
			<?php endif;?>
			
			<?php if (in_array('edit', $aOpt['url'])) : ?>
			<?php echo $this->Html->link(__('EDIT', true), array('action' => 'edit', $v[$model][$id])); ?>
			<?php endif;?>
			
			<?php if (in_array('delete', $aOpt['url'])) : ?>
			<?php echo $this->Html->link(__('DELETE', true), array('action' => 'delete', $v[$model][$id]), null, sprintf(__('Are you sure you want to delete # %s?', true), $v[$model][$id])); ?>
			<?php endif;?>
			
			<?php foreach ($aOpt['url'] as $x=>$y) : ?>
				<?php if (is_array($y) and !in_array($x, array('add','view','edit','delete'))) : ?>
					<?php
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
								
								//if ($l=='%s') $l=$v[$model][$id];
								/*else {
									$isXCode=explode('%',$l);
									if (isset($isXCode[1])) $l=$v[$model][$isXCode[1]];
								}*/
								if (!in_array($k, array('uac','alert'))) $option_link[$k]=$l;
							}
						}
					
						$option_url=array();
						$option_url['action']=$y['uac'];
						if (is_array($fid)) {
							foreach ($fid as $t) $option_url[]=$v[$model][$t];
						}
						else $option_url[]=$v[$model][$fid];
					?>
					<?php if (isset($y['alert'])) : ?>
						<?php echo $this->Html->link(__($x, true), $option_url, $option_link, sprintf(__($y['alert']. ' # %s?', true), $v[$model][$fid])); ?>
					<?php else : ?>
						<?php echo $this->Html->link(__($x, true), $option_url, $option_link); ?>
					<?php endif;?>
				<?php endif; ?>
			<?php endforeach;?>
			
		</td>
		<?php endif;?>
	</tr>
<?php endforeach; ?>

	</table>
	
	<table width="100%"  align="right">
	<tr><td class="actions"align="right" style="text-align:right;font-size:14px;border-bottom:0px">
		<?php echo $this->Html->link(strtoupper(__('ADD '.((isset($aOpt['title_button']) and !empty($aOpt['title_button']))?$aOpt['title_button']:$model), true)), array('action' => 'add')); ?>
	</td></tr>
	</table>	
	<?php 
		if (isset($aOpt['selectAll'])) {
			foreach ($aOpt['selectAll']['button'] as $k=>$l){
				echo $this->Form->button($k, $l);
			}
			echo $this->Form->end();
		}
	?>
	
	

	
</div>
<?php if ((isset($aOpt['url']) and $aOpt['url'] and in_array('add', $aOpt['url'])) or isset($aOpt['topurl'])) : ?>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<?php if (isset($aOpt['url']) and $aOpt['url'] and in_array('add', $aOpt['url'])) :?>
		<li><?php echo $this->Html->link(strtoupper(__('ADD '.((isset($aOpt['title_button']) and !empty($aOpt['title_button']))?$aOpt['title_button']:$model), true)), array('action' => 'add')); ?></li>
		<?php endif;?>
		
		<?php if (isset($aOpt['topurl'])) :?>
			<?php 
				foreach($aOpt['topurl'] as $x=>$y) { 
					$cur_url=is_array($y)?$y['url']:$y; 
					$option_link=array();
					if (isset($y['onclick'])) $option_link['onclick']=$y['onclick'];
					echo '<li>';
					if (isset($y['alert']) and !empty($y['alert'])) echo $this->Html->link(strtoupper(__($x, true)), $cur_url, $option_link, sprintf(__($y['alert'], true)));
					else echo $this->Html->link(strtoupper(__($x, true)), $cur_url, $option_link);
					echo '</li>';
				}
			?>
		<?php endif;?>
	</ul>
</div>
<?php endif;?>

<?php if (isset($addjs)) echo $this->Html->script($addjs);?>