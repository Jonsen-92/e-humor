<div style="height:400px;">
	<h2><?php 
	
	echo __((isset($this->passedArgs['plabel']))?$this->passedArgs['plabel']:$model);?></h2>
	
	<div style="width:450px; margin: 0 0 5px 0;" class="row">
	<?php 
		$c=1;
		foreach ($aField as $a=>$b){
			if ($c==1) $firstIdField=is_array($b)?$a:$b;
			else $secondIdField=is_array($b)?$a:$b;
			if($c==2) break;
			$c++;
		}
		
		foreach ($aFilter as $j=>$k) : 
			$label=is_array($k)?$j:$k;
			if (is_array($k) and isset($k['label']) and !empty($k['label'])) $label=$k['label'];
			$field=is_array($k)?$j:$k;	
			?>
		<div style="float:left;width:200px; margin:0 0 3px 0;"><?php echo $label;?></div>
		<div style="float:right;width:200px; margin:0 0 3px 0;"><input style="width:200px;" id="search<?php echo $field;?>" onkeydown="onEnter(event);"/></div>
		<div style="clear:both;height:0;">&nbsp;</div>
	<?php endforeach;?>
	<div><input style="width:100px;" value="Search" type="button" onclick="isidataajax();"/></div>
	</div>
	
	<div id="isidataajax" style="margin: 10px 0 5px 5px;" class="row"></div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		isidataajax();
	});

	function onEnter(event){
		if(event.keyCode == 13){
	        isidataajax();
	    }
	}

	function isidataajax(){
		$.post(window.location.protocol+"//<?php echo $_SERVER['SERVER_NAME'].$this->base.'/common/xlov/';?>", 
			{	<?php 
					// Menentukan kondisi fix
					foreach ($cond as $x=>$y) { 
						echo 'fixCond_'.$x;
						echo ':';
						echo "'$y',";
					}
					foreach ($aFilter as $j=>$k) {
						$field=is_array($k)?$j:$k;
						if (is_string($field) && strpos($field, ".") > -1)
							$field = str_replace(".",__,$field);
						echo 'sFilter_'.$field;
						echo ':';
						echo "'$field',";
						
						echo 'filCond_'.$field;
						echo ':';
						?>'LIKE "%'+$('#search<?php echo $field;?>').val()+'%"',<?php
					} 
					foreach ($aField as $j=>$k) {
						$field=is_array($k)?$j:$k;
						$label=is_array($k)?$j:$k;
						if (is_array($k) and isset($k['label']) and !empty($k['label'])) $label=$k['label'];
						$label=str_replace(' ','_',$label);
						echo 'aField_'.$label;
						echo ':';
						echo "'$field',"; 
					}
					foreach ($aGroup as $x=>$y) { 
						echo 'aGroup_'.$x;
						echo ':';
						echo "'$y',";
					}
					echo "model:'$model'";
					//if (isset($options['recursive'])) echo ",recursive:{$options['recursive']}";
					foreach ($options as $m=>$n) echo ",$m:'$n'";
				?>},
			function(data){
				$('#isidataajax').html(data);
		});
	}

	function defaultlov(p){
		valIdLov=$('#<?php echo $firstIdField;?>'+p).html();
	
		<?php if (isset($this->passedArgs['usescript']) and !empty($this->passedArgs['usescript'])) : ?>
			<?php echo $this->passedArgs['usescript'];?>;
		<?php else : ?>
			<?php if (isset($firstIdField) and isset($this->passedArgs['pid'])) :?>
				var parent0=parent.document.getElementById('<?php echo $this->passedArgs['pid'];?>');
				var firstValue = $('#<?php echo $firstIdField;?>'+p).html();
				if (firstValue != null)
					firstValue = firstValue.trim();
				$(parent0).val( firstValue);
			<?php endif;?>

			<?php if (isset($secondIdField) and isset($this->passedArgs['pname'])) :?>
				var parent1=parent.document.getElementById('<?php echo $this->passedArgs['pname'];?>');
				var secondIdValue = $('#<?php echo $secondIdField;?>'+p).html();
				if (secondIdValue != null)
					secondIdValue = secondIdValue.trim();
				$(parent1).val( secondIdValue );
			<?php endif;?>

			<?php $c=2;foreach ($aField as $i=>$v) :  $field=is_array($v)?$i:$v; ?>
				<?php 
					$idField=explode('&',$this->passedArgs['pmodel']); 
					$idField=$idField[0]; 
					if (isset($this->passedArgs['pcounter'])) $idField.=$this->passedArgs['pcounter'];
					$idField.=Inflector::camelize($field);
				?>				
				var parent<?php echo $c;?>=parent.document.getElementById('<?php echo $idField;?>');
				if (parent<?php echo $c;?>!=null && $(parent<?php echo $c;?>).hasClass('lovfield')) $(parent<?php echo $c;?>).val( $('#<?php echo $field;?>'+p).html().trim());				
			<?php $c++;endforeach;?>
			
			<?php if (isset($this->passedArgs['addscript']) and !empty($this->passedArgs['addscript'])) :?>
				<?php echo $this->passedArgs['addscript']; ?>;
			<?php endif;?>
		<?php endif;?>
		tb_remove();
	}

	function getPCounter(){
		var c='';
		<?php if (isset($this->passedArgs['pcounter']) and !empty($this->passedArgs['pcounter'])) :?>
		var c =<?php echo $this->passedArgs['pcounter'];?>;
		<?php endif;?>
		return c;
	}
</script>
<?php echo $this->Js->writeBuffer();?>
