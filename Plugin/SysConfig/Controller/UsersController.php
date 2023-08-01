<?php
App::uses('AppController', 'Controller');
class UsersController extends AppController {

	public $name = 'Users';
	var $model='UserAccount';
	public $uses=array('SysConfig.UserAccount','MasterFile.Pegawai');
	var $components=array('Master');
	// var $optUserAkses = array('0'=>'- Level User Akses -','1'=>'Pusat','2'=>'Divisi','3'=>'Office','4'=>'Company','5'=>'Branch');

	public function index() {
		$type_akses = $this->Session->read('Auth.User.type_akses');
		if(!$this->Session->read('Auth.User.id')){
			$this->redirect('/logout');
		}

		if($type_akses > 0){
			$this->Session->setFlash(__('Anda tidak memiliki hak Akses terhadap Menu ini, Silahkan Login dengan User Admin'));
			$this->redirect('/');
		}
		
		$this->helpers[] = 'General';
		$model = $this->model;
		$this->loadModel('UserAccount');
		$this->loadModel('Indikator');
		$conditions = array();
		$kondisi1 = $this->__filteringSearch($this->Session->read('Auth.Filter'));
		if (isset($kondisi1) and !empty($kondisi1)) $conditions[] = $kondisi1;
		unset($kondisi1);
		
		$test = $this->paginate($model, implode(' AND ', $conditions), array(), 'type_akses asc');
		$server = $_SERVER['SERVER_NAME'];
		$base = substr($this->base, 1);
		$this->set(compact('test', 'server', 'base','model'));

	}

	function view($id = null) {
		// $home = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base.'/home';
		
		$this->loadModel('SysConfig.GroupAccount');
	    $model='UserAccount';
		$this->Master->__edit('UserAccount', $id, null, 1,'index');

		$this->helpers[]='Master';
		
		$groupAccounts = $this->UserAccount->GroupAccount->find('list', array('conditions'=>''));

		$base=substr($this->base,1);
		$server=$_SERVER['SERVER_NAME'];

		$data['field']=array();
		$data['field'][]=array('nip'=> array('type'=>'text','label'=>'NIP','maxlength'=>35,'style'=>'width:300px;'));
		$data['field'][]=array('name'=> array('type'=>'text','label'=>'Nama Pengguna','maxlength'=>35,'style'=>'width:300px;'));
		$data['field'][]=array('pangkat_gol'=> array('type'=>'text','label'=>'Pangkat/Golongan','maxlength'=>35,'style'=>'width:300px;'));
		$data['field'][]=array('jabatan'=> array('type'=>'text','label'=>'Jabatan','maxlength'=>35,'style'=>'width:300px;'));
		// $data['field'][]=array('username'=> array('type'=>'text','label'=>'User Name','maxlength'=>20,'style'=>'width:160px;','disabled'=>true));
		$data['field'][]=array('GroupAccount'=>array('type'=>'select','label'=>'Group Account','options'=>$groupAccounts));
		// $data['field'][]=array('company_id'=>array('type'=>'select','label'=>'Perusahaan','options'=>$aCompany,'default'=>0));
		$data['field'][]=array('type_akses'=>array('type'=>'select','label'=>'Level User Akses', 'options'=>array('0'=>'Ketua/Wakil Ketua','1'=>'Panitera/Sekretaris','2'=>'Kasubbag','3'=>'Staff','5'=>'Admin'), 'style'=>'width:80px;float:none;', 'fieldset'=>false, 'legend'=>false,'onchange'=>"getUserType('$server', '$base',this.value)"));
		$data['field'][]=array('divisi'=> array('type'=>'text','label'=>'Divisi','maxlength'=>35,'style'=>'width:300px;'));
		$data['field'][]=array('sub_divisi'=> array('type'=>'text','label'=>'Sub Divisi','maxlength'=>35,'style'=>'width:300px;'));
		$data['field'][]=array('is_actived'=>array('type'=>'select','label'=>'Is Active ?','options'=>array('0'=>'Not Active','1'=>'Active'), 'style'=>'width:100px'));

		$script = '$(document).ready(function() { ';
		$script .= 'var element = document.getElementById("UserAccountIsActived");';
		$script .= 'element.classList.remove("form-control");';
		$script .= '$("#UserAccountIsActived").css({"height":"10px","width":"10px"});';
		$script .= '});';
		// $data['script'] = $script;

		$data['option']['url']='/user_priviledges/user_account/edit/'.$id;
		$simplepaging=$this->Master->__getsimplepaging($model,$id);
		$this->set(compact('groupAccounts','model','data','id','simplepaging'));
		$this->render('/Elements/Masters/form_view');
	}

