<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your applicatse{
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
     public $components = array(
		'Acl',
		// 'Auth'=>array(
		// 	'authorize' => 'actions',
        // 	'loginAction' => array('plugin'=>'sys_config', 'controller' => 'users', 'action' => 'signin'),
        // 	'logoutRedirect' => array('plugin'=>'sys_config', 'controller' => 'users', 'action' => 'signin'),
        // 	'loginRedirect' => array('plugin'=>'sys_config', 'controller' => 'users', 'action' => 'index')
		// ),
        // => array('authorize' => array('Actions' => array('actionPath' => 'controllers'))),
		'Session',
		'Email'
	);
    // public $helpers = array('Html', 'Form', 'Session');

		
	// pr($this->Session->read('Auth'));
	public function beforeFilter() {
        $this->loadModel('SysConfig.UserAccount');
        $action = $this->request->params['action'];
        if ($action <> 'signin' AND  $action <> 'forget' AND $action <> 'welcome'  AND  $action <> 'signup' AND !$this->Session->read('Auth.User.id')) {
            $this->redirect('/welcome');
        }

        if ($this->Session->check('Auth.User.id')) {
            if (!in_array($this->request->params['action'], array('signin','signout','formredirect'))){
                $this->UserAccount->id=$this->Session->read('Auth.User.id');
                // Menghapus Session jika last_activity lebih dari umur session
                $last_activity = strtotime($this->UserAccount->field('last_activity'));
                $umur_sesi = $last_activity +300*60;
                $now = time();
                
                if($umur_sesi < $now){
                    $this->redirect('/logout');
                }
                else{
                    $this->UserAccount->saveField('last_activity', date('Y-m-d H:i:s'));
                }
            }
        }
        // else{
        //     $this->redirect('/logout');
        // }
        
        if ($this->Session->read('Auth.User.id')){
            
            // GET MENU PRIVILEDGES
            $this->helpers[]='TreeUtility';
			$this->loadModel('GroupsMenu');
			
            $groupId = $this->Session->read('Auth.Group');
            if (!isset($groupId) || empty($groupId)) {
                $groupId = 2;
            } 
            else {
                if(is_array($groupId)) {
                    $groupId = implode(',', $groupId);
                }
			}
			
            $current_url = "/" . $this->request->params['plugin'] . "/" . $this->request->params['controller'];
            $current_url2 = "/" . $this->request->params['plugin'] . "/" . $this->request->params['controller'] . "/" . $this->request->params['action'];
            
            $group_menu = $this->Session->read('Auth.User.group_menu');
            $user_menu = $this->Session->read('Auth.User.user_menu');
            $validMenu = array();
            // foreach($user_menu as $v){
                $validMenu[]=$this->search_menu_url($user_menu, $current_url,$current_url2);
            // }
			$validMenu=array_unique($validMenu);
			
            if (count($validMenu) <= 0
                && ($current_url != "/"
                && $current_url != "/welcome"
                && $current_url != "/login"
                && $current_url != "/logout"
                && $current_url != "/forgetPassword"
                && $current_url != "/signup"
                && $current_url != "/sys_config/users"
                && $current_url != "/sys_config/home"
            ))
                $this->redirect('/home');

            $menuprivileges=array();
            foreach($group_menu as $v){
                $menuprivileges[$v['GroupsMenu']['id']]=$v['GroupsMenu']['menu_id'];
            }

            foreach ($menuprivileges as $v){
                $aParent=$this->__getParentMenuId($v, array(), $user_menu);
                $menuprivileges=array_merge($menuprivileges,$aParent);
            }
            $menuprivileges=array_unique($menuprivileges);
            //passing BASE : Johnny
            $base=substr($this->base,1);
            // GET PARENT MENU
            $this->loadModel('SysConfig.Menu');
            $this->Menu->recursive=-1;//line 124
            if ($menuprivileges) {
                
                $mainmenus=array();
                foreach($user_menu as $v){
                    if ($v['Menu']['is_publish'] == "1" &&
                        $v['Menu']['type_menu'] != "1" &&
                        empty($v['Menu']['parent_id']) &&
                        in_array($v['Menu']['id'], $menuprivileges)
                        )
                        $mainmenus[]=array(
                            'id'=>$v['Menu']['id'],
                            'title'=>$v['Menu']['title'],
                            'url'=>$v['Menu']['url'],
                        );
                }
            } 
            else {
                $mainmenus=array();
            }

            //Get Sub Menu
            // Jika url home/signin, maka session menu dihapus
            if ($this->request->params['controller']=='home' or $this->request->params['action']=='signin'){
                $this->Session->delete('Auth.Menus');
            }

            // redirect to first child url
            if(!$this->Session->read('Auth.Menus.id') and isset($this->passedArgs['pmenus'])){
                $urls = $this->__getSubmenu($this->passedArgs['pmenus'], $user_menu);
                $this->redirect($urls);
                //echo 'awal'.$this->Session->read('Menus.parent_id').'pasArg'.$this->passedArgs['pmenus'];
            }
            elseif ($this->Session->read('Auth.Menus.id') <> @$this->passedArgs['pmenus'] and isset($this->passedArgs['pmenus'])){
                $urls = $this->__getSubmenu($this->passedArgs['pmenus'], $user_menu);
                $this->redirect($urls);
                //echo 'akhir'.$this->Session->read('Menus.parent_id').'pasArg'.$this->passedArgs['pmenus'];
            }

            $group_filter = "";
            if (isset($groupId))
                $group_filter = " AND Menu.id IN (SELECT menu_id FROM groups_menus WHERE group_id IN ({$groupId})) ";

            if($this->Session->read('Auth.Menus.id')){
                
                $data_sub=array();
                foreach($user_menu as $v){
                    if ($v['Menu']['is_publish'] == "1" &&
                        $v['Menu']['type_menu'] != "1" &&
                        in_array($v['Menu']['id'], $menuprivileges)
                        )
                        $data_sub[]=$v;
                }

                //menu
                $data_sub=$this->__getSideMenus($data_sub);
                $this->set('menus2',$data_sub);
            }
            else{
                if($this->request->params['controller']=='home' or $this->request->params['action']=='signin'){
                    

                    $data_sub=array();
                    foreach($user_menu as $v){
                        if ($v['Menu']['is_publish'] == "1" &&
                            $v['Menu']['type_menu'] != "1" &&
                            in_array($v['Menu']['id'], $menuprivileges)
                            )
                            $data_sub[]=$v;
                    }
                }
                else{
                    
                    $curMenu=array();
                    $curMenu[]=$this->search_menu_url($user_menu, "/{$this->request->url}");
                    if (!empty($curMenu[0]['id']))
                        $curMenu=$curMenu[0]['id'];
                    if (empty($curMenu)) {
                        $like='/';
                        if (!empty($this->request->params['plugin'])) $like.=$this->request->params['plugin'].'/';
                        $like.=$this->request->params['controller'];
                        if ($this->request->params['action']=='index'){
                            // $curMenu=$this->Menu->field('id',"Menu.url LIKE '$like%' OR Menu.url LIKE '$like/{$this->request->params['action']}%'");
                            $curMenu[]=$this->search_menu_url($user_menu, "/", "/".$this->request->params['action']);
                        }
                        else{
                            $like.='/'.$this->request->params['action'];
                            // $curMenu=$this->Menu->field('id',"Menu.url LIKE '$like%'");
                            $curMenu[]=$this->search_menu_url($user_menu, $like);
						}
						
						// pr($like);
                        if (!empty($curMenu))
                            $curMenu=$curMenu[0]['id'];
                        // unset($like);
                        if (empty($curMenu)) {
                            
                            $curMenu[]=$this->search_menu_url($user_menu, $like);
                            if (!empty($curMenu))
                                $curMenu=$curMenu[0]['id'];
                        }

                    }
                    if (empty($curMenu)){
                        
                        $data_sub=array();
                        foreach($user_menu as $v){
                            if ($v['Menu']['is_publish'] == "1" &&
                                $v['Menu']['type_menu'] != "1"
                                )
                                $data_sub[]=$v;
                        }

                    }
                    else {

                        // dapatkan nenek moyang dari curMenu
                        $grandParentId=$this->__getGrandParent($curMenu); unset($curMenu);
                        $this->Session->write('Auth.Menus.id', $grandParentId); unset($grandParentId);
                        
                        $lft_rgt[]=$this->search_menu_id($user_menu, $this->Session->read('Auth.Menus.id'));
                        if (!empty($lft_rgt))
                            $lft_rgt=$lft_rgt[0];
                        
                        if(isset($lft_rgt['title']))
                        $this->Session->write('Auth.Menus.title', $lft_rgt['title']);
                        
                        $data_sub=array();
                        foreach($user_menu as $v){
                            if ($v['Menu']['is_publish'] == "1" &&
                                $v['Menu']['type_menu'] != "1"
                                )
                                $data_sub[]=$v;
                        }
                    }
				}
                
				$data_sub=$this->__getSideMenus($data_sub);
                $this->set('menus2', $data_sub);
            }

        }
        

        // FILTERING
        if ( ($this->request->is('post') and isset($this->request->data['Filter'])) or
             (!$this->Session->read('Auth.Filter') and isset($this->request->data['Filter']))){
            $this->Session->write('Auth.Filter', $this->request->data['Filter']);
            $this->Session->write('Auth.Filter.controller', $this->name);
        }
        else if ($this->Session->read('Auth.Filter.controller')<>$this->name){
            $this->Session->delete('Auth.Filter');
        }
        else $this->request->data['Filter']=$this->Session->read('Auth.Filter');


        parent::beforeFilter();
	}
	
	function search_menu_url($user_menu, $url1, $url2=null){
        foreach($user_menu as $v){
            if (count($v['children']) > 0){
                return $this->search_menu_url($v['children'], $url1, $url2);
            }
            else{
                if($v['Menu']['url'] == $url1 || (isset($url2) && $v['Menu']['url'] == $url2)){
                    return $v['Menu'];
                }
            }
        }
    }

    function search_menu_id($user_menu, $id){
        foreach($user_menu as $v){
            if (count($v['children']) > 0){
                return $this->search_menu_id($v['children'], $id);
            }
            else{
                if($v['Menu']['id'] == $id){
                    return $v['Menu'];
                }
            }
        }
    }

	function __getSideMenus($data,$parents=0){
        $parent =($parents!=0)?"id=span".$parents:"";

        foreach($data as $v){

            $this->sideMenu .='<li class="treeview"><a href="#">';
            $this->sideMenu .='<span>'.$v['Menu']['title'].'</span>';
            $this->sideMenu .='<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';

            //punya submenu
            if(!empty($v['children'])){
                $this->sideMenu .='<ul class="treeview-menu">';

                foreach($v['children'] as $child){

                    if(!empty($child['children'])){

                        $this->sideMenu .='<li><a href="#">';
                        $this->sideMenu .='<i class="fa fa-circle-o"></i>'.$child['Menu']['title'].'';
                        $this->sideMenu .='<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
                        $this->sideMenu .='<ul class="treeview-menu">';
                        foreach($child['children'] as $child2){
                            $this->sideMenu .='<li><a href="'.$this->base.$child2['Menu']['url'].'">';
                            $this->sideMenu .='<i class="fa fa-caret-right"></i>'.$child2['Menu']['title'].'</a></li>';
                        }
                        $this->sideMenu .='</ul></li>';

                    }
                    else
                    {
                        $this->sideMenu .='<li><a href="'.$this->base.$child['Menu']['url'].'">';
                        $this->sideMenu .='<i class="fa fa-file-pdf-o" aria-hidden="true"></i>'.$child['Menu']['title'].'';
                        $this->sideMenu .='<span class="pull-right-container"></span></a></li>';
                    }
                }
                $this->sideMenu .='</ul>';
            }
            $this->sideMenu .='</li>';
        }

        return $this->sideMenu;
    }

	function __getParentMenuId($id, $aParent, $userMenu){
        $this->loadModel('SysConfig.Menu');
        // $data=$this->Menu->find('first', array('conditions'=>"Menu.is_publish IN (1) AND Menu.type_menu NOT IN (1) AND Menu.id IN ($id)", 'fields'=>array('Menu.parent_id')));
        $data = array();
        foreach($userMenu as $item){
            if ($item['Menu']['is_publish'] == "1" && $item['Menu']['type_menu'] != "1" && $item['Menu']['id'] == $id)
                $data['Menu'] = array(
                    'parent_id'=>$item['Menu']['parent_id'],
                    'id'=>$item['Menu']['id'],
                );
        }

        if (isset($data['Menu']['parent_id'])) {
            if (!in_array($data['Menu']['parent_id'], $aParent)) {
                $aParent[]=$data['Menu']['parent_id'];
                return $this->__getParentMenuId($data['Menu']['parent_id'],$aParent,$userMenu);
            }
        }
        return $aParent;
	}
	
	private function __getGrandParent($id){
        $group_menu = $this->Session->read('Auth.User.group_menu');
        $user_menu = $this->Session->read('Auth.User.user_menu');
        $this->loadModel('SysConfig.Menu');
        // $parentId=$this->Menu->field('parent_id', "Menu.is_publish IN (1) AND Menu.type_menu NOT IN (1) AND Menu.id IN ($id)");
        $parentId[]=$this->search_menu_id($user_menu, $id);
        if(isset($parentId[0]['is_publish'])){
            if (!empty($parentId) &&
                $parentId[0]['is_publish'] == "1" &&
                $parentId[0]['type_menu'] != "1"
                )
                $parentId=$parentId[0]['parent_id'];
            else
                $parentId = false;
        
            if ($parentId) return $this->__getGrandParent($parentId);
        }
        return $id;
	}
	
	function __getSubmenu($aParent,$userMenu){
        $this->Session->write('Auth.Menus.id', $aParent);
        $this->Menu->recursive=-1;
        $group_menu = $this->Session->read('Auth.User.group_menu');
        $user_menu = $this->Session->read('Auth.User.user_menu');
        // $getParentId = $this->Menu->find('first',array('fields'=>array('title','url','lft','rght'),'conditions'=>array('id'=>$aParent,'type_menu'=>array(2,3))));
        $getParentId=$this->search_menu_id($user_menu, $aParent);
        if (!empty($getParentId))
            $getParentId=$getParentId[0];

        $this->Session->write('Auth.Menus.title', $getParentId['Menu']['title']);

        if(empty($getParentId['Menu']['url'])){
            $url = $this->Menu->find('first', array('conditions'=>"lft>{$getParentId['Menu']['lft']} AND rght<{$getParentId['Menu']['rght']} AND url IS NOT NULL AND url<>'' AND type_menu NOT IN (1)", 'order'=>array('description'), 'fields'=>'url'));
            if (isset($url['Menu']['url'])) $url=$url['Menu']['url'];
            else $url ='/home';
        }else $url = $getParentId['Menu']['url'];
        return $url;
    }

    public $helpers = array('Html', 'Form', 'Session');
    function __handleError($_e){
		$error=$this->__handleErrorMessage($_e);
        if ($error) $this->Session->setFlash($error);
		else $this->Session->setFlash('undefined error on validate');
    }

	function __handleErrorMessage($_e){
		$aIndex_e = array_keys($_e);
		if (!empty($aIndex_e[0])) return $_e[$aIndex_e[0]][0];
		else return false;
    } 
    
    function __filteringSearch($_aArgs){
		$aCond=array();
		if (isset ($_aArgs['field'])) {
			$notField=array();
			foreach ($_aArgs['field'] as $v) {
				if (substr($v,-8)<>'is_next2') {
					$field=explode('.', $v);
					if (isset($field[1])) {
						$prefix=$_aArgs[$field[0]];
						$prefixOp=$_aArgs['op'.$field[0]][$field[1]];
						$prefix__=$_aArgs['____'.$field[0]][$field[1]];
						$field=$field[1];
					}
					else {
						$prefix=$_aArgs;
						$field=$v;
						$prefixOp=$_aArgs['op'.$field];
						$prefix__=$_aArgs['____'.$field];
					}

					$cond='';
					if (!in_array($v, $notField)) {
						if (isset($prefix[$field]) and !empty($prefix[$field])) {
							if (in_array($prefixOp, array('IN','NOT IN'))){
								if ($prefix__<>'numeric'){
									$aTemp=explode(',',$prefix[$field]);
									foreach ($aTemp as $i=>$j) $aTemp[$i]="'$j'";
									$prefix[$field]='('.implode(',',$aTemp).')';
									unset($aTemp);
								}
								else $prefix[$field]='('.$prefix[$field].')';
							}
							elseif ($prefixOp=='LIKE') $prefix[$field]="'%{$prefix[$field]}%'";
							elseif ($prefix__<>'numeric') $prefix[$field]="'{$prefix[$field]}'";

							$cond.="$v {$prefixOp} {$prefix[$field]}";

							if (in_array($v.'is_next2', $_aArgs['field'])){
								$field=explode('.',$v);
								if (isset($field[1])) {
									$prefixOpNext=$_aArgs['op'.$field[0]][$field[1].'is_next2'];
									$prefixNext=$_aArgs['next'.$field[0]][$field[1]];
									$field=$field[1];
								}
								else {
									$prefixOpNext=$_aArgs['op'.$field[0].'is_next2'];
									$prefixNext=$_aArgs['next'.$field[0]];
									$field=$field[0];
								}
								if (isset($prefix[$field.'is_next2']) and !empty($prefix[$field.'is_next2'])) {
									if (in_array($prefixOpNext, array('IN','NOT IN'))){
										if ($prefix<>'numeric') {
											$aTemp=explode(',',$prefix[$field.'is_next2']);
											foreach ($aTemp as $i=>$j) $aTemp[$i]="'$j'";
											$prefix[$field.'is_next2']='('.implode(',',$aTemp).')';
											unset($aTemp);
										}
										else $prefix[$field.'is_next2']='('.$prefix[$field.'is_next2'].')';
									}
									elseif ($prefixOpNext=='LIKE') $prefix[$field.'is_next2']="'%{$prefix[$field.'is_next2']}%'";
									elseif ($prefix__<>'numeric') $prefix[$field.'is_next2']="'{$prefix[$field.'is_next2']}'";

									$cond.= " {$prefixNext} $v {$prefixOpNext} {$prefix[$field.'is_next2']}";
									$notField[]=$v.'is_next2';
								}
							}

							$aCond[]='('.$cond.')';
						}
					}
				}
			}
		}
		return implode (' AND ', $aCond);
    }

    
}
