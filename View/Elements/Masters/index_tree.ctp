<style>
td, th {

    padding: 5px;

}
a {

    padding: 5px;

}
</style>
<div class="<?php echo $this->name;?> index">
    <br/>
	<h2><?php echo __((isset($aOpt['title_header']) and !empty($aOpt['title_header']))?$aOpt['title_header']:$this->name);?></h2>
	<?php
    	if (isset($aOpt['title_group'])) echo $aOpt['title_group'];

        if (isset($aOpt['selectAll'])) echo $this->Form->create($aOpt['selectAll']['model'], array('id'=>'selectAllForm', 'url'=>$aOpt['selectAll']['url']));
    ?>

	<table cellpadding="0" cellspacing="0">
	<tr>
		<?php if (isset($aOpt['selectAll'])) echo '<th style="width:20px;">'.$this->Form->input('selectAllMaster', array('type'=>'checkbox', 'onchange'=>"$('.selectAll').attr('checked',$(this).attr('checked'))", 'label'=>false,'div'=>false)).'</th>';?>

		<?php foreach ($aOpt['field'] as $i=>$v) : ?>
			<?php
				if (is_array($v) and isset($v['label'])) $w=$v['label'];
				else {
					if (is_array($v)) $w=$i;
					else $w=$v;
				}
			?>
			<th style="<?php if (isset($v['align'])) echo 'text-align:'.$v['align'].';'; if (isset($v['width'])) echo 'width:'.$v['width'].';';?>"><?php echo $w;?></th>
		<?php endforeach; ?>
		<?php if (isset($aOpt['url']) and !empty($aOpt['url'])) : ?><th class="actions"><?php echo __('Actions');?></th><?php endif;?>
	</tr>
        <?php $nameVar=$this->name; echo $this->General->getTreeView($aOpt, $model, $id, $$nameVar);?>

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

<!-- start tampilkan link top url -->
<?php if ((isset($aOpt['url']) and $aOpt['url'] and in_array('add', $aOpt['url'])) or isset($aOpt['topurl'])) : ?>
<div class="actions actionsindex">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<?php if (isset($aOpt['url']) and $aOpt['url'] and in_array('add', $aOpt['url'])) :?>
		<li><?php echo $this->Html->link(__('ADD DATA', true), array('action' => 'add'), array('class' => 'btn btn-primary')); ?></li>
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
<!-- end tampilkan link top url -->

<?php if (isset($addjs)) echo $this->Html->script($addjs);?>