	public function add() {
		$model = $this->model;
		$this->loadModel('SysConfig.GroupsUsers');
		$this->loadModel('SysConfig.Group');
		$this->loadModel('MasterFile.Pegawai');

		$group[''] = '- Pilih Level Jabatan -';
		$group += $this->Group->find('list',array('fields'=>'id, description','order'=>'Group.id'));
		$nip[''] = 'Pilih NIP Pegawai';
		$nip += $this->Pegawai->find('list', array('fields'=>array('nip','nip')));

		if ($this->request->is('post') || $this->request->is('put')){
			$exist = $this->UserAccount->find('first', array('conditions'=>"nip IN ('{$this->request->data[$model]['nip']}')"));
			if($exist){
				$this->Session->setFlash("User for NIP ".$this->request->data[$model]['nip']." is already exist");
				$this->redirect('add');
			}
			$this->request->data[$model]['password'] = Security::hash($this->request->data['UserAccount']['password'], null, true);
			
			$this->$model->saveAll($this->request->data);
			$data = $this->$model->find('first', array('conditions'=>array('UserAccount.nip'=>$this->request->data[$model]['nip'])));
			$this->request->data['GroupsUsers']['group_id'] = $this->request->data[$model]['group_id'];
			$this->request->data['GroupsUsers']['user_id'] = $data[$model]['id'];
			$this->GroupsUsers->saveAll($this->request->data);
			$this->redirect('index');
		}

		$this->set(compact('model','group','nip'));

	}

	public function edit($id=null) {
		$model = $this->model;
		$this->loadModel('SysConfig.GroupsUsers');
		$this->loadModel('SysConfig.Group');
		$data = $this->$model->find('first', array('conditions'=>"$model.id IN ('{$id}')"));
		$group = $this->Group->find('list',array('fields'=>'id, description','order'=>'Group.id'));
		
		if ($this->request->is('post') || $this->request->is('put')){
			$edit_user = array();
			$edit_group_user = array();

			
			$edit_group_user['group_id'] = $this->request->data[$model]['group_id'];
			foreach($this->request->data[$model] as $k=>$v){
				if($k !== 'group_id'){
					$edit_user[$k] = "'".$v."'";
				}
			}
			$this->request->data[$model]['password'] = Security::hash($this->request->data['UserAccount']['password'], null, true);
			// pr($this->request->data);exit;
			// $this->$model->updateAll($edit_user, array($model.'.id' => $id));
			$this->GroupsUsers->updateAll($edit_group_user, array('user_id' => $id));
			$this->Master->__edit('UserAccount', $id, 'UserAccount', '', 'index');
			// $this->redirect('index');

		}
		$this->set(compact('data', 'group'));
	}

	function delete($id = null) {
		$home = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$this->base.'/home';
		
		if ($this->Session->read('Auth.User.level_akses') != 'PUSAT') {
			echo '<script>window.history.back();</script>';
			return false;
		}

		// if ($this->Session->read('Auth.User.kode_group') != 77) {
		// 	echo '<script>window.history.back();</script>';
		// 	return false;
		// }

	    unset($this->UserAccount->hasAndBelongsToMany['GroupAccount']);
		unset($this->UserAccount->hasAndBelongsToMany['Salesman']);
		$this->Master->__delete('UserAccount', $id);
	}

	function getNip($nip)
	{
		$this->layout=false;
		$this->autoRender=false;
		$this->loadModel('Pegawai');

		$data = $this->Pegawai->find('first', array('conditions' => array('Pegawai.nip' => $nip)));

		echo json_encode($data);
	}

