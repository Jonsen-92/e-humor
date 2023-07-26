<?php
/**
 * DEFAULT LAYOUT APPLICATION
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<?php echo $this->Html->charset(); ?>
<title>
        Cuti Online
</title>
<?php
// for favicon default
// echo $this->Html->meta('icon');
// end for favicon default

// for favicon custom
echo $this->Html->meta ('favicon.ico', 'img/logo-pn-sbh.png', array (
    'type' => 'icon'
));
// end for favicon custom

echo $this->Html->css('cake.generic', null,array('media'=>'screen'));
//echo $this->Html->script('jquery');


//<!-- Bootstrap 3.3.6 -->
    echo $this->Html->css('bootstrap/css/bootstrap.min');

//<!-- font awasome-->
    echo $this->Html->css('plugins/font-awesome/css/font-awesome.min');
    echo $this->Html->css('plugins/jQueryUI/jquery-ui.min');
//<!-- iCheck -->
    echo $this->Html->css('plugins/iCheck/flat/blue');
//<!-- Morris chart -->
    echo $this->Html->css('plugins/morris/morris.min');
//<!-- Date Picker -->
    // echo $this->Html->css('plugins/datepicker/datepicker3.min');
//<!-- Daterange picker -->
    // echo $this->Html->css('plugins/daterangepicker/daterangepicker.min');
//<!-- bootstrap wysihtml5 - text editor -->
    // echo $this->Html->css('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5');
//<!-- Ionicons -->
    echo $this->Html->css('plugins/ionicons/css/ionicons.min');
    echo $scripts_for_layout;
//<!-- MASTER Theme style -->
    echo $this->Html->css('AdminLTE');
     //</--Custom css-->
      echo $this->Html->css('Custom');
//<!-- AdminLTE Skins. Choose a skin from the css/skins
    echo $this->Html->css('skins/skin-green');
//<!-- Select2 css
    echo $this->Html->css('plugins/select2/select2.min');

?>



<?php
//echo $this->Html->script('script');
//echo $this->Html->script('dist/app');
//<!-- jQuery 2.2.3 -->
echo $this->Html->script('plugins/jQuery/jquery-2.2.3.min');
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->script('plugins/jQuerymaskMoney/jquery.maskMoney.js');
echo $this->Html->script('plugins/select2/select2.min.js');
echo $this->Html->script('plugins/jQueryUI/jquery-ui.min.js');
echo $this->Html->script('bootstrap/js/bootstrap.min');
?>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  //$.widget.bridge('uibutton', $.ui.button);
</script>
<?php
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
<script src="plugins/daterangepicker/daterangepicker.min.js"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
 */

//<!-- Bootstrap WYSIHTML5 -->
echo $this->Html->script('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min');
//<!-- Slimscroll -->

//<!-- FastClick -->
echo $this->Html->script('plugins/fastclick/fastclick.min');
//<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
//<script src="dist/js/pages/dashboard.js"></script>
echo $this->Html->script('dist/app');
//<!-- AdminLTE for demo purposes -->
//echo $this->Html->script('dist/demo');
echo $this->Html->script('script');
?>


</head>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

<!-- MAIN HEADER ----------------------------------------------------------- -->

    <header class="main-header">

        <!-- Logo -->
        <a href="" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>Cuti Online</b></span>
            <!-- logo for regular state and mobile devices -->
            <span style="font-size:15px; margin-left: -10px">SISTEM APLIKASI CUTI ONLINE</a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">

            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

<div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown messages-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-inbox"></i><span class="label label-warning">3</span></a>
						<ul class="dropdown-menu">
							<li class="header">Anda memiliki 3 permohonan cuti</li>
						</ul>
					</li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php echo $this->Html->image('ava1.jpg', array('alt' => 'CakePHP','class' => 'user-image','width'=>'26px')); ?>
              <span class="hidden-xs"><?php echo strtoupper($this->Session->read('Auth.User.name')); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <?php echo $this->Html->image('ava1.jpg', array('alt' => 'CakePHP','class' => 'img-circle','width'=>'60px')); ?>
                <p>
                  <?php echo strtoupper($this->Session->read('Auth.User.name')); ?>
                  <!--<small>Member since Nov. 2012</small>-->
                </p>
              </li>
            
              <!-- Menu Body -->
        <!--
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>

              </li>
        -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                    <?php echo $this->Html->link('Change Password', '/sys_config/users/changepassword', array('class' => 'btn btn-default btn-flat'));?>
                </div>
         <div class="pull-right">
                    <?php echo $this->Html->link('Sign Out' , '/sys_config/users/signout', array('class' => 'btn btn-default btn-flat'));?>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>

        </nav>
    </header>

<!-- LEFT SIDE MENU ----------------------------------------------------------- -->

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

             <div class="user-panel">

             <div class="pull-left image">
               <?php echo $this->Html->image('ava1.jpg', array('alt' => 'CakePHP','class' => 'img-circle')); ?>
             </div>

             <div class="pull-left info">
               <p><?php echo strtoupper($this->Session->read('Auth.User.name')); ?></p>
         <?php
         echo $this->Session->read('Auth.User.branch_code');
        // if ($this->Session->read('Auth.User.region_id')) echo $this->Session->read('Auth.User.region');
        // else echo $this->Session->read('Auth.User.branch');
        ?>
        <!--
               <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
         -->
             </div>

           </div>

            <ul class="sidebar-menu">
            <!-- Sidebar user panel -->
            <?php
            // get current url for active and not active sidebar menu
            // pr($this->request->here);
            // $urlCurrent = $this->request->here();
            // $explodeUrlCurrent = explode("/",$urlCurrent);
            // unset($explodeUrlCurrent[0]);
            // pr($explodeUrlCurrent);

            // $base = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $this->base . $this->request->here;
            // pr($base);
            // end get current url for active and not active sidebar menu
            ?>
                <li class="header">MAIN NAVIGATION</li>
                <?php if (isset($menus2)) echo $menus2; ?>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

<!-- CONTENT ----------------------------------------------------------- -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

    <section class="content-header" >
    <h2 style="background-color: transparent">
      <?php //echo $title_for_layout; ?>
      <!-- <small>it all starts here</small> -->
    </h2>

    <!-- breadcrumb -->
      <ol class="breadcrumb">
        <li style="margin:0"><?php echo $this->Html->link('Home', '/home');?></li>
        <li style="margin:0" class="active"><?php echo $this->Html->link(isset($menuTitle)?$menuTitle:$this->name , array('action'=>'index'));?></li>
        <!-- <?php echo $this->request->here(); ?> -->
      </ol>
      <!-- end : breadcrumb -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">

            <!--
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <?php// echo $this->Session->flash(); ?>
            </div>
            -->

            <div class="box-body">
            <div style="margin-top: 10px">
                <?php echo $this->Session->flash(); ?>
            </div>
             <?php  echo $content_for_layout; ?>
            </div>
        </div>
   </section>

    </div>
    <!-- /.content-wrapper -->

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 1.0
                </div>
                <?php echo date('Y')?>. Powered by <strong>PN SIBUHUAN DEVELOPER TIM</strong> All rights
                reserved.
            </footer>
      <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg" style="position: fixed; height: auto;"></div>
        </div>
        <!-- ./wrapper -->
<!-- ./wrapper -->

</body>

</html>
