<div class="users form">
<?php echo $this->Form->create('UserAccount');?>
	<fieldset>
 		<legend style="max-width: 300px;width: auto;"><?php echo __('Change Password'); ?></legend>
	<?php
		echo $this->Form->input('current_password',array('label'=>array('text'=>'Current Password', 'class'=>'col-sm-3 control-label'),'type'=>'password','class'=>'form-control','between'=>'<div class="col-sm-9">','after'=>'</div>'));
		echo $this->Form->input('new_password',array('label'=>array('text'=>'New Password', 'class'=>'col-sm-3 control-label'),'type'=>'password','class'=>'form-control','between'=>'<div class="col-sm-9">','after'=>'</div>'));
		echo $this->Form->input('re_new_password',array('label'=>array('text'=>'Re New Password', 'class'=>'col-sm-3 control-label'),'type'=>'password','class'=>'form-control','between'=>'<div class="col-sm-9">','after'=>'</div>'));
		// echo $this->Form->input('current_password', array('type'=>'password'));
		// echo $this->Form->input('new_password', array('type'=>'password'));
		// echo $this->Form->input('re_new_password', array('type'=>'password'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
