<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<?php
		// for favicon default
		// echo $this->Html->meta('icon');
		// end for favicon default

		// for favicon custom
		echo $this->Html->meta ('favicon.ico', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base . DS . 'img/logo-tristar-1.jpg', array (
				'type' => 'icon'
		));
		// end for favicon custom

		echo $this->Html->css('cake.generic');
		// we dont need to load jquery again.
		// echo $this->Html->script('jquery');

		echo $scripts_for_layout;
	?>
</head>
<body>
		<div>
					<div class="ajax_paging_content"><?php echo $content_for_layout; ?></div>
		</div>
</body>
</html>
