<html>
<head>
	<?php echo $this->Html->script('jquery');?>
	<meta http-equiv="Refresh" content="<?php echo $pause?>;url=<?php echo WEB_PROTOCOL.'://'.$_SERVER['SERVER_NAME'].$this->base.$url;?>"/>
</head>
<body>
	<?php echo $script;?>
</body>
</html>