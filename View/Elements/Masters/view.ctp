<div class="<?php  echo Inflector::variable($this->name);?> view">
    <h2><?php __(isset($data['title_header'])?$data['title_header']:$this->name);?></h2>

	<h2><?php echo __((isset($data['title_header']) and !empty($data['title_header']))?$data['title_header']:Inflector::humanize(Inflector::underscore($_sModel)));?></h2>

        <nav aria-label="Page navigation">
            <?php echo ($simplepaging['first'][$_sModel][$simplepaging['primaryKey']]<>$id) ? $this->Html->link('<< First', array($simplepaging['first'][$_sModel][$simplepaging['primaryKey']]), array('class'=>'btn btn-primary btn-xs')):'<button type="button" class="btn btn-primary btn-xs" disabled><< First</button>';?>
            <?php echo (isset($simplepaging['prev'][$_sModel][$simplepaging['primaryKey']]) and !empty($simplepaging['prev'][$_sModel][$simplepaging['primaryKey']])) ? $this->Html->link('< Prev', array($simplepaging['prev'][$_sModel][$simplepaging['primaryKey']]), array('class'=>'btn btn-primary btn-xs')):'<button type="button" class="btn btn-primary btn-xs" disabled>< Prev</button>';?>
            <?php echo (isset($simplepaging['next'][$_sModel][$simplepaging['primaryKey']]) and !empty($simplepaging['next'][$_sModel][$simplepaging['primaryKey']]))?$this->Html->link('Next >', array($simplepaging['next'][$_sModel][$simplepaging['primaryKey']]), array('class'=>'btn btn-primary btn-xs')):'<button type="button" class="btn btn-primary btn-xs" disabled>Next ></button>';?>
            <?php echo ($simplepaging['last'][$_sModel][$simplepaging['primaryKey']]<>$id)?$this->Html->link('Last >>', array($simplepaging['last'][$_sModel][$simplepaging['primaryKey']]), array('class'=>'btn btn-primary btn-xs')):'<button type="button" class="btn btn-primary btn-xs" disabled>last >></button>';?>
		</nav>

        <div style="float:right;">

	<?php if (isset($data['hUrl']['edit'])) :?>
	<?php echo $this->Html->link(isset($data['title_button']) ? 'Edit '.$data['title_button']:'Edit', (isset($data['hUrl']['edit']) and !empty($data['hUrl']['edit']))?$data['hUrl']['edit'].'/'.$id:array('action'=>'edit',$id), array("class"=>"btn btn-primary btn-xs"));?>
	<?php endif;?>

	<?php if (isset($data['hUrl']['delete'])) :?>
		<?php if (isset($data['hUrl']['edit'])) echo ' | &nbsp; ';?>
		<?php echo $this->Form->postLink(__(isset($data['title_button'])?'Delete '.$data['title_button']:'Delete'), (isset($data['hUrl']['delete']) and !empty($data['hUrl']['delete']))?$data['hUrl']['delete'].'/'.$id:array('action'=>'delete',$id), array("class"=>"btn btn-primary btn-xs"), __('Are you sure you want to delete # %s?', $id)); ?>
	<?php endif;?>

	<?php
		if (isset($data['hUrl'])) {
			$c=1;
			foreach ($data['hUrl'] as $i=>$j) {
				if (!in_array($i, array('edit','delete'))) {
					if ($c<>1 or isset($data['hUrl']['delete']) or isset($data['hUrl']['edit'])) echo ' | ';
					$cur_url=$j['url'];
					unset($j['url']);
					echo $this->Html->link($i, $cur_url, $j);
				}
				$c++;
			}
		}
	?>
        </div>

    <div style="height:5px;clear:both;">&nbsp;</div>


    <div class="viewheader" id="actluar">
		<div style="float:left;<?php echo (isset($data['hCol']) and $data['hCol']==2)?'width:45%;':'';?>">
			 <?php
				$c=1;
				foreach ($data['field'] as $i=>$v) :
                if (is_array($v)) {
                    $label=(isset($v['label']))?$v['label']:$i;
                    $t=$i;
                }
                else {
                    $label=$v;
                    $t=$v;
                }
	        ?>
				<div style="padding-left:10px;float:left;width:200px;"><?php echo Inflector::humanize($label); ?></div>
				<div style="float:left;"><?php echo $this->General->getValue($i,$v,$val,$data,$_sModel, $primaryKey);?></div>
				<div style="clear:both;height:4px;">&nbsp;</div>

				<?php if (isset($data['hCol']) and $data['hCol']==2 and ceil(count($data['field'])/2)==$c) echo '</div><div style="width:45%;float:right;">';?>
			<?php $c++; endforeach;?>
		</div>
		<div style="clear:both;height:0;">&nbsp;</div>
    </div>

	<?php
	if (isset($data['detail'])) {
		foreach ($data['detail'] as $model=>$value) {
			if (isset($val[$model]) and !empty($val[$model])) {
				if (isset($data['title_detail'])) echo '<h2>'.$data['title_detail'].'</h2>';
				else echo '<h2>'.Inflector::humanize(Inflector::underscore($model)).'</h2>';
				echo '<div style="height: 350px;overflow: auto;overflow-x: hidden;white-space: nowrap"> <table class="table table-striped table-hover" ';
				if (isset($value['style'])) {echo ' style="'.$value['style'].'"';unset($value['style']);}
				if (isset($value['class'])) {echo ' style="'.$value['class'].'"';unset($value['class']);}
				echo '><tr>';
				$footerField=false;
				if (isset($value['footerField'])) {$footerField=$value['footerField'];unset($value['footerField']);}
				$totalField=sizeof($value);
				foreach($value as $i=>$v){
					if (is_array($v)) {
		                $label=(isset($v['label']))?$v['label']:Inflector::humanize($i);
		                $t=$i;
		            }
		            else {
		                $label=Inflector::humanize($v);
		                $t=$v;
		            }
					echo '<th>'.$label.'</th>';
				}
				echo '</tr>';
				foreach ($val[$model] as $j=>$k){
					$k=array($model=>$k);
					echo '<tr>';
					foreach($value as $i=>$v){
						echo '<td';
						if (is_array($v) and isset($v['align'])) echo ' style="text-align:'.$v['align'].'"';
						echo '>'.$this->General->getValue($i,$v,$k,$data,$model).'</td>';
					}
					echo '</tr>';
				}
				if ($footerField){
					$key=array_keys($footerField);
					$model=$key[0]; unset($key);
					foreach ($footerField[$model] as $i=>$v){
						echo '<tr>';
						if ($i=='multiField') {
							for ($c=1;$c<=$totalField;$c++){
								echo '<td>';
								foreach ($v as $in=>$va) {
									if ($c==$va['kolom']) {
										echo '<label ';
										if (isset($va['label']['style'])) echo $va['label']['style'];
										echo '">';
										if (isset($va['label']) and !is_array($va['label'])) echo $va['label'];
										elseif (isset($va['label']['value'])) echo $va['label']['value'];
										else echo Inflector::humanize($in);
										echo '</label>';
									}
									elseif ($c==$va['kolom']+1){
										unset($va['kolom']);

										echo '<label style="width:98%;';
										if (isset($va['align'])) echo 'text-align:'.$va['align'];						

										echo '<label' ;
										if (isset($va['align'])) echo 'text-align:'.$va['align'];

										echo '">';
										echo $this->General->getValue($in,$va,$val,null,$model);
										echo '</label>';
									}
									else echo '';//&nbsp;
								}
								echo '</td>';
							}
						}
						else {
							for ($c=1;$c<=$totalField;$c++){
								echo '<td';
								if (is_array($v) and isset($v['align'])) echo ' style="text-align:'.$v['align'].'"';
								echo '>';
								if ($c==$v['kolom']) {
									echo '<label style="width:95%;';
									if (isset($v['label']['style'])) echo $v['label']['style'];
									echo '">';
									if (isset($v['label']) and !is_array($v['label'])) echo $v['label'];
									elseif (isset($v['label']['value'])) echo $v['label']['value'];
									else echo Inflector::humanize($i);
									echo '</label>';
								}
								elseif ($c==$v['kolom']+1){
									unset($v['kolom']);
									$v['label']=false;
									echo $this->General->getValue($i,$v,$val,null,$model);
								}
								else echo '&nbsp;';
								echo '</td>';
							}
						}
						echo '</tr>';
					}
				}
				echo '</table></div>';
			}
		}
	}
	?>
</div>
