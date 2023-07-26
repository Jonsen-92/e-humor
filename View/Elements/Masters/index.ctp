<?php  if (isset($aOpt['script'])) echo $aOpt['script'];?>

<?php $controllername=Inflector::variable($this->name);?>
<div class="<?php echo $controllername;?> index" >
	<h2><?php echo __((isset($aOpt['title_header']) and !empty($aOpt['title_header']))?$aOpt['title_header']:Inflector::humanize(Inflector::underscore($this->name)));?></h2>
	<?php if (!isset($aOpt['display_filter']) or (isset($aOpt['display_filter']) and $aOpt['display_filter']==1)) :?>
	<?php echo $this->General->searchfiltering(isset($aOpt['filter'])?$aOpt['filter']:$aOpt['field']);?>
	<?php endif;?>

	<?php if (isset($aOpt['title_group'])) :?>
	<?php echo $aOpt['title_group'];?>
	<?php endif;?>

	<?php if (isset($aOpt['selectAll'])) echo $this->Form->create($aOpt['selectAll']['model'], array('id'=>'selectAllForm', 'url'=>$aOpt['selectAll']['url']));?>

<!-- table --------------------------------------------------- -->
	<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">

	<tr class="altrow">
		<?php if (isset($aOpt['selectAll'])) echo '<th style=>'.$this->Form->input('selectAllMaster', array('type'=>'checkbox', 'onchange'=>"var cheked=false; if ($(this).attr('checked')=='checked') checked='checked';$('.selectAll').attr('checked',checked);", 'label'=>false,'div'=>false)).'</th>';?>

		<?php foreach ($aOpt['field'] as $i=>$v) : ?>
			<?php
				if (is_array($v) and isset($v['label'])) $w=$v['label'];
				else {
					if (is_array($v)) $w=$i;
					else $w=$v;
					$w=Inflector::humanize($w);
				}
			?>
			<?php $sort=(is_array($v))?$i:$v;?>
			<th style="<?php if (isset($v['align'])) echo 'text-align:'.$v['align'].';'; if (isset($v['width'])) echo 'width:'.$v['width'].';';?>"> <?php echo $this->Paginator->sort($sort,$w);?> <i class="fa fa-sort" class="pull-right"></i></th>
		<?php endforeach; ?>
		<?php if (isset($aOpt['url']) and !empty($aOpt['url'])) : ?><th class="actions"><?php echo __('Actions');?></th><?php endif;?>
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
			<?php  echo $this->General->getValue($i,$w,$v,$aOpt,$model,$id);?>
		&nbsp;</td>
		<?php endforeach; ?>
		<?php if (isset($aOpt['url']) and !empty($aOpt['url'])) : ?>

		<td class="actions col-sm-3">
			<?php if (in_array('view', $aOpt['url'])) : ?>

			<?php echo $this->Html->link(__('VIEW', true), array('action' => 'view', $v[$model][$id]),array('class'=>'btn btn-success btn-xs')); ?>
			<?php endif;?>

			<?php if (in_array('edit', $aOpt['url'])) : ?>
			<?php echo $this->Html->link(__('EDIT', true), array('action' => 'edit', $v[$model][$id]),array('class'=>'btn btn-warning btn-xs')); ?>
			<?php endif;?>

			<?php if (in_array('delete', $aOpt['url'])) : ?>
			<?php echo $this->Form->postLink(__('DELETE'), array('action' => 'delete', $v[$model][$id]), array('class'=>'btn btn-danger btn-xs'), __('Are you sure you want to delete # %s?', $v[$model][$id])); ?>
			<?php endif;?>

			<?php foreach ($aOpt['url'] as $x=>$y) : ?>
				<?php if (is_array($y) and !in_array($x, array('add','view','edit','delete'))) : ?>
					<?php
						$option_link=array();
						$fid=isset($y['fid'])?$y['fid']:$id;

						if (is_array($fid)) {
							$isId=array();
							foreach ($fid as $t) {
								$isRelation=explode('.',$t);
								if (isset($isRelation[1])) $isId[]=$v[$isRelation[0]][$isRelation[1]];
								else $isId[]=$v[$model][$t];
							}
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
							foreach ($fid as $t) {
								$isRelation=explode('.',$t);
								if (isset($isRelation[1])) $option_url[]=$v[$isRelation[0]][$isRelation[1]];
								else $option_url[]=$v[$model][$t];
							}
						}
						else $option_url[]=$v[$model][$fid];
					?>
					<?php if (isset($y['alert'])) : ?>
						<?php
						if(isset($y['post'])) echo $this->Form->postLink(__($y['name'], true), $option_url, $option_link,sprintf(__($y['alert']. ' # %s?', true),$sFid));
						else echo $this->Html->link(__($x, true), $option_url, $option_link, sprintf(__($y['alert']. ' # %s?', true),$sFid)); ?>
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

	<?php
		if (isset($aOpt['selectAll'])) {
			foreach ($aOpt['selectAll']['button'] as $k=>$l){
				echo $this->Form->button($k, $l);
			}
			echo $this->Form->end();
		}
	?>
	<p>
	<?php
	$this->Paginator->options(array('url' => $this->passedArgs));
	echo $this->Paginator->counter(array(
	'format' => __('<small>Page <strong> {:page} </strong> of <strong> {:pages} </strong>, showing <strong> {:current} </strong> records out of <strong> {:count} </strong> total, starting on record <strong> {:start} </strong>, ending on <strong>{:end}</strong> </small>')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo ' &nbsp; ';
		echo $this->Paginator->numbers(array('separator' => ' | '));
		echo ' &nbsp; ';
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<?php if ((isset($aOpt['url']) and $aOpt['url'] and in_array('add', $aOpt['url'])) or isset($aOpt['topurl'])) : ?>
<div class="actions actionsindex">

	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<?php if (isset($aOpt['url']) and $aOpt['url'] and in_array('add', $aOpt['url'])) :?>
		<li >
                    <?php echo $this->Html->link( __('ADD DATA', true), array('action' => 'add'),array('class'=>'btn btn-primary') ); ?>

                </li>
		<?php endif;?>

		<?php if (isset($aOpt['topurl'])) :?>
			<?php
				foreach($aOpt['topurl'] as $x=>$y) {
					$cur_url=is_array($y)?$y['url']:$y;
					$option_link=array();
					if (isset($y['onclick'])) $option_link['onclick']=$y['onclick'];
					echo '<li>';
					if (isset($y['alert']) and !empty($y['alert'])) echo $this->Html->link(strtoupper(__($x, true)), $cur_url, array('class'=>'btn btn-primary'), sprintf(__($y['alert'], true)));
					else echo $this->Html->link(strtoupper(__($x, true)), $cur_url, array('class'=>'btn btn-primary'));
					echo '</li>';
				}
			?>
		<?php endif;?>
	</ul>
</div>
<?php endif;?>

<?php if (isset($addjs)) echo $this->Html->script($addjs);?>
