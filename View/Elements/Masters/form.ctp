<?php
    if (isset($data['script'])) echo '<script type="text/javascript">'.$data['script'].'</script>';
?>


<div class="<?php echo Inflector::variable($this->name);?> form">
	<?php
            if (isset($data['lov']) and !empty($data['lov'])) {
                echo $this->Html->script('thickbox');
                echo $this->Html->css('jquery.thickbox');
            }
	?>
	<h2><?php echo __((isset($data['title_header']))?$data['title_header']:Inflector::humanize(Inflector::underscore($model)));?></h2>

    <!-- DECLARE FORM EDIT ------------------------------------------------ -->
        <?php $data['option'] = array('class' => 'form-horizontal'); ?>

        <?php // echo (isset($data['option'])) ? $this->Form->create($model, $data['option']); : $this->Form->create($model);
             echo $this->Form->create($model, $data['option']);
        ?>

	<?php echo $this->Master->getFormMaster($data['field'], $model);?>

        <?php
        $options = array(
                        'label' => __((isset($button) ? $button : 'Submit'), true),
                        'class' => 'btn btn-primary pull-left'
                    );
        ?>

	<?php echo $this->Form->end($options);?>

    <!-- end : DECLARE FORM EDIT ------------------------------------------------ -->
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
	<?php echo $this->Html->script('jquery-ui-1.8.9.autocomplete.js');?>
    <?php echo $this->Html->script('bootstrap-datepicker.min.js');?>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->base;?>/css/autocomplete/jquery.ui.css" media="screen"/>


	<style>
	.ui-autocomplete-loading { background: white url('<?php echo $this->Html->url('/img/ui-anim_basic_16x16.gif');?>') right center no-repeat; }
	</style>

<?php endif;?>
