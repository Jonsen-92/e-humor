<?php
/**
 * Class of Installation
 * 
 * Class for installation, set primary data to apllication as company, configuration etc
 * this class run only on first time call apllication 
 * 
 * Date Created  : 15 November 2011
 * Last Modified : 17 November 2011
 *
 * @package cake
 * @subpackage cake.app.controller
 * @since mtemplate 1.1.1 vertion
 * @access public
 */
App::uses('Controller', 'Controller');
class InstallationController extends Controller{
	
	/** Declaration of Member Variables **/
	
	/**
	 * @abstract this varibale use to gave name of this class
	 * @var string
	 * @global
	 * @access public
	 */
	var $name='Installation';
	
	/**
	 * @abstract this varibale use to list global Model that uses in this class
	 * @var array
	 * @global
	 * @access public
	 */
	var $uses=array('Installation','UserPriviledges.UserAccount');

	/**
	 * Constructor function to check that aplication has been installed
	 *
	 * Date Created  : 15 November 2011
	 * Last Modified : 15 November 2011
	 *
	 * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
	 * @since mtemplate 1.1.1 vertion
	 * @access public
	 */
	function beforeFilter(){
		// if has been installed, url wiil be redirected to /login
		if (INSTALLATION) {
			$this->Session->setFlash('Aplikasi Ledger sudah terinstall. Jika ingin meng-install kembali silahkan copy ulang source code terlebih dahulu!');
			$this->redirect('/login');
		}
	}

