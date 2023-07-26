<?php
App::uses('Component', 'Controller');
class MasterComponent extends Component{
	var $components=array('Session','General');
	var $controller;

	public function startup(Controller $controller){
		$this->controller=$controller;
	}

	/**
     * To retrive master data in index page with standard pagination
     *
     * Date Created  : 22 November 2010
     * Last Modified : 30 January 2012
     *
     * @param string $_sModel Model
     * @param array $aOpt array option data
     *
     * @access private
     * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
     * @since mtemplate 1.0 version
     */
    function __index($_sModel, $aOpt=null){
        $aOpt['url']=isset($aOpt['url'])?$aOpt['url']:array('add','view','edit','delete');


		$this->controller->helpers[]='General';
		if (!isset($aOpt['recursive']) or !in_array($aOpt['recursive'], array(-1,0,1,2))) $this->controller->$_sModel->recursive = 0;
        else
         $this->controller->$_sModel->recursive=$aOpt['recursive'];
		// pr($aOpt['conditions']);
		// exit;
		$conditions=array();
		$kondisi1=$this->controller->__filteringSearch($this->Session->read('Auth.Filter'));
		//pr($kondisi1);exit;
		if (isset($kondisi1) and !empty($kondisi1))$conditions[]=$kondisi1; unset($kondisi1);
		if (isset($aOpt['conditions']) and !empty($aOpt['conditions'])) $conditions[]=$aOpt['conditions'];
		if (!isset($this->controller->passedArgs['sort'])) {
			if (isset($aOpt['order'])) $this->controller->$_sModel->order=$aOpt['order'];
			else $this->controller->$_sModel->order=array($_sModel.'.'.$this->controller->$_sModel->primaryKey.' desc');
		}

		$this->controller->set(Inflector::variable($this->controller->name), $this->controller->paginate($_sModel, implode(' AND ',$conditions)));
		$this->controller->set('aOpt', $aOpt);
		$this->controller->set('model', $_sModel);
		$this->controller->set('id', $this->controller->$_sModel->primaryKey);
		//pr($this->controller->paginate($_sModel));
		//pr($this->controller->paginate($_sModel, implode(' AND ',$conditions)));
		//exit;
		if (isset($aOpt['render'])) $this->controller->render($aOpt['render']);
		else $this->controller->render('/Elements/Masters/index');
    }

