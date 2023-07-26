<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 */

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/', array('plugin'=>'sys_config', 'controller' => 'home', 'action' => 'index'));
Router::connect('/home', array('plugin'=>'sys_config', 'controller' => 'home', 'action' => 'index'));
Router::connect('/welcome', array('plugin'=>'sys_config', 'controller' => 'welcomes', 'action' => 'welcome'));
Router::connect('/login', array('plugin'=>'sys_config', 'controller' => 'users', 'action' => 'signin'));
Router::connect('/logout', array('plugin'=>'sys_config', 'controller' => 'users', 'action' => 'signout'));
Router::connect('/users/*', array('plugin'=>'sys_config', 'controller' => 'users'));
Router::connect('/groups/*', array('plugin'=>'sys_config', 'controller' => 'groups'));
Router::connect('/branches/*', array('plugin'=>'sys_config', 'controller' => 'branches'));
Router::connect('/companies/*', array('plugin'=>'sys_config', 'controller' => 'companies'));
Router::connect('/regions/*', array('plugin'=>'sys_config', 'controller' => 'regions'));
Router::connect('/underconstruction', array('controller' => 'common', 'action' => 'underconstruction'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
