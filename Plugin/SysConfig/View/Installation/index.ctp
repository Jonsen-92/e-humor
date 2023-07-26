<div class="installation index">
	<h2>INSTALLATION</h2>
	<?php echo $this->Form->create('Installation', array('url'=>'/installation'));?>
	<div><h3>Administrator</h3></div>	
	<?php echo $this->Form->input('username_administrator');?>
	<?php echo $this->Form->input('password_administrator');?>
	<?php echo $this->Form->input('email_administrator');?>
	<div><br/><h3>Perusahaan</h3></div>
	<?php echo $this->Form->input('nama_perusahaan');?>
	<?php echo $this->Form->input('alamat_perusahaan');?>
	<?php echo $this->Form->input('kota_perusahaan');?>
	<?php echo $this->Form->input('telp_perusahaan');?>
	<?php echo $this->Form->input('fax_perusahaan');?>
	<?php echo $this->Form->input('npwp_perusahaan');?>
	<div><br/><h3>Lain-Lain</h3></div>	
	<?php echo $this->Form->input('nama_aplikasi');?>
	<?php echo $this->Form->input('email_helpdesk');?>
	<?php echo $this->Form->input('multi_branch', array('options'=>array('0'=>'- SELECT MULTI BRANCH OPTION -','YES'=>'MULTI BRANCH','NO'=>'SINGLE BRANCH')));?>
	<?php echo $this->Form->end('Install');?>

</div>