	/**
     * To Add master data
     *
     * Date Created  : 22 November 2010
     * Last Modified : 30 January 2012
     *
     * @param string $_sModel Model
     * @param string $_sName=null Name of transction
     * @param string $index_url=null custome url redirect after add operation
     * @param array $generateID use if want to generate ID number
     * @param array $aFiles=array() if any files to upload
     *
     * @access private
     * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
     * @since mtemplate 1.0 version
     */
    function __add($_sModel, $_sName=null, $index_url=null, $generateID=false, $aFiles=array(), $otherFunction=array()){
        $varpesan=(!is_null($_sName))?$_sName:$_sModel;
		$varpesan=Inflector::humanize(Inflector::underscore($varpesan));

		if (($this->controller->request->is('post') or $this->controller->request->is('put')) and !empty($this->controller->data[$_sModel])) {
            $errorfile=0;
            $pesan_error="";

			// modified on 28 January 2011 to add input file type
            if (!empty($aFiles)){
                $sFile='';
                foreach($aFiles as $i=>$v){
                    $sFile=is_array($v)?$i:$v;
                    $namaArrayFile="a$sFile";
                    $$namaArrayFile=$this->controller->request->data[$_sModel][$sFile];
                    $namaArrayFile=$$namaArrayFile;
                    if (isset($v['maxSize']) and $namaArrayFile['size']>MAX_IMAGE_SIZE){
                        $errorfile=1;
                        $pesan_error="Ukuran $sFile harus dibawah ".MAX_IMAGE_SIZE;
                        break;
                    }

					$this->controller->request->data[$_sModel][$sFile]=$namaArrayFile['name'];
                }
            }

            $this->controller->$_sModel->create();
            $this->controller->$_sModel->set($this->controller->request->data);
            if ($this->Session->check('Auth.User.id')) {
                $this->controller->request->data[$_sModel]['modi_by']=$this->Session->read('Auth.User.id');
                $this->controller->request->data[$_sModel]['create_by']=$this->Session->read('Auth.User.id');
            }
            if ($this->controller->$_sModel->validates()){
                $error=$errorfile;
                $errormessage=$pesan_error;
                unset($errorfile); unset($pesan_error);

                $dataSource = $this->controller->$_sModel->getDataSource();
                $dataSource->begin($this->controller);

                if (!$error and isset($generateID) and !empty($generateID)){

					foreach ($generateID as $field=>$v){

                    	$this->controller->request->data[$_sModel][$field]=$this->General->__sinchronizeID($v['val_id'], $v['val_char'], $v['format'], $v['return']);
					//	pr($v['val_id']);
					//	pr($v['return']);
					//	pr($this->controller->request->data[$_sModel][$field]);exit;
		                if (!$this->controller->request->data[$_sModel][$field]) {
		                    $error=1;
		                    $errormessage='gagal menggenerate UNIQUE ID untuk field '. $field;
							break;
		                }
		                // Change at 17 January 2011. pengecekan apakah generate ID sudah pernah digunakan
		                else {
		                    $checkId=$this->controller->$_sModel->field($field, "$_sModel.$field IN ('{$this->controller->request->data[$_sModel][$field]}')");
							//pr($checkId);exit;
		                    if (isset($checkId) and $checkId) {
		                        $error=1;
		                        $errormessage='Generate UNIQUE ID untuk field '.$field.' sudah ada dalam database.';
								break;
		                    }
		                }
					}

                }

				if (!$error and isset($otherFunction['first'])) {
					$hasil=$this->__callOtherFunction($otherFunction['first'], array('id'=>array()));
					$error=$hasil[0]; $errormessage=$hasil[1];
				}

                if (!$error){ //pr($this->controller->request->data);
                    if ($this->controller->$_sModel->save($this->controller->request->data)) {
                        // Penyimpanan LOG bahwa terjadi perubahan data
                        // if(MODE<>'development') {
                        //     if (!$this->controller->__logChangeData($this->controller->Log->id)){
                        //         $error=1;
                        //         $errormessage='gagal menyimpan LOG';
                        //     }
                        // }
                    }
                    else {
                        $error=1;
                        $errormessage="Data tidak dapat disimpan";
                    }
                }

				if (!$error and isset($otherFunction['last'])) {
					$hasil=$this->__callOtherFunction($otherFunction['last'], array('id'=>array($this->controller->$_sModel->id)));
					$error=$hasil[0]; $errormessage=$hasil[1];
				}

                if ($error){
                    $dataSource->rollback($this->controller);
                    $this->Session->setFlash(__('ROLLBACK. '.$errormessage, true));
                }
                else {
                    $dataSource->commit($this->controller);
                    $pesan=__('Data telah disimpan', true);

                    // modified on 28 January 2011 to add input file type
                    if (!empty($aFiles)){
                        $sFile='';
                        foreach($aFiles as $i=>$v){
                            $sFile=is_array($v)?$i:$v;
                            $namaArrayFile="a$sFile";
                            if (isset($$namaArrayFile)) $namaArrayFile=$$namaArrayFile;
							if (is_array($namaArrayFile) and isset($namaArrayFile['tmp_name']) and !empty($namaArrayFile['tmp_name'])) {
								if (is_array($v) and isset($v['parentFolder']) and $v['parentFolder']=='app') $filesPath=APP;
								else $filesPath=FILE_FULL_PATH;
								if (is_array($v) and isset($v['folder'])) $filesPath.=$v['folder'].DS;

								// Penyimpanan file
								if (!move_uploaded_file($namaArrayFile['tmp_name'], $filesPath.$namaArrayFile['name'])) {
									$pesan.=__('Penyimpanan '.$sFile.' gagal');
								}
							}
                        }
                    }
                    
                    $this->Session->setFlash($pesan);
					// $index_url=str_replace('xxx',$this->$_sModel->id,$index_url);
                    $this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
                }
            }
            else $this->controller->__handleError($this->controller->$_sModel->invalidFields());
        }
    }

