<div style="width:100%">

	<?php if (isset($data['header'])) :?>
	<table width="100%" style="font-size:12px;">
		<tr>
			<?php if (isset($data['header']['left'])) : ?>
			<td style="width:<?php echo (isset($data['header']['left']['width']) and !empty($data['header']['left']['width']))?$data['header']['left']['width']:'33%';?>" align="<?php echo (isset($data['header']['left']['align']) and !empty($data['header']['left']['align']))?$data['header']['left']['align']:'left';?>"><?php echo (isset($data['header']['left']['text']) and !empty($data['header']['left']['text']))?$data['header']['left']['text']:'&nbsp;';?></td>
			<?php endif;?>
			<?php if (isset($data['header']['center'])) : ?>
			<td style="width:<?php echo (isset($data['header']['center']['width']) and !empty($data['header']['center']['width']))?$data['header']['center']['width']:'33%';?>" align="<?php echo (isset($data['header']['center']['align']) and !empty($data['header']['center']['align']))?$data['header']['center']['align']:'left';?>"><?php echo (isset($data['header']['center']['text']) and !empty($data['header']['center']['text']))?$data['header']['center']['text']:'&nbsp;';?></td>
			<?php endif;?>
			<?php if (isset($data['header']['right'])) : ?>
			<td style="width:<?php echo (isset($data['header']['right']['width']) and !empty($data['header']['right']['width']))?$data['header']['right']['width']:'33%';?>" align="<?php echo (isset($data['header']['right']['align']) and !empty($data['header']['right']['align']))?$data['header']['right']['align']:'left';?>"><?php echo (isset($data['header']['right']['text']) and !empty($data['header']['right']['text']))?$data['header']['right']['text']:'&nbsp;';?></td>
			<?php endif;?>
		</tr>
	</table>
	<?php endif;?>

    <div class="viewheader" style="width:100%;padding-top:15px;">
		<div style="float:left;<?php echo (isset($data['hCol']) and $data['hCol']==2)?'width:49%;':'';?>">
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
				<div style="float:left;width:200px;"><?php echo Inflector::humanize($label); ?></div>
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
				echo '<table border="0" cellspacing="0" cellpadding="0" ';
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
					echo '<th style="border-top:1px solid #000;border-bottom:1px solid #000;text-align:left;">'.$label.'</th>';
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
										echo '<label style="width:95%;';
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
										echo '">';
										echo $this->General->getValue($in,$va,$val,null,$model);
										echo '</label>';
									}
									else echo '&nbsp;';						
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
				echo '</table>';
			}
            echo '<br />';
        }
	}
	?>
    <?php
	echo '<br />';
    ?>
	<?php if (isset($data['catatan'])):?>
	<div style="padding:20px 0;"><?php echo $data['catatan'];?></div>
	</table>
	<?php endif;?>	
	<?php //pr($data['tandaTangan']); ?>
	<?php if (isset($data['tandaTangan'])) :?>
	<table width="100%">
		<tr>
			<?php if (isset($data['tandaTangan']['left'])) : ?>
			<td style="width:<?php echo (isset($data['tandaTangan']['left']['width']) and !empty($data['tandaTangan']['left']['width']))?$data['tandaTangan']['left']['width']:'33%';?>" align="<?php echo (isset($data['tandaTangan']['left']['align']) and !empty($data['tandaTangan']['left']['align']))?$data['tandaTangan']['left']['align']:'left';?>"><?php echo (isset($data['tandaTangan']['left']['text']) and !empty($data['tandaTangan']['left']['text']))?$data['tandaTangan']['left']['text']:'&nbsp;';?></td>
			<?php endif;?>
			<?php if (isset($data['tandaTangan']['center'])) : ?>
			<td style="width:<?php echo (isset($data['tandaTangan']['center']['width']) and !empty($data['tandaTangan']['center']['width']))?$data['tandaTangan']['center']['width']:'33%';?>" align="<?php echo (isset($data['tandaTangan']['center']['align']) and !empty($data['tandaTangan']['center']['align']))?$data['tandaTangan']['center']['align']:'left';?>"><?php echo (isset($data['tandaTangan']['center']['text']) and !empty($data['tandaTangan']['center']['text']))?$data['tandaTangan']['center']['text']:'&nbsp;';?></td>
			<?php endif;?>
			<?php if (isset($data['tandaTangan']['right'])) : ?>
			<td style="width:<?php echo (isset($data['tandaTangan']['right']['width']) and !empty($data['tandaTangan']['right']['width']))?$data['tandaTangan']['right']['width']:'33%';?>" align="<?php echo (isset($data['tandaTangan']['right']['align']) and !empty($data['tandaTangan']['right']['align']))?$data['tandaTangan']['right']['align']:'left';?>"><?php echo (isset($data['tandaTangan']['right']['text']) and !empty($data['tandaTangan']['right']['text']))?$data['tandaTangan']['right']['text']:'&nbsp;';?></td>
			<?php endif;?>
		</tr>
		<tr><td style="height:<?php echo (isset($data['tandaTangan']['height']) and !empty($data['tandaTangan']['height']))?$data['tandaTangan']['height']:'50px';?>">&nbsp;</td></tr>

		<tr>
			<?php if (isset($data['tandaTangan']['left'])) : ?>
			<td style="width:<?php echo (isset($data['tandaTangan']['left']['width']) and !empty($data['tandaTangan']['left']['width']))?$data['tandaTangan']['left']['width']:'33%';?>" align="<?php echo (isset($data['tandaTangan']['left']['align']) and !empty($data['tandaTangan']['left']['align']))?$data['tandaTangan']['left']['align']:'left';?>"><?php echo (isset($data['tandaTangan']['left']['name']) and !empty($data['tandaTangan']['left']['name']))?$data['tandaTangan']['left']['name']:'&nbsp;';?></td>
			<?php endif;?>
			<?php if (isset($data['tandaTangan']['center'])) : ?>
			<td style="width:<?php echo (isset($data['tandaTangan']['center']['width']) and !empty($data['tandaTangan']['center']['width']))?$data['tandaTangan']['center']['width']:'33%';?>" align="<?php echo (isset($data['tandaTangan']['center']['align']) and !empty($data['tandaTangan']['center']['align']))?$data['tandaTangan']['center']['align']:'left';?>"><?php echo (isset($data['tandaTangan']['center']['name']) and !empty($data['tandaTangan']['center']['name']))?$data['tandaTangan']['center']['name']:'&nbsp;';?></td>
			<?php endif;?>
			<?php if (isset($data['tandaTangan']['right'])) : ?>
			<td style="width:<?php echo (isset($data['tandaTangan']['right']['width']) and !empty($data['tandaTangan']['right']['width']))?$data['tandaTangan']['right']['width']:'33%';?>" align="<?php echo (isset($data['tandaTangan']['right']['align']) and !empty($data['tandaTangan']['right']['align']))?$data['tandaTangan']['right']['align']:'left';?>"><?php echo (isset($data['tandaTangan']['right']['name']) and !empty($data['tandaTangan']['right']['name']))?$data['tandaTangan']['right']['name']:'&nbsp;';?></td>
			<?php endif;?>
		</tr>
	</table>
	<?php endif;?>
</div>