	/**
	 * Main function to set first configuration, will be overwrite bootsrap and save any data to database
	 *
	 * Date Created  : 15 November 2011
	 * Last Modified : 17 November 2011
	 *
	 * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
	 * @since mtemplate 1.1.1 vertion
	 * @access public
	 */
	function index(){ 
		if (isset($this->request->data)) {
			$error=0;
			$this->Installation->set($this->request->data);
			if ($this->Installation->validates()){
				// Read file bootstrap.php and convert to array
				$file=APP.'config'.DS.'bootstrap.php';
				$content=file($file);
				foreach ($content as $i=>$v){
					$content[$i]=trim($v);
					// If content of line is define constanta, 
					// check konstanta name to change value from installation form
					if (substr($content[$i],0,6)=='define'){
						$isDefine=substr($content[$i],7,-2);
						$isDefine=explode(',',$isDefine);
						$konstanta=str_replace("'",'',trim($isDefine[0]));
						// If konstanta name is INSTALLATION, NAMA_PERUSAHAAN, ALAMAT, KOTA,
						// TELP, NAMA_APLIKASI, EMAIL_ADMINISTRATOR, EMAIL_HELPDESK, MODE, 
						// or MULTI_BRANCH, change its value 
						if (in_array($konstanta, array('INSTALLATION', 'NAMA_PERUSAHAAN', 'ALAMAT', 'KOTA', 'TELP', 'NAMA_APLIKASI', 'EMAIL_ADMINISTRATOR', 'EMAIL_HELPDESK', 'MODE', 'MULTI_BRANCH'))) {
							$value=str_replace("'",'',trim($isDefine[1]));
							// Set INSTALLATION value to be true
							if ($konstanta=='INSTALLATION') $value=1;
							// Set NAMA_PERUSAHAAN value from installation form
							elseif ($konstanta=='NAMA_PERUSAHAAN') $value=$this->request->data['Installation']['nama_perusahaan'];
							// Set ALAMAT value from installation form
	 						elseif ($konstanta=='ALAMAT') $value=$this->request->data['Installation']['alamat_perusahaan'];
							// Set KOTA value from installation form
							elseif ($konstanta=='KOTA') $value=$this->request->data['Installation']['kota_perusahaan'];
							// Set TELP value from installation form
							elseif ($konstanta=='TELP') $value=$this->request->data['Installation']['telp_perusahaan'];
							// Set NAMA_APLIKASI value from installation form
							elseif ($konstanta=='NAMA_APLIKASI') $value=$this->request->data['Installation']['nama_aplikasi'];
							// Set EMAIL_ADMINISTRATOR value from installation form
							elseif ($konstanta=='EMAIL_ADMINISTRATOR') $value=$this->request->data['Installation']['email_administrator'];
							// Set EMAIL_HELPDESK value from installation form
							elseif ($konstanta=='EMAIL_HELPDESK') $value=$this->request->data['Installation']['email_helpdesk'];
							// Set MODE value to be production
							elseif ($konstanta=='MODE') $value='production';
							// Set MULTI_BRANCH value from installation form
							elseif ($konstanta=='MULTI_BRANCH') $value=$this->request->data['Installation']['multi_branch'];
							if (!is_numeric($value)) $value="'$value'";
							$content[$i]="define('$konstanta', $value);";
						}
					}
				}

				if (!isset($content) or empty($content) or !is_array($content)) {
					$error=1;
					$errorMessage='Gagal memanipulasi data bootsrap dengan data form installation';
				}

				// Set point of datasource to handle transaction
				$dataSource = $this->UserAccount->getDataSource();
            	$dataSource->begin($this);
				
				// Truncate table users and insert new record with administrator data from installation form
				if (!$error){
					if (!$this->UserAccount->query('TRUNCATE TABLE `'.$this->UserAccount->useTable.'`')){
						$error=1;
						$errorMessage='Gagal mengkosongkan data user';
					}
				}
				if (!$error){
					$this->UserAccount->create();
					$this->request->data['UserAccount']['name']='Administrator';
					$this->request->data['UserAccount']['username']=$this->request->data['Installation']['username_administrator'];
					$this->request->data['UserAccount']['password']=Security::hash($this->request->data['Installation']['password_administrator'], null, true);
					$this->request->data['UserAccount']['email']=$this->request->data['Installation']['email_administrator'];
					$this->request->data['UserAccount']['is_actived']=1;
					if (!$this->UserAccount->save($this->request->data)){
						$error=1;
						$errorMessage='Gagal menambahkan user administrator';
					}
				}
			
				// Truncate table groups_users and insert new record to grand administrator priviledges
				if (!$error){
					if (!$this->UserAccount->query('TRUNCATE TABLE `groups_users`')){
						$error=1;
						$errorMessage='Gagal mengkosongkan data priviledges user';
					}
				}
				if (!$error){
					if ($this->request->data['Installation']['multi_branch']=='NO') $groupId=2;
					else $groupId=1;
					$sql='INSERT INTO `groups_users` SET group_id='.$groupId.', user_id='.$this->UserAccount->id;
					if (!$this->UserAccount->query($sql)){
						$error=1;
						$errorMessage='Gagal menambahkan priviledges Administrator';
					}
				}

				// Truncate table companies and insert new record with company data from installation form
				if (!$error){
					if (!$this->UserAccount->Branch->Company->query('TRUNCATE TABLE `'.$this->UserAccount->Branch->Company->useTable.'`')){
						$error=1;
						$errorMessage='Gagal mengkosongkan data perusahaan';
					}
				}
				if (!$error){
					$this->UserAccount->Branch->Company->create();
					$this->request->data['Company']['id']=strtoupper(substr(str_replace(' ','',$this->request->data['Installation']['nama_perusahaan']),0,10));
					$this->request->data['Company']['name']=$this->request->data['Installation']['nama_perusahaan'];
					$this->request->data['Company']['address']=$this->request->data['Installation']['alamat_perusahaan'];
					$this->request->data['Company']['npwp']=$this->request->data['Installation']['npwp_perusahaan'];
					$this->request->data['Company']['phone_num']=$this->request->data['Installation']['telp_perusahaan'];
					$this->request->data['Company']['fax_num']=$this->request->data['Installation']['fax_perusahaan'];
					if (!$this->UserAccount->Branch->Company->save($this->request->data)){
						$error=1;
						$errorMessage='Gagal menambahkan data perusahaan';
					}
				}

				/** 
				 * Truncate to table branches and insert new record with id=0, 
				 * branch_name=NAMA_PERUSAHAAN if MULTI_BRANCH='NO' 
				 * or branch_name=PUSAT if MULTI_BRANCH='YES'
				 */
				if (!$error){
					if (!$this->UserAccount->Branch->query('TRUNCATE TABLE `'.$this->UserAccount->Branch->useTable.'`')){
						$error=1;
						$errorMessage='Gagal mengkosongkan data cabang';
					}
				}
				if (!$error){
					$this->UserAccount->Branch->create();
					$this->request->data['Branch']['id']='PUSAT';
					if ($this->request->data['Installation']['multi_branch']=='NO') 
					$this->request->data['Branch']['branch_name']=$this->request->data['Installation']['nama_perusahaan'];
					else $this->request->data['Branch']['branch_name']='PUSAT';
					$this->request->data['Branch']['address']=$this->request->data['Installation']['alamat_perusahaan'];
					$this->request->data['Branch']['npwp']=$this->request->data['Installation']['npwp_perusahaan'];
					$this->request->data['Branch']['phone_num']=$this->request->data['Installation']['telp_perusahaan'];
					$this->request->data['Branch']['fax_num']=$this->request->data['Installation']['fax_perusahaan'];
					$this->request->data['Branch']['user_id']=$this->UserAccount->id;
					$this->request->data['Branch']['create_by']=$this->UserAccount->id;
					$this->request->data['Branch']['company_id']=$this->UserAccount->Branch->Company->id;
					$this->request->data['Branch']['is_otonom']=1;
					/*
					$sql="INSERT INTO branches SET id='PUSAT'";
					if ($this->data['Installation']['multi_branch']=='NO') 
					$sql.=", branch_name='".$this->data['Installation']['nama_perusahaan']."'";
					else $sql.=", branch_name='PUSAT'";
					$sql.=", address='".$this->data['Installation']['alamat_perusahaan']."'";
					$sql.=", npwp='".$this->data['Installation']['npwp_perusahaan']."'";
					$sql.=", phone_num='".$this->data['Installation']['telp_perusahaan']."'";
					$sql.=", fax_num='".$this->data['Installation']['fax_perusahaan']."'";
					$sql.=', user_id='.$this->User->id;
					$sql.=', create_by='.$this->User->id;
					$sql.=", company_id='".$this->User->Branch->Company->id."'";
					$sql.=', is_otonom=1';
					*/
					//if (!$this->User->Branch->query($sql)){
					if (!$this->UserAccount->Branch->save($this->request->data)){							
						$error=1;
						$errorMessage='Gagal menambahkan data cabang';
					}
				}

				// Truncate table branches_users and insert new record with administrator users and his branch
				if (!$error){
					$this->loadModel('BranchesUser');
					if (!$this->BranchesUser->query('TRUNCATE TABLE `'.$this->BranchesUser->useTable.'`')){
						$error=1;
						$errorMessage='Gagal mengkosongkan data Branch User';
					}
				}
				if (!$error){
					$this->BranchesUser->create();
					$this->request->data['BranchesUser']['user_id']=$this->UserAccount->id;
					$this->request->data['BranchesUser']['branch_id']='PUSAT';
					if (!$this->BranchesUser->save($this->request->data)){
						$error=1;
						$errorMessage='Gagal menambahkan data Branch User';
					}
				}

				// Truncate table regions
				if (!$error){
					if (!$this->UserAccount->Region->query('TRUNCATE TABLE `'.$this->UserAccount->Region->useTable.'`')){
						$error=1;
						$errorMessage='Gagal mengkosongkan data region';
					}
				}

				// Open bootsrap file and rewrite with new data
				if (!$error) {					
					$handle=fopen($file, 'wb+');
					if (!$handle) {
						$error=1;
						$errorMessage='Gagal membaca file bootstrap.php';
					}
					else {
						$c=1;
						foreach ($content as $v){
							if (!fwrite($handle,$v."\n")){
								$error=1;
								$errorMessage='Gagal menuliskan file bootstrap dibaris '.$c;
								break;
							}
							$c++;
						}
						fclose($handle);
					}
				}

				// if not error, commit transaction
				if (!$error){
					$dataSource->commit($this);
					$this->Session->setFlash(__('Proses Installasi Berhasil'));
					// redirect to login page
					$this->redirect('/logout');
				}
				// if any error, rollback transaction
				else {
					$dataSource->rollback($this);
                	$this->Session->setFlash(__('ROLLBACK. '.$errorMessage, true));
				}
			}
			else $this->__handleError($this->Installation->invalidFields());
		} // end if (isset($this->data))
		
	}
	
	/**
	 * To handle error message with create flash session
	 * its same function __handleError in AppController
	 * this function was made because this class not have inharitance from AppController
	 *
	 * Date Created  : 15 November 2011
	 * Last Modified : 15 November 2011
	 *
	 * @param array $_e array error message
	 * @access private
	 * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
	 * @since mtemplate 1.1.1 version
	 */  
	function __handleError($_e){
    	$aIndex_e = array_keys($_e);
		if (!empty($aIndex_e[0])) $this->Session->setFlash($_e[$aIndex_e[0]]);
	}
}