	function __callOtherFunction($otherFunction, $aId=array()){
		$error=0; $errormessage='';
		foreach($otherFunction as $i=>$v){
			if (is_array($v)) { $namaFungsi=$i; $argFungsi=$aId+$v; }
			else { $namaFungsi=$v; $argFungsi=$aId; }
			$hasil=call_user_func_array(array($this->controller->name.'Controller', $namaFungsi), $argFungsi);

			if (!isset($hasil)) {$error=1;$errormessage='gagal menjalankan fungsi '.$namaFungsi; break;}
			elseif ($hasil[0]==0) {$error=1; $errormessage=$hasil[1]; break;}
		}
		return array($error,$errormessage);
	}

	/* untuk php 5.4 keatas
	function __callOtherFunction ($otherFunction, $aId=array()){
		$error=0; $errormessage='';
		foreach($otherFunction as $i=>$v){
			if (is_array($v)) { $namaFungsi=$i; $argFungsi=$aId+$v; }
			else { $namaFungsi=$v; $argFungsi=$aId; }
			$class=new ReflectionClass($this->controller->name.'Controller');
			$hasil=call_user_func_array(array($class->newInstance(), $namaFungsi), $argFungsi);

			if (!isset($hasil)) {$error=1;$errormessage='gagal menjalankan fungsi '.$namaFungsi; break;}
			elseif ($hasil[0]==0) {$error=1; $errormessage=$hasil[1]; break;}
		}
		return array($error,$errormessage);
	} */

    /**
     * To view Header Detail
     *
     * Date Created  : 7 Desember 2010
     * Last Modified : 14 Desember 2010
     *
     * @param array $data is array with definitive rule
     * @param mixed $id primaryKey
     * @param string $conditions
     *
     * @access private
     * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
     * @since mtemplate 1.0 version
     */
    function __view($_sModel, $id, $data){
        $this->controller->$_sModel->id = $id;
		if (!$this->controller->$_sModel->exists()) {
			$this->Session->setFlash(__('Invalid '.$_sModel, true));
			$this->controller->redirect(array('action' => 'index'));
        }
        // hilangkan button edit delete pada tampilan view
		// if (!isset($data['hUrl'])) $data['hUrl']=array('edit'=>'','delete'=>'');
		$this->controller->helpers[]='General';
        // Modification 14 September 2011 to set default coloums
		$data['col']=isset($data['col'])?$data['col']:1;

		$this->controller->set('val', $this->controller->$_sModel->read(null, $id));

        $primaryKey=$this->controller->$_sModel->primaryKey;
		$conditions=isset($data['conditions'])?$data['conditions']:'';
		$simplepaging=$this->__getsimplepaging($_sModel, $id, $conditions);
		// pr($data);exit;
		$this->controller->set(compact('simplepaging', 'id', 'data','_sModel','primaryKey'));

        if (isset($data['render'])) $this->controller->render($data['render']);
        else $this->controller->render('/Elements/Masters/view');
    }
	

	function __print1($_sModel, $id, $data){
		$this->controller->$_sModel->id = $id;
		if (!$this->controller->$_sModel->exists()) {
			$this->Session->setFlash(__('Invalid '.$_sModel, true));
			$this->controller->redirect(array('action' => 'index'));
        }
		$this->controller->helpers[]='General';
        $data['col']=isset($data['col'])?$data['col']:1;

		$this->controller->set('val', $this->controller->$_sModel->read(null, $id));

        $primaryKey=$this->controller->$_sModel->primaryKey;
		$conditions=isset($data['conditions'])?$data['conditions']:'';
		$this->controller->set(compact('id', 'data','_sModel','primaryKey'));

        if (isset($data['render'])) $this->controller->render($data['render']);
        else $this->controller->render('/Elements/Masters/print1');
    }

