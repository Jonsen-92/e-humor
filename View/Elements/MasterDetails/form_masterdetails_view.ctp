<?php if (isset($data['script'])) echo '<script type="text/javascript">'.$data['script'].'</script>';?>
<div class="<?php echo Inflector::variable($this->name);?> form">
	<?php
		if (isset($data['lov']) and !empty($data['lov'])) {
			echo $this->Html->script('thickbox');
			echo $this->Html->css('jquery.thickbox');
		}

        $data['option'] = array('class'=>'form-horizontal');
	?>
	<h2><?php echo __((isset($data['title_header'])) ? $data['title_header']:Inflector::humanize(Inflector::underscore($model)));?></h2>
	<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model);?>

    <!-- PRINT FORM -->
    	<?php echo $this->MasterDetail->getFormMasterDetail($data, $model, true); ?>
    <!-- / PRINT FORM -->

	<?php echo $this->Form->end($options);?>
</div>
<?php echo $this->Html->script('script');?>
<?php if (isset($addjs)) echo $this->Html->script($addjs);?>

<?php if (isset($data['fckeditor']) and is_array($data['fckeditor'])) : ?>
	<script type="text/javascript" src="<?php echo $this->Html->url('/js/fckeditor/fckeditor.js');?>"></script>
	<script type="text/javascript">
		function createFCKeditor(oName, path, toolbar){
			oName.BasePath	= path;
			oName.ToolbarSet = toolbar ;
			oName.ReplaceTextarea() ;
		}
		<?php foreach ($data['fckeditor'] as $i=>$v) :
			$fckId=is_array($v)?$i:$v;
			$fckHeight=isset($v['height'])?$v['height']:'400px';
			$fckWidth=isset($v['width'])?$v['width']:'100%';
		?>
			createFCKeditor(new FCKeditor('<?php echo $fckId;?>', '<?php echo $fckWidth;?>', '<?php echo $fckHeight;?>'), "<?php echo $this->Html->url('/js/fckeditor/');?>", "CustomeOne");
		<?php
			unset($fckId);unset($fckWidth);unset($fckHeight);
			endforeach;
		?>
	</script>
<?php endif;?>

<?php if (isset($data['autocomplete']) and $data['autocomplete']) :?>
	<?php echo $this->Html->script('jquery-ui-1.8.9.autocomplete.min.js');?>
	<?php echo $this->Html->css('autocomplete/jquery.ui'); ?>

	<style>
	.ui-autocomplete-loading { background: white url('<?php echo $this->Html->url('/img/ui-anim_basic_16x16.gif');?>') right center no-repeat; }
	</style>

<?php endif;?>
<script type="text/javascript">
	function addRow(model, html){

		html=html.toString();
		var nextCounter=Number($('#tr_d_counter'+model).val())+1;
		$('#tr_d_counter'+model).val(nextCounter);
		while (html != (html = html.replace("::row::", nextCounter)));
		$('#trHiddenCounter'+model).before(html);
		//alert(nextCounter);
		//tb_init('#trPesananDetail'+nextCounter+' input.thickbox');
		tb_init('#tr'+model+nextCounter+' input.thickbox');
		//tb_init('input.thickbox');
		currencyOnKeyPress();
		enterToTab();
	}

	function deleteRow(id){
		$('#'+$('#'+id).attr('rel')).detach();
	}
</script>