	function getUser($nip)
	{
		$this->layout=false;
		$this->autoRender=false;
		$model = $this->model;

		$data = $this->$model->field('nip', 'nip IN ("'.$nip.'")');

		echo json_encode($data);
	}

	

	function __updateBranchesUser($id=null,$data,$controller=null){
		$error=0;$errormessage='';
        $controller->loadModel('SysConfig.BranchesUser');
        $controller->loadModel('SysConfig.Branch');
        if(!empty($data['branch']['branch'])){
			// $branchesUserId = $controller->BranchesUser->field("id", array("user_id"=>$id, "branch_id"=>$branch));
			$branchesUserId = $controller->BranchesUser->field("id", array("user_id"=>$id));
			$controller->BranchesUser->deleteAll(array("id"=>$branchesUserId));
			// if (is_array($data['Branch']['Branch'])){
			// 	foreach($data['Branch']['Branch'] as $branch){
	        // 		$branch_code = $controller->Branch->field('branch_code', array("Branch.id"=>$branch));
		    // 		$data=array();
			// 		$controller->BranchesUser->create();
			// 		$data['BranchesUser']['user_id']=$id[0];
			// 		$data['BranchesUser']['branch_id']=$branch;
			// 		$data['BranchesUser']['branch_code']=$branch_code;

			// 		if(!$controller->BranchesUser->save($data)) {
			// 			$error=1;
			// 			$errormessage='Penyimpanan BranchesUser Gagal';
			// 		}else{
			// 			$error=0;
			// 			$errormessage='Penyimpanan BranchesUser Berhasil';
			// 		}
	        // 	}
			// }
	        // else{
	        // 	$branch = $data['Branch']['Branch'];
	        // 	$branch_code = $controller->Branch->field('branch_code', array("Branch.id"=>$branch));
	    	// 	$data=array();
			// 	$controller->BranchesUser->create();
			// 	$data['BranchesUser']['user_id']=$id[0];
			// 	$data['BranchesUser']['branch_id']=$branch;
			// 	$data['BranchesUser']['branch_code']=$branch_code;

			// 	if(!$controller->BranchesUser->save($data)) {
			// 		$error=1;
			// 		$errormessage='Penyimpanan BranchesUser Gagal';
			// 	}else{
			// 		$error=0;
			// 		$errormessage='Penyimpanan BranchesUser Berhasil';
			// 	}
	        // }
        }
		return array(!$error,$errormessage);
	}

	function signin() {
		$this->layout='login';
		if ($this->Session->read('Auth.User')){
			$this->Session->setFlash('You are logged in!');
			$this->redirect('/', null, false);
		}
		if ($this->request->is('post')) {
			$this->request->data['UserAccount']['password']=Security::hash($this->request->data['UserAccount']['password1'], null, true);
			$user=$this->UserAccount->find('first', array('conditions'=>"UserAccount.username IN ('{$this->data['UserAccount']['username']}') AND UserAccount.password IN('{$this->data['UserAccount']['password']}') AND UserAccount.status = 1"));
			
			if (isset($user['UserAccount']['id'])){

				$createSession=true;
				if ($createSession){
					// Create Session
					foreach ($user['GroupAccount'] as $v) {
						$groupId[]=$v['id'];
					}

					$jenisPegawai = $this->Pegawai->field('jenis_pegawai', "nip IN ('".$user['UserAccount']['nip']."')");
					$this->Session->write('Auth.User.id', $user['UserAccount']['id']);
					$this->Session->write('Auth.User.nip', $user['UserAccount']['nip']);
					$this->Session->write('Auth.User.name', $user['UserAccount']['name']);
					$this->Session->write('Auth.User.divisi', $user['UserAccount']['divisi']);
					$this->Session->write('Auth.User.jabatan', $user['UserAccount']['jabatan']);
					$this->Session->write('Auth.User.unit_kerja', $user['UserAccount']['unit_kerja']);
					$this->Session->write('Auth.User.type_akses', $user['UserAccount']['type_akses']);
					$this->Session->write('Auth.User.status_jabatan', $user['UserAccount']['status_jabatan']);
					$this->Session->write('Auth.User.jenis_pegawai', $jenisPegawai);
					$this->Session->write('Auth.Group', $groupId);

					// update last login information
					if ($this->Session->read('Auth.User.id')){
						$this->UserAccount->id=$this->Session->read('Auth.User.id');
						$this->UserAccount->saveField('last_login', date('Y-m-d H:i:s'));
						$this->UserAccount->saveField('last_activity', date('Y-m-d H:i:s'));
						$this->UserAccount->saveField('is_login', 1);
					}

					$this->getMenus();

					// redirect
					$url=$this->Session->check('Auth.redirect')?$this->Session->read('Auth.redirect'):'/home';
					$this->redirect($url);
				}
				// hapus $user;
				unset($user);
			}
			else {
					$this->set('notValid', $this->request->data);
			}
		} 
	}