     /**
     * To edit master data
     *
     * Date Created  : 22 November 2010
     * Last Modified : 30 January 2012
     *
     * @param string $_sModel Model
     * @param mixed $id
     * @param string $_sName=null string set for title
     * @param integer $recusive=0 set with -1,0,1 or 2 its mean recursive while retrive data
     * @param string $index_url=null use if want to custom url redirect after edit operation
     * @param array $aFiles=array() if any files to upload
     *
     * @access private
     * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
     * @since mtemplate 1.0 version
     */
    function __edit($_sModel, $id, $_sName=null, $recursive=0, $index_url=null, $aFiles=array(), $script=null, $otherFunction=array()){

    	$varpesan=(!is_null($_sName))?$_sName:$_sModel;
		$varpesan=Inflector::humanize(Inflector::underscore($varpesan));
        $this->controller->$_sModel->id = $id;

		if (!$this->controller->$_sModel->exists()) {
			//throw new NotFoundException(__('Invalid '.$_sModel));
		    $this->Session->setFlash(__('Invalid '.$varpesan, true));
            $this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
        }
        if (($this->controller->request->is('post') || $this->controller->request->is('put')) and !empty($this->controller->request->data[$_sModel])) {

			$errorfile=0;
            $pesan_error="";

            // modified on 1 February 2011 to edit input file type
            if (!empty($aFiles)){
            	$sFile='';
                foreach($aFiles as $i=>$v){ //pr($this->controller->request->data[$_sModel]); exit;
                    $sFile=is_array($v)?$i:$v;
                    if (isset($this->controller->request->data[$_sModel]['new_'.$sFile]['name']) and !empty($this->controller->request->data[$_sModel]['new_'.$sFile]['name'])) {
                        $namaArrayFile="a$sFile";
                        $$namaArrayFile=$this->controller->request->data[$_sModel]['new_'.$sFile];
                        $namaArrayFile=$$namaArrayFile;

						if (isset($v['maxSize']) and $namaArrayFile['size']>MAX_IMAGE_SIZE){
							$errorfile=1;
							$pesan_error="Ukuran $sFile harus dibawah ".MAX_IMAGE_SIZE;
							break;
						}

						$this->controller->request->data[$_sModel][$sFile]=$namaArrayFile['name'];
                    }
                    else {
                        $this->controller->request->data[$_sModel][$sFile]=$this->controller->request->data[$_sModel]['cur_'.$sFile];
                    }
                }
            }

            if ($this->Session->check('Auth.User.id')) $this->controller->request->data[$_sModel]['modi_by']=$this->Session->read('Auth.User.id');
            $this->controller->$_sModel->set($this->controller->request->data);
            if ($this->controller->$_sModel->validates()){
                $error=$errorfile;
                $errormessage=$pesan_error;
                unset($errorfile); unset($pesan_error);

                $dataSource = $this->controller->$_sModel->getDataSource();
                $dataSource->begin($this->controller);

				if (!$error and isset($otherFunction['first'])) {
					$hasil=$this->__callOtherFunction($otherFunction['first'], array('id'=>array($id)));
					$error=$hasil[0]; $errormessage=$hasil[1];
				}

                if ($this->controller->$_sModel->save($this->controller->data)) {
                    // if (MODE<>'development') {
                    //     if (!$this->controller->__logChangeData($this->controller->Log->id)){
                    //         $error=1;
                    //         $errormessage=__('Gagal menyimpan LOG.', true);
                    //     }
                    // }
				}

				if (!$error and isset($otherFunction['last'])) {
					$hasil=$this->__callOtherFunction($otherFunction['last'], array('id'=>array($id)));
					$error=$hasil[0]; $errormessage=$hasil[1];
				}

                if ($error){
                    $dataSource->rollback($this->controller);
                    $this->Session->setFlash(__('ROLLBACK. '.$errormessage, true));
                }
                else {
                    $dataSource->commit($this->controller);
                    $pesan=__('Perubahan telah disimpan', true);

                    // modified on 1 February 2011 to add input file type
                    if (!empty($aFiles)){
                        $sFile='';
                        foreach($aFiles as $i=>$v){
                            $sFile=is_array($v)?$i:$v;
                            $namaArrayFile="a$sFile";
                            if (isset($$namaArrayFile)) $namaArrayFile=$$namaArrayFile;
                            if (is_array($namaArrayFile) and isset($namaArrayFile['tmp_name']) and !empty($namaArrayFile['tmp_name'])) {
								if (is_array($v) and isset($v['parentFolder']) and $v['parentFolder']=='app') $filesPath=APP;
								else $filesPath=FILE_FULL_PATH;
                                if (isset($v['folder'])) $filesPath.=$v['folder'].DS;

                                // Penyimpanan file
                                if (!move_uploaded_file($namaArrayFile['tmp_name'], $filesPath.$namaArrayFile['name'])) {
                                    $pesan.=__('Penyimpanan '.$sFile.' gagal');
                                }

								if ($namaArrayFile['name']<>$this->controller->request->data[$_sModel]['cur_'.$sFile]){
									if (file_exists($filesPath.$this->controller->request->data[$_sModel]['cur_'.$sFile]))
									unlink($filesPath.$this->controller->request->data[$_sModel]['cur_'.$sFile]);
									if (file_exists($filesPath.'cache'.DS.'thumbs'.DS.substr($this->controller->request->data[$_sModel]['cur_'.$sFile],0,-4).'_'.THUMB_WIDTH.'x'.THUMB_WIDTH.substr($this->controller->request->data[$_sModel]['cur_'.$sFile],-4)))
									unlink($filesPath.'cache'.DS.'thumbs'.DS.substr($this->controller->request->data[$_sModel]['cur_'.$sFile],0,-4).'_'.THUMB_WIDTH.'x'.THUMB_WIDTH.substr($this->controller->request->data[$_sModel]['cur_'.$sFile],-4));
									if (file_exists($filesPath.'cache'.DS.'resize'.DS.$this->controller->request->data[$_sModel]['cur_'.$sFile]))
									unlink($filesPath.'cache'.DS.'resize'.DS.$this->controller->request->data[$_sModel]['cur_'.$sFile]);
								}
                            }
                        }
                    }
					$index_url=str_replace('xxx',$this->controller->$_sModel->id,$index_url);
                    if (!is_null($script)){
						$inf=new Inflector();
						$url=is_null($index_url)?'/'.$inf->underscore($this->controller->name).'/index':$index_url;
						$pause=1;
						$this->layout=false;
						$this->Set(compact('script','url','pause'));
						$this->render('/Elements/script');
						return 1;
					}
					else {
						$this->Session->setFlash($pesan);
						$this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
					}
                }
            }
            else $this->controller->__handleError($this->controller->$_sModel->invalidFields());
        }
		else {
            $this->controller->$_sModel->recursive=$recursive;
			$this->controller->request->data = $this->controller->$_sModel->read(null, $id);
        }
    }

