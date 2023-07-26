<?php
App::uses('Controller','Controller');
class AjaxController extends Controller{
	var $name='Ajax';
	var $uses=array();
	var $components=array('General');

	public function getDivisiUnit($kodeDivisi=null)
	{
		$this->autoRender=false;
		$this->layout=false;
		$this->loadModel('MasterFile.DivisiDetail');
		$aDivUnit = $this->DivisiDetail->find('list', array('fields'=>array('unit_bisnis','unit_bisnis'), 'conditions'=>array("DivisiDetail.kode_divisi='$kodeDivisi'")));
		// pr($aDivUnit);
		echo "<option value='0'>--Pilih Unit Bisnis--</option>";
		foreach ($aDivUnit as $key => $value) {
			echo "<option value='$value'>$value</option>";
		}
	}

	public function getIdDivisi($kodeDivisi=null)
	{
		$this->autoRender=false;
		$this->layout=false;
		$this->loadModel('MasterFile.Divisi');
		$aDivId = $this->Divisi->find('list', array('fields'=>array('id'), 'conditions'=>array("Divisi.kode_divisi='$kodeDivisi'")));
		foreach ($aDivId as $key => $value) {
			echo "<option value='$value'>$value</option>";
		}
	}

	public function getNamaDivisi($kodeDivisi=null)
	{
		$this->autoRender=false;
		$this->layout=false;
		$this->loadModel('MasterFile.Divisi');
		$aDivName = $this->Divisi->find('list', array('fields'=>array('nama_divisi'), 'conditions'=>array("Divisi.kode_divisi='$kodeDivisi'")));
		foreach ($aDivName as $key => $value) {
			echo "<option value='$value'>$value</option>";
		}
	}







}