	function getMenus(){

		$this->loadModel('GroupsMenu');
		$this->loadModel('Menu');
		$groupId = $this->Session->read('Auth.Group');
        if (!isset($groupId) || empty($groupId)) {
            $groupId = 2;
        } else {
            if(is_array($groupId)) {
                $groupId = implode(',', $groupId);
            }
		}
		
        $validMenu = $this->GroupsMenu->find('all', array('recursive'=>-1,'conditions'=>"group_id IN ({$groupId}) "));
		$this->Session->write('Auth.User.group_menu', $validMenu);
		
        $menuIds = array();
        foreach($validMenu as $v){
        	$menuIds[] = $v['GroupsMenu']['menu_id'];
		}
		
        $userMenu = $this->Menu->find('threaded', array('recursive'=>-1,'conditions'=>"id IN ('".implode("','", $menuIds)."') AND Menu.is_publish='1'", 'order'=>array('Menu.description','Menu.title')));
    
		$this->Session->write('Auth.User.user_menu', $userMenu);

	}

	function signout(){
		if ($this->Session->read('Auth.User.id')) {
			$this->UserAccount->id=$this->Session->read('Auth.User.id');
			$this->UserAccount->saveField('is_login', 0);
		}
		$this->Session->delete('Auth');
		$this->redirect('/welcome');
	}

	function changepassword(){
		if (isset($this->request->data['UserAccount'])){
			if (empty($this->request->data['UserAccount']['current_password']) or empty($this->request->data['UserAccount']['new_password']) or empty($this->request->data['UserAccount']['re_new_password'])){
				$this->Session->setFlash('Please fill the field below');
			}
			elseif ($this->request->data['UserAccount']['new_password']<>$this->request->data['UserAccount']['re_new_password']) {
				$this->Session->setFlash('Your new password and re new password not equal');
				$this->request->data['UserAccount']['new_password']=$this->request->data['UserAccount']['re_new_password']='';
			}
			elseif(strlen($this->request->data['UserAccount']['new_password'])<8){
				$this->Session->setFlash('New Password min length 8 character.');
			}
			else {
				$this->UserAccount->id=$this->Session->read('Auth.User.id');
				if ($this->UserAccount->field('password')==Security::hash($this->data['UserAccount']['current_password'], null, true)){
					if ($this->UserAccount->saveField('password', Security::hash($this->request->data['UserAccount']['new_password'], null, true))){
						// $this->__logChangeData($this->Log->id);
						$this->Session->setFlash('Password has been changed. Please login again');
						$this->redirect('/logout');
					}
					else $this->Session->setFlash('Undefine error. please try again');
				}
				else $this->Session->setFlash('Your Current Password is not valid');

				$this->data['UserAccount']=array();
			}
		}
	}

	public function forget() {
		$this->layout='forgetPassword';
	}

	public function signup() {
		$this->layout='signup';
	}