    /**
     * To delete master data phisicly
     *
     * Date Created  : 22 November 2010
     * Last Modified : 30 January 2012
     *
     * @param string $_sModel Model
     * @param mixed $id
     * @param string $_sName=null title master data
     * @param $index_url=null if want to use custom url redirect after delete operation
     * @param boolean $cascade=false
     * @param array $aFiles=array() if any files upload to delete
     *
     * @access private
     * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
     * @since mtemplate 1.0 version
     */
    function __delete($_sModel, $id, $_sName=null, $index_url=null, $cascade=false, $aFiles=array(), $otherFunction=array()){

		$varpesan=(!is_null($_sName))?$_sName:$_sModel;
		$varpesan=Inflector::humanize(Inflector::underscore($varpesan));

		if (!$this->controller->request->is('post')) {
			$this->Session->setFlash(__('Method tidak diijinkan', true));
            $this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
		}
		$this->controller->$_sModel->id = $id;
		if (!$this->controller->$_sModel->exists()) {
            $this->Session->setFlash(__('Invalid '.$varpesan, true));
            $this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
        }

        $error=0;

        $dataSource = $this->controller->$_sModel->getDataSource();
        $dataSource->begin($this->controller);

		if (isset($this->controller->$_sModel->actsAs) and in_array('GenerateDelete', $this->controller->$_sModel->actsAs)){
			$hasil=$this->controller->$_sModel->toDelete($id);
			$error=$hasil[0];
			$errormessage=$hasil[1];
       	}

        $sId=$this->controller->$_sModel->primaryKey;
        $current_data=$this->controller->$_sModel->find('first', array('conditions'=>"$sId IN ('$id')", 'recursive'=>-1));

		if (!$error and isset($otherFunction['first'])) {
			$hasil=$this->__callOtherFunction($otherFunction['first'], array('id'=>array($id)));
			$error=$hasil[0]; $errormessage=$hasil[1];
		}

		if (!$error) {
			if ($this->controller->$_sModel->delete($id, $cascade)) {
		        // Penyimpanan LOG bahwa terjadi perubahan data
		        if(MODE<>'development') {
		            if (!$this->controller->__logChangeData($this->controller->Log->id)){
		                $error=1;
		                $errormessage='gagal menyimpan LOG';
		            }
		        }
		    }
		    else{
		        $error=1;
		        $errormessage=__('Data tidak dapat dihapus', true);
		    }
		}

		if (!$error and isset($otherFunction['last'])) {
			$hasil=$this->__callOtherFunction($otherFunction['last'], array('id'=>array($id)));
			$error=$hasil[0]; $errormessage=$hasil[1];
		}

        if ($error){
            $dataSource->rollback($this->controller);
            $this->Session->setFlash(__('ROLLBACK. '.$errormessage, true));
        }
        else {
            $dataSource->commit($this->controller);
            $this->Session->setFlash(__('Data telah dihapus', true));
            // modified on 1 February 2011 to add input file type
            if (!empty($aFiles)){
                $sFile='';
                foreach($aFiles as $i=>$v){
                    $sFile=is_array($v)?$i:$v;

					if (is_array($v) and isset($v['parentFolder']) and $v['parentFolder']=='app') $filesPath=APP;
					else $filesPath=FILE_FULL_PATH;
					$path=$filesPath.$v['folder'].DS;

					if (file_exists($path.$current_data[$_sModel][$sFile])) unlink($path.$current_data[$_sModel][$sFile]);
					if (file_exists($path.'cache'.DS.'thumbs'.DS.substr($current_data[$_sModel][$sFile],0,-4).'_'.THUMB_WIDTH.'x'.THUMB_WIDTH.substr($current_data[$_sModel][$sFile],-4)))
					unlink($path.'cache'.DS.'thumbs'.DS.substr($current_data[$_sModel][$sFile],0,-4).'_'.THUMB_WIDTH.'x'.THUMB_WIDTH.substr($current_data[$_sModel][$sFile],-4));
					if (file_exists($path.'cache'.DS.'resize'.DS.$current_data[$_sModel][$sFile]))
					unlink($path.'cache'.DS.'resize'.DS.$current_data[$_sModel][$sFile]);

                }
            }
        }

        $this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
    }

