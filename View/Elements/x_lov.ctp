<?php //pr($model); pr($sField); pr($aField); pr($$model);
//pr($this->passedArgs);
?>
<div style="height: 210px;overflow: auto;overflow-x: hidden;white-space: nowrap">
<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
	<tr>
		<?php foreach ($aField as $j=>$v) : $label=str_replace('_',' ',substr($j,7)); $v=str_replace('__','.',$v); 
		
		//echo (stripos($label,'Hidden'))?$label:2;?>
			<th <?php echo (stripos($label,'Hidden') > -1)?"style='display:none'":""?>><?php //echo $this->Paginator->sort($v,$label);
			echo $label;
			?></th>
		<?php endforeach;?>
	</tr>
	<?php
	$i = 0;
	foreach ($$model as $v):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<?php foreach ($aField as $j=>$k) : $l=explode('__',$k); $label2=str_replace('_',' ',substr($j,7));?>
		<td <?php echo (stripos($label2,'Hidden') > -1)?"style='display:none'":""?>><a id="<?php echo $k.$i;?>" href="javascript:defaultlov(<?php echo $i;?>);">
		<?php 
			if (strpos($l[0], ".") > -1) $otherTable = explode(".", $l[0]);
		?>
		<?php echo (isset($l[1]) ? $v[$l[0]][$l[1]] : ( strpos($l[0], ".") > -1 ? $v[$otherTable[0]][$otherTable[1]] : $v[$model][$l[0]])); ?>
			
		</a>&nbsp;</td>
                <?php endforeach;?>
	</tr>
<?php endforeach; ?>
	</table></div>
	<p>
	<?php 
	$this->passedArgs['order']=$aOrder;
	$this->Paginator->options(array('url' => $this->passedArgs, 'update' => '#isidataajax', 'evalScripts' => true));
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging" style="margin:0 0 0 0;">
		<?php 
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo ' &nbsp; '; 
		echo $this->Paginator->numbers(array('separator' => ' | '));
		echo ' &nbsp; '; 
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
		?>
	</div>
	
	<?php echo $this->Js->writeBuffer();?>