	function grant_cash_bank($id){
	$this->loadModel('SysConfig.Branch');
	$this->loadModel('FinActs.CashBank');
		// Jika sudah tekan tombol submit

		if ($this->request->is('post') || $this->request->is('put')){
		//echo 1;exit;
			// set default value for $error. Default value is NOT ERROR
			$error=0;

			// Set point of datasource to handle transaction
			$dataSource = $this->UserAccount->getDataSource();
			$dataSource->begin($this);
			$this->loadModel('CashBanksUser');
			// Delete all existing priviledges for current user
			if (!$this->CashBanksUser->deleteAll(array('CashBanksUser.user_id' =>$id))){
				$errorMessage='Gagal menghapus priviledge cash bank sebelumnya';
				$error=1;
			}
			else {
				//echo 'tes';exit;
				// Looping for execute data form

				foreach ($this->request->data['CashBanksUser']['cash_bank_id'] as $i=>$v){
					// if cash bank is checked, grant priviledges for this with insert into table cash_banks_users
					if ($v==1){
						//echo "INSERT INTO cash_banks_users SET id=UUID(), user_id=$id, cash_bank_id='$i'";
						$this->CashBanksUser->create();
						$this->request->data['CashBanksUser']['user_id']=$id;
						$this->request->data['CashBanksUser']['cash_bank_id']=$i;
						$this->request->data['CashBanksUser']['created']=date("Y-m-d h:i:s");
						$this->request->data['CashBanksUser']['create_by']=$this->Session->read('Auth.User.id');
						$this->request->data['CashBanksUser']['modified']=date("Y-m-d h:i:s");
						$this->request->data['CashBanksUser']['modi_by']=$this->Session->read('Auth.User.id');
						//pr($this->request->data);exit;
						if (!$this->CashBanksUser->saveAll($this->request->data['CashBanksUser'])){
							$errorMessage='Gagal grant cash bank '.$i;
							$error=1;
							break;
						}
					}
				}
			}

			// if not error, execute commit, else execute rollback
			if (!$error){
				$dataSource->commit($this);
				$this->Session->setFlash(__('Grant Priviledge cash bank berhasil dijalankan', true));
			}
			else{
				$dataSource->rollback($this);
				$this->Session->setFlash(__('Rollback! '.$errorMessage, true));
			}

			// refresh to self page with new priviledges of cash bank
			$this->redirect('/users/grant_cash_bank/'.$id);
		}

		/**
		 * Get list of branches that belongs to user,
		 * first, check users is user region or not,
		 * if User is user region, get branches from list of branches that belongs to his region (branches_regions table)
		 * if user is not user region, get branches from list of branches that belongs to users (branches_users table)
		 */
		$isRegion=$this->UserAccount->RegionsUser->field('region_id', "user_id IN ($id)");


		if ($isRegion){
			$branches=$this->UserAccount->Region->BranchesRegion->find('list', array('fields'=>array('branch_id'), 'conditions'=>"region_id IN ('$isRegion')"));
		}
		else {
			$branches=$this->UserAccount->BranchesUser->find('list', array('fields'=>array('branch_id'), 'conditions'=>"user_id IN ($id)"));
		}



		// get list of cash bank that belongs to branches
		$cashBanks=$this->UserAccount->CashBank->find('list', array('fields'=>array('cash_bank_code','deskripsi'),'recursive'=>-1, 'conditions'=>"branch_id IN ('".implode("', '",$branches)."')"));
		//$cashBanks=$this->UserAccount->CashBank->find('list', array('fields'=>array('cash_bank_code','deskripsi')));

		// get existing priviledges
		$this->loadModel('CashBanksUser');
		$existingPriviledges=$this->CashBanksUser->find('list', array('fields'=>'cash_bank_id', 'conditions'=>"user_id IN ($id)"));
		$this->set(compact('id','cashBanks','existingPriviledges'));
	}
	
	function activate($id=null)
	{
		$model = $this->model;
		$status['status'] = 1;
		$this->$model->updateAll($status, array($model.'.id'=>$id));
		$this->Session->setFlash('User berhasil Diaktifkan');
		$this->redirect('index');
	}

	function deactivate($id=null)
	{
		$model = $this->model;
		$status['status'] = 0;
		$this->$model->updateAll($status, array($model.'.id'=>$id));
		$this->Session->setFlash('User berhasil Dinonaktifkan');
		$this->redirect('index');
	}


}