    /**
     * To delete master data as delete flag
     *
     * Date Created  : 7 Desember 2010
     * Last Modified : 14 Desember 2010
     *
     * @param string $_sModel Model
     * @param mixed $id
     * @param string $_sName=null title master data
     * @param string $index_url=null  if want to use custom url redirect after delete operation
     *
     * @access private
     * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
     * @since mtemplate 1.0 version
     */
    function __deletef($_sModel, $id, $_sName=null, $index_url=null, $otherFunction=array()){
        $varpesan=(!is_null($_sName))?$_sName:$_sModel;
        $varpesan=Inflector::humanize(Inflector::underscore($varpesan));

		if (!$this->controller->request->is('post')) {
			$this->Session->setFlash(__('Method tidak diijinkan', true));
            $this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
		}

		$this->controller->$_sModel->id = $id;

		if (!$this->controller->$_sModel->exists()) {
            $this->Session->setFlash(__('Invalid '.$varpesan, true));
            $this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
        }
        $error=0;

        $dataSource = $this->controller->$_sModel->getDataSource();

        $dataSource->begin($this);

		if (!$error and isset($otherFunction['first'])) {
			$hasil=$this->__callOtherFunction($otherFunction['first'], array('id'=>array($id)));
			$error=$hasil[0]; $errormessage=$hasil[1];
		}

        if ($this->controller->$_sModel->saveField('is_delete', 1)) {
            // Penyimpanan LOG bahwa terjadi perubahan data
            if(MODE<>'development') {
                if (!$this->__logChangeData($this->Log->id)){
                    $error=1;
                    $errormessage='gagal menyimpan LOG';
                }
            }
        }
        else {
            $error=1;
            $errormessage=__('Data tidak dapat dihapus', true);
        }

		if (!$error and isset($otherFunction['last'])) {
			$hasil=$this->__callOtherFunction($otherFunction['last'], array('id'=>array($id)));
			$error=$hasil[0]; $errormessage=$hasil[1];
		}

        if ($error){
            $dataSource->rollback($this);
            $this->Session->setFlash(__('ROLLBACK. '.$errormessage, true));
        }
        else {
            $dataSource->commit($this);
            $this->Session->setFlash(__('Data telah dihapus', true));
        }
        $this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
    }

