<?php if (isset($data['script'])) echo '<script type="text/javascript">'.$data['script'].'</script>';?>		
<div class="<?php echo Inflector::variable($this->name);?> form">
	<h2><?php echo __((isset($data['title_header']))?$data['title_header']:Inflector::humanize(Inflector::underscore($model)));?></h2>
	
	<div style="float:left;">
	<?php echo ($simplepaging['first'][$model][$simplepaging['primaryKey']]<>$id)?$this->Html->link('<< First', array($simplepaging['first'][$model][$simplepaging['primaryKey']]), array('style'=>'margin-right:50px;text-decoration:none;')):'<span style="margin-right:50px;font-weight:bold;"><< First</span>';?>
	<?php echo (isset($simplepaging['prev'][$model][$simplepaging['primaryKey']]) and !empty($simplepaging['prev'][$model][$simplepaging['primaryKey']]))?$this->Html->link('< Prev', array($simplepaging['prev'][$model][$simplepaging['primaryKey']]), array('style'=>'margin-right:50px;text-decoration:none;')):'<span style="margin-right:50px;font-weight:bold;">< Prev</span>';?>
	<?php echo (isset($simplepaging['next'][$model][$simplepaging['primaryKey']]) and !empty($simplepaging['next'][$model][$simplepaging['primaryKey']]))?$this->Html->link('Next >', array($simplepaging['next'][$model][$simplepaging['primaryKey']]), array('style'=>'margin-right:50px;text-decoration:none;')):'<span style="margin-right:50px;font-weight:bold;">Next ></span>';?>
	<?php echo ($simplepaging['last'][$model][$simplepaging['primaryKey']]<>$id)?$this->Html->link('Last >>', array($simplepaging['last'][$model][$simplepaging['primaryKey']]), array('style'=>'margin-right:50px;text-decoration:none;')):'<span style="margin-right:50px;font-weight:bold;">Last >></span>';?>
	
	</div>
	<div style="float:right;">
	<?php if (isset($data['hUrl']['edit'])) :?>
	<?php echo $this->Html->link(isset($data['title_button'])?'Edit '.$data['title_button']:'Edit '.Inflector::humanize(Inflector::underscore($model)), (isset($data['hUrl']['edit']) and !empty($data['hUrl']['edit']))?$data['hUrl']['edit'].'/'.$id:array('action'=>'edit',$id), array('style'=>'margin-left:0px;text-decoration:none;'));?>
	<?php endif;?>
	
	<?php if (isset($data['hUrl']['delete'])) :?>
		<?php if (isset($data['hUrl']['edit'])) echo ' | ';?>
		<?php echo $this->Form->postLink(__(isset($data['title_button'])?'Delete '.$data['title_button']:'Delete '.Inflector::humanize(Inflector::underscore($model))), (isset($data['hUrl']['delete']) and !empty($data['hUrl']['delete']))?$data['hUrl']['delete'].'/'.$id:array('action'=>'delete',$id), array('style'=>'margin-left:0px;text-decoration:none;'), __('Are you sure you want to delete # %s?', $id)); ?>
	<?php endif;?>
	
	<?php 
		if (isset($data['hUrl'])) {
			$c=1;			
			foreach ($data['hUrl'] as $i=>$j) {
				if (!in_array($i, array('edit','delete'))) {
					if ($c<>1 or isset($data['hUrl']['delete']) or isset($data['hUrl']['edit'])) echo ' | ';
					$cur_url=$j['url'];
					unset($j['url']);
				
					$alert=isset($j['alert'])?$j['alert']:'';
					if (isset($j['alert'])) unset($j['alert']);

					if (!empty($alert)) echo $this->Html->link($i, $cur_url, $j, sprintf(__($alert, true)));
					else echo $this->Html->link($i, $cur_url, $j);
				}
				$c++;
			}
		}
	?>
	
    </div>
	<div style="height:5px;clear:both;">&nbsp;</div>

	<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model);?>
	<?php echo $this->MasterDetail->getFormMasterDetail($data, $model, true);?>
	</form>
</div>
<?php echo $this->Html->script('script');?>
<?php if (isset($addjs)) echo $this->Html->script($addjs);?>
