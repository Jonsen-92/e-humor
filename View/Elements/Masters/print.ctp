<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __(NAMA_PERUSAHAAN.' : '.NAMA_APLIKASI.' : '); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->script('jquery');
		
		echo $scripts_for_layout;
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->base;?>/css/cake.print.css"/>
</head>
<body onload="window.print();">
		<?php echo $content_for_layout; ?>
</body>
</html>