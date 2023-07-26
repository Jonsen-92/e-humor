<?php
App::uses('MasterComponent', 'Controller/Component');
class MasterDetailComponent extends Component{
	/**
     * To add transaction on header detail template
     *
     * Date Created  : 7 Desember 2010
     * Last Modified : 17 January 2011
     *
     * @param string $_sModel Model for header
     * @param array $_sDetail Model for detail
     * @param string $_sName=null title for transaction
     * @param array $_generateID if need to generate ID for header or for detail
     * @param array $hFiles=array() if any files to upload
     * @param array $dFiles=array() if any files to upload
	 *
     * @access private
     * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
     * @since mtemplate 1.0 version
     */
    function __add($_sModel, $_aDetail, $_sName=null, $index_url=null, $_generateID=false, $hFiles=array(), $dFiles=array(), $otherFunction=array()){
        $varpesan=(!is_null($_sName))?$_sName:$_sModel;
		$varpesan=Inflector::humanize(Inflector::underscore($varpesan));

        if ($this->controller->request->is('post') and !empty($this->controller->data[$_sModel])) {
            $errorfile=0;
            $pesan_error="";

            // modified on 1 February 2011 to add input file type
            if (!empty($hFiles)){
                $sFile='';
                foreach($hFiles as $i=>$v){
                    $sFile=is_array($v)?$i:$v;
                    $namaArrayFile="a$sFile";
                    $$namaArrayFile=$this->data[$_sModel][$sFile];
                    $namaArrayFile=$$namaArrayFile;

					if (isset($v['maxSize']) and $namaArrayFile['size']>MAX_IMAGE_SIZE){
                        $errorfile=1;
                        $pesan_error="Ukuran $sFile harus dibawah ".MAX_IMAGE_SIZE;
                        break;
                    }
					if (isset($v['fullpath']) and $v['fullpath']==true) $this->controller->data[$_sModel][$sFile]='/'.$v['folder'].'/'.$namaArrayFile['name'];
					else $this->controller->data[$_sModel][$sFile]=$namaArrayFile['name'];
                }
            }

			$this->controller->$_sModel->create();
            $this->controller->$_sModel->set($this->controller->data);

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
                //pr($error);
                if (!$error and isset($_generateID['header'])){
					foreach ($_generateID['header'] as $field=>$v){

                    	$this->controller->request->data[$_sModel][$field]=$this->General->__sinchronizeID($v['val_id'], $v['val_char'], $v['format'], $v['return']);
		                //pr($this->controller->request->data[$_sModel][$field]);exit;
						if (!$this->controller->request->data[$_sModel][$field]) {
		                    $error=1;
		                    $errormessage='gagal menggenerate UNIQUE ID untuk field '. $field;
							break;
		                }
		                // Change at 17 January 2011. pengecekan apakah generate ID sudah pernah digunakan
		                else {
		                    $checkId=$this->controller->$_sModel->field($field, "$_sModel.$field IN ('{$this->controller->request->data[$_sModel][$field]}')");
		                    if (isset($checkId) and $checkId) {
		                        $error=1;
		                        $errormessage='Generate UNIQUE ID untuk field '.$field.' sudah ada dalam database.';
								break;
		                    }

		                }

					}

                }
                //pr($error);
				if (!$error and isset($otherFunction['first'])) {
					$hasil=$this->__callOtherFunction($otherFunction['first'], array('id'=>array()));
					$error=$hasil[0]; $errormessage=$hasil[1];
				}
				// pr($error);
				// pr($_sModel);
				// pr($this->controller->request->data);die;
                if (!$error and $this->controller->$_sModel->save($this->controller->request->data)) {
					//pr($this->data);
                    $arg=array('id'=>array($this->controller->$_sModel->id));
					if (!$error and isset($otherFunction['beforeDetail'])) {
						$hasil=$this->__callOtherFunction($otherFunction['beforeDetail'], $arg);
						$error=$hasil[0]; $errormessage=$hasil[1];
					}

					// simpan detail
					if (!$error) {
						foreach ($_aDetail as $model){
							$dFiles=array();//$_generateID=false;
							//if (isset($_generateID[$model])) $_generateID=$_generateID[$model];
							if (isset($dFiles[$model])) $dFiles=$dFiles[$model];
							$aError=$this->__addDetail($model, $_sModel, $_generateID, $dFiles);
		                	$error=$aError[0];
		                	$errormessage=$aError[1];
							if ($error) break;

							$arg['id_'.$model]=array($this->controller->$_sModel->$model->id);
							if (!$error and isset($otherFunction['after'.$model])) {
								$hasil=$this->__callOtherFunction($otherFunction['after'.$model], $arg);
								$error=$hasil[0]; $errormessage=$hasil[1];
							}
							if ($error) break;
						}
					}

					if (!$error and isset($otherFunction['last'])) {
						$hasil=$this->__callOtherFunction($otherFunction['last'], $arg);
						$error=$hasil[0]; $errormessage=$hasil[1];
					}

                    // Penyimpanan LOG bahwa terjadi perubahan data
                    if(!$error and MODE<>'development') {
                        if (!$this->controller->__logChangeData($this->Log->id)){
                            $error=1;
                            $errormessage='gagal menyimpan LOG';
                        }
                    }

					if (!$error){
                        $this->Session->setFlash(__('Data telah disimpan', true));
                        $dataSource->commit($this->controller);

						// modified on 28 January 2011 to add input file type
						if (!empty($hFiles)){
							$sFile='';
							foreach($hFiles as $i=>$v){
								$sFile=is_array($v)?$i:$v;
								$namaArrayFile="a$sFile";
								if (isset($$namaArrayFile)) $namaArrayFile=$$namaArrayFile;
								if (is_array($namaArrayFile) and isset($namaArrayFile['tmp_name']) and !empty($namaArrayFile['tmp_name'])) {
									$filesPath=FILE_FULL_PATH;
									if (isset($v['folder'])) $filesPath.=$v['folder'].DS;

									// Penyimpanan file
									if (!move_uploaded_file($namaArrayFile['tmp_name'], $filesPath.$namaArrayFile['name'])) {
										$pesan.=__('Penyimpanan '.$sFile.' gagal');
									}
								}
							}
						}
						$index_url=str_replace('xxx',$this->controller->$_sModel->id,$index_url);
                        $this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
                    }
                    else {
                        $dataSource->rollback($this->controller);
                        $this->controller->Session->setFlash(__('Terjadi ROLLBACK. '.$errormessage.' Silahkan mencoba kembali.', true));
                    }
                } else {
                    $this->controller->Session->setFlash(__('Data tidak dapat disimpan, silahkan mencoba kembali.', true));
                }
            }
            else $this->controller->__handleError($this->controller->$_sModel->invalidFields());
        }
    }

	/**
     * To add transaction on header detail template
     *
     * Date Created  : 7 Desember 2010
     * Last Modified : 17 January 2011
     *
     * @param string $_sModel Model for header
     * @param array $_sDetail Model for detail
     * @param string $_sName=null title for transaction
     * @param array $_generateID if need to generate ID for header or for detail
     * @param array $hFiles=array() if any files to upload
     * @param array $dFiles=array() if any files to upload
	 *
     * @access private
     * @author Rijal Asep Nugroho <rijal.asep.nugroho@gmail.com>
     * @since mtemplate 1.0 version
     */
    function __edit($id, $_sModel, $_aDetail, $_sName=null, $index_url=null, $_generateID=false, $hFiles=array(), $dFiles=array(), $otherFunction=array()){
        $varpesan=(!is_null($_sName))?$_sName:$_sModel;
		$varpesan=Inflector::humanize(Inflector::underscore($varpesan));

        if (($this->controller->request->is('post') or $this->controller->request->is('put')) and !empty($this->controller->data[$_sModel])) {
            $errorfile=0;
            $pesan_error="";

            if (!empty($hFiles)){
                $sFile='';
                foreach($hFiles as $i=>$v){
                    $sFile=is_array($v)?$i:$v;
                    $namaArrayFile="a$sFile";
                    $$namaArrayFile=$this->data[$_sModel][$sFile];
                    $namaArrayFile=$$namaArrayFile;

					if (isset($v['maxSize']) and $namaArrayFile['size']>MAX_IMAGE_SIZE){
                        $errorfile=1;
                        $pesan_error="Ukuran $sFile harus dibawah ".MAX_IMAGE_SIZE;
                        break;
                    }
					if (isset($v['fullpath']) and $v['fullpath']==true) $this->controller->request->data[$_sModel][$sFile]='/'.$v['folder'].'/'.$namaArrayFile['name'];
					else $this->controller->request->data[$_sModel][$sFile]=$namaArrayFile['name'];
                }
            }

			$this->controller->$_sModel->id=$id;
			$this->controller->$_sModel->set($this->controller->data);

			if ($this->Session->check('Auth.User.id')) {
                $this->controller->request->data[$_sModel]['modi_by']=$this->Session->read('Auth.User.id');
            }
            if ($this->controller->$_sModel->validates()){
                $error=$errorfile;
                $errormessage=$pesan_error;
                unset($errorfile); unset($pesan_error);

                $dataSource = $this->controller->$_sModel->getDataSource();
                $dataSource->begin($this->controller);

				if (isset($otherFunction)) $arg=array('id'=>array($this->controller->$_sModel->id));

               	if (!$error and isset($otherFunction['first'])) {
					$hasil=$this->__callOtherFunction($otherFunction['first'], $arg);
					$error=$hasil[0]; $errormessage=$hasil[1];
				}

                if (!$error and $this->controller->$_sModel->save($this->controller->request->data)) {
					//pr($this->controller->request->data);exit;
                    if (!$error and isset($otherFunction['beforeDetail'])) {
						$hasil=$this->__callOtherFunction($otherFunction['beforeDetail'], $arg);
						$error=$hasil[0]; $errormessage=$hasil[1];
					}

					// simpan detail
					if (!$error){
						foreach ($_aDetail as $model){
							// exisiting detail
							$existingDetail=$this->controller->$_sModel->$model->find('list', array('conditions'=>"{$this->controller->$_sModel->hasMany[$model]['foreignKey']} IN ('$id')", 'fields'=>array($this->controller->$_sModel->$model->primaryKey,$this->controller->$_sModel->$model->primaryKey)));

							$dFiles=array();//$_generateID=false;
							//if (isset($_generateID[$model])) $_generateID=$_generateID[$model];
							if (isset($dFiles[$model])) $dFiles=$dFiles[$model];
							$aError=$this->__addDetail($model, $_sModel, $_generateID, $dFiles, $existingDetail);
		                	$error=$aError[0];
		                	$errormessage=$aError[1];
							if ($error) break;

							$arg['id_'.$model]=array($this->controller->$_sModel->$model->id);
							if (!$error and isset($otherFunction['after'.$model])) {
								$hasil=$this->__callOtherFunction($otherFunction['after'.$model], $arg);
								$error=$hasil[0]; $errormessage=$hasil[1];
							}
							if ($error) break;
						}
					}

					if (!$error and isset($otherFunction['last'])) {
						$hasil=$this->__callOtherFunction($otherFunction['last'], $arg);
						$error=$hasil[0]; $errormessage=$hasil[1];
					}

                    // Penyimpanan LOG bahwa terjadi perubahan data
                    if(!$error and MODE<>'development') {
                        if (!$this->controller->__logChangeData($this->Log->id)){
                            $error=1;
                            $errormessage='gagal menyimpan LOG';
                        }
                    }

					if (!$error){
                        $this->Session->setFlash(__('Data telah disimpan', true));
                        $dataSource->commit($this->controller);

						// modified on 28 January 2011 to add input file type
						if (!empty($hFiles)){
							$sFile='';
							foreach($hFiles as $i=>$v){
								$sFile=is_array($v)?$i:$v;
								$namaArrayFile="a$sFile";
								if (isset($$namaArrayFile)) $namaArrayFile=$$namaArrayFile;
								if (is_array($namaArrayFile) and isset($namaArrayFile['tmp_name']) and !empty($namaArrayFile['tmp_name'])) {
									$filesPath=FILE_FULL_PATH;
									if (isset($v['folder'])) $filesPath.=$v['folder'].DS;

									// Penyimpanan file
									if (!move_uploaded_file($namaArrayFile['tmp_name'], $filesPath.$namaArrayFile['name'])) {
										$pesan.=__('Penyimpanan '.$sFile.' gagal');
									}
								}
							}
						}
						$index_url=str_replace('xxx',$this->controller->$_sModel->id,$index_url);
                        $this->controller->redirect(is_null($index_url)?array('action'=>'index'):$index_url);
                    }
                    else {
                        $dataSource->rollback($this->controller);
                        $this->Session->setFlash(__('Terjadi ROLLBACK. '.$errormessage.' Silahkan mencoba kembali.', true));
                    }
                } else {
                    $this->Session->setFlash(__('Data tidak dapat disimpan, silahkan mencoba kembali.', true));
                }
            }
			else $this->controller->__handleError($this->controller->$_sModel->invalidFields());
        }
		// LOAD DATA FROM DATABASE
		else {
			$this->controller->request->data=$this->controller->$_sModel->read(null,$id);
		}
    }

    /**
     * To Add Detail on Header-DEtail Template Or Header-Detail-Detail template
     *
     * Date Created  : 7 Desember 2010
     * Last Modified : 17 January 2010
     *
     * @param string $_sDetail detail model
     * @param string $_sModel header model
     * @param array $generateID use if need to generate id for header or detail
	 * @param array $aFiles=array() if any files to upload
     * @return array error message, array('Boolean error', 'String message error')
     *
     * @access private
     * @author Rijal Asep Nugroho
     * @since mtemplate 1.0 version
     */
    function __addDetail($_sDetail, $_sModel, $generateID=false, $aFiles=array(), $existingDetail=array()){
        $tempData=$this->controller->request->data;
		$data=$this->controller->request->data[$_sDetail];
        unset($this->controller->request->data[$_sDetail]);
        $error=0;
        $errormessage='';
        $counter=1;
		//pr($this->controller->$_sModel->$_sDetail->find('all'));exit;
		$detId=$this->controller->$_sModel->$_sDetail->primaryKey;
		foreach ($data as $t=>$v){
			unset($this->controller->request->data[$_sDetail]);
			if (isset($v[$detId]) and !empty($v[$detId])) {
				$this->controller->$_sModel->$_sDetail->$detId=$v[$detId];
				unset($existingDetail[$v[$detId]]);
			}
			else {
				$this->controller->$_sModel->$_sDetail->create();
				$this->controller->request->data[$_sDetail]['create_by']=$this->Session->read('Auth.User.id');
			}
			$this->controller->request->data[$_sDetail]['modi_by']=$this->Session->read('Auth.User.id');

			$this->controller->request->data[$_sDetail]=$v;
            $this->controller->request->data[$_sDetail][$this->controller->$_sModel->hasMany[$_sDetail]['foreignKey']]=$this->controller->$_sModel->id;

			if (!isset($v[$detId])) {
				if (isset($generateID['header']) and is_array($generateID['header'])) {
					foreach ($generateID['header'] as $field=>$v){
						$this->controller->request->data[$_sDetail][$field]=$this->controller->request->data[$_sModel][$field];
					}
		        }
			}

			// modified on 28 January 2011 to add input file type
			if (!empty($aFiles)){
				$sFile='';
				foreach($aFiles as $o=>$p){
					$sFile=is_array($p)?$o:$p;
					$namaArrayFile="a$sFile";
					$$namaArrayFile=$v[$sFile];
					$namaArrayFile=$$namaArrayFile;
					if (isset($p['maxSize']) and $namaArrayFile['size']>MAX_IMAGE_SIZE){
						$error=1;
						$errormessage="Ukuran $sFile harus dibawah ".MAX_IMAGE_SIZE;
						break;
					}

					if (isset($p['fullpath']) and $p['fullpath']==true) $this->controller->request->data[$_sDetail][$sFile]='/'.$p['folder'].'/'.$namaArrayFile['name'];
					else $this->controller->request->data[$_sDetail][$sFile]=$namaArrayFile['name'];
				}
			}
			//pr($this->controller->request->data);exit;
			$this->controller->$_sModel->$_sDetail->set($this->controller->request->data);

            if ($this->controller->$_sModel->$_sDetail->validates()){
				if (!isset($v[$detId])){
                    if (isset($generateID[$_sDetail]) and is_array($generateID[$_sDetail])){
						$this->controller->request->data[$_sDetail][$this->controller->$_sModel->$_sDetail->primaryKey]=$this->General->__sinchronizeID($generateID[$_sDetail]['val_id'], $generateID[$_sDetail]['val_char'], $generateID[$_sDetail]['format'], $generateID[$_sDetail]['return']);
		                if (!$this->controller->request->data[$_sDetail][$this->controller->$_sModel->$_sDetail->primaryKey]) {
							$error=1;
							$errormessage='gagal menggenerate ID untuk detail';
							break;
		                }
		                // Change at 17 January 2011. pengecekan apakah generate ID sudah pernah digunakan
		                else {
		                    $this->controller->$_sModel->$_sDetail->id=$this->controller->data[$_sDetail][$this->controller->$_sModel->$_sDetail->primaryKey];
		                    $checkDetId=$this->controller->$_sModel->$_sDetail->field($this->controller->$_sModel->$_sDetail->primaryKey);
		                    if (isset($checkDetId) and $checkDetId) {
		                        $error=1;
		                        $errormessage='Generate ID sudah ada dalam database.';
		                        break;
		                    }
		                }
					}
                }

                if (!$this->controller->$_sModel->$_sDetail->save($this->controller->request->data)) {
                    $error=1;
                    $errormessage="Terjadi error tak terduga pada penyimpanan transaksi baris $counter";
                    break;
                }
				else {
					// modified on 28 January 2011 to add input file type
					if (!empty($aFiles)){
						$sFile='';
						foreach($aFiles as $i=>$v){
							$sFile=is_array($v)?$i:$v;
							$namaArrayFile="a$sFile";
							if (isset($$namaArrayFile)) $namaArrayFile=$$namaArrayFile;
							if (is_array($namaArrayFile) and isset($namaArrayFile['tmp_name']) and !empty($namaArrayFile['tmp_name'])) {
								$filesPath=FILE_FULL_PATH;
								if (isset($v['folder'])) $filesPath.=$v['folder'].DS;

								// Penyimpanan file
								if (!move_uploaded_file($namaArrayFile['tmp_name'], $filesPath.$namaArrayFile['name'])) {
									$pesan.=__('Penyimpanan '.$sFile.' gagal');
								}
							}
						}
					}
				}
            }
            else {
              	$_e=$this->controller->$_sModel->$_sDetail->invalidFields();
				$aIndex_e = array_keys($_e);
				if (!empty($aIndex_e[0])) $errormessage=$_e[$aIndex_e[0]][0];
				$errormessage.=" pada baris $counter";
                $error=1;
                break;
            }
            $counter++;
        }

		if ($error) $this->controller->request->data=$tempData;

		// delete detail
		if (!$error and !empty($existingDetail)) {
			if (!$this->controller->$_sModel->$_sDetail->deleteAll("$_sDetail.$detId IN ('".implode("','",$existingDetail)."')")){
				$error=1;
				$errormessage='Gagal menghapus existing detail pada '. $_sDetail;
			}
		}

        return array($error, $errormessage);
    }
}