	/**
     * To get simple paging. First - Prev - Next - Last
     *
     * Date Created  : 7 January 2011
     * Last Modified : 13 January 2011
     *
     * @param string $_sModel
     * @param mixed $id
     * @param string $conditions
     * @return array simple paging
     *
     * @access private
     * @author Rijal Asep Nugroho
     * @since mtemplate 1.0 version
     */
    function __getsimplepaging($_sModel, $id, $conditions=''){
        $_sId=$this->controller->$_sModel->primaryKey;
        $first=$this->controller->$_sModel->find('first', array('fields'=>$_sId, 'order'=>"$_sModel.$_sId ASC", 'conditions'=>$conditions));
        $last=$this->controller->$_sModel->find('first', array('fields'=>$_sId, 'order'=>"$_sModel.$_sId DESC", 'conditions'=>$conditions));
        $first_last=array('first'=>$first, 'last'=>$last);
        $neighbors = $this->controller->$_sModel->find('neighbors', array('field' => $_sId, 'value' => $id, 'fields'=>$_sId, 'conditions'=>$conditions));
        return am($first_last, $neighbors, array('primaryKey'=>$_sId));
    }

 	/**
     * To print Header Detail
     *
     * Date Created  : 21 March 2011
     * Last Modified :
     *
     * @param array $data is array with definitive rule
     * @param mixed $id primaryKey
     * @param string $conditions
     *
     * @access private
     * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
     * @since mtemplate 1.1.1 version
     */
    /*function __print1($data, $id, $conditions=''){
		$data['hId']=$this->$data['hModel']->primaryKey;
		$data['dId']=$this->$data['dModel']->primaryKey;
		if (isset($data['d2Model'])){
			$data['d2Id']=$this->$data['d2Model']->primaryKey;
		}

		$inf = new Inflector();
		//$foreignKey=$inf->underscore($data['hModel']).'_id';
		$foreignKey=$this->$data['hModel']->hasMany[$data['dModel']]['foreignKey'];
		$cond[]="{$data['dModel']}.$foreignKey IN ('$id')";
		if (isset($data['dConditions']) and !empty($data['dConditions'])) $cond[]=$data['dConditions'];

		$data['dData']=$this->$data['dModel']->find('all', array('conditions'=>implode(' AND ', $cond)));

		$data['hData']=$this->$data['hModel']->find('first', array('conditions'=>"{$data['hModel']}.{$data['hId']} IN ('$id')"));
		$this->set(compact('id', 'data'));
		$this->render('/elements/print_1');
    } */
}
