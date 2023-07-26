<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('CakePHP: the rapid development php framework:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		// for favicon default
		// echo $this->Html->meta('icon');
		// end for favicon default

		// for favicon custom
		echo $this->Html->meta ('favicon.ico', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base . DS . 'img/logo-pn-sbh.png', array (
		    'type' => 'icon'
		));
		// end for favicon custom


		echo $scripts_for_layout;
                //die;

		//<!-- Bootstrap 3.3.6 -->
			echo $this->Html->css('bootstrap/css/bootstrap');
		//<!-- font awasome-->
			echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">';
		//<!-- iCheck -->
			// echo $this->Html->css('plugins/blue/flat/blue');
		//<!-- Morris chart -->
			echo $this->Html->css('plugins/morris/morris');
		//<!-- Date Picker -->
			echo $this->Html->css('plugins/datepicker/datepicker3');
		//<!-- Daterange picker -->
			echo $this->Html->css('plugins/daterangepicker/daterangepicker');
		//<!-- bootstrap wysihtml5 - text editor -->
			echo $this->Html->css('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min');
		//<!-- Ionicons -->
			echo $this->Html->css('plugins/ionicons/css/ionicons.min');
			echo $scripts_for_layout;
		//<!-- MASTER Theme style -->
			echo $this->Html->css('AdminLTE');
		//<!-- AdminLTE Skins. Choose a skin from the css/skins
			echo $this->Html->css('skins/skin-red');

		// echo $this->Html->css('login');
	?>
</head>
<body>
	<div id="container">
		<div id="header">

		</div>
		<div id="btm_header">
			<div style="clear:both;heght:0;">&nbsp;</div>
		</div>
		<div id="content">

                 <?php
                    echo $content_for_layout;
                    die;
                 ?>
			<?php echo $this->Session->flash(); ?>


			<?php echo $content_for_layout; ?>

		</div>
		<div id="footer">

		</div>
	</div>
</body>

<?php
//echo $this->Html->script('script');
//echo $this->Html->script('dist/app');
//<!-- jQuery 2.2.3 -->
echo $this->Html->script('plugins/jQuery/jquery-2.2.3.min');
?>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<?php
//<!-- Bootstrap 3.3.6 -->
echo $this->Html->script('bootstrap/js/bootstrap');
/*
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
 */

//<!-- Bootstrap WYSIHTML5 -->
echo $this->Html->script('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min');
//<!-- Slimscroll -->

//<!-- FastClick -->
echo $this->Html->script('plugins/fastclick/fastclick');
//<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
//<script src="dist/js/pages/dashboard.js"></script>
echo $this->Html->script('dist/app.min');
//<!-- AdminLTE for demo purposes -->
echo $this->Html->script('dist/demo');

?>
</html>
