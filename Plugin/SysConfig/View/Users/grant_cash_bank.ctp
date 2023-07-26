<div class="users form">
<?php echo $this->Form->create('CashBanksUser', array('url'=>'/sys_config/users/grant_cash_bank/'.$id)); ?> 
<fieldset>
	<legend><?php __('Grant Cash Bank');?></legend>
	<?php $c=0; foreach ($cashBanks as $i=>$v): ?>
		<div class="input checkbox">
			<?php if (isset($this->data)): ?>
				<input id="CashBanksUserCashBankId" type="checkbox" value="1" name="data[CashBanksUser][cash_bank_id][<?php echo $i;?>]" <?php echo (isset($this->data['CashBanksUser']['cash_bank_id'][$i]) and $this->data['CashBanksUser']['cash_bank_id'][$i]==1)?'checked':'';?>>
			<?php else : ?>
				<input id="CashBanksUserCashBankId" type="checkbox" value="1" name="data[CashBanksUser][cash_bank_id][<?php echo $i;?>]" <?php echo (in_array($i,$existingPriviledges))?'checked':'';?>>
			<?php endif;?>
			<label><?php echo $v;?></label>
		</div>
	<?php $c++; endforeach;?>
</fieldset>
<?php echo $this->Form->submit('Grant Cash Bank', array('class'=>'submit'));?>
<?php echo $this->Form->end();?>
</div>
