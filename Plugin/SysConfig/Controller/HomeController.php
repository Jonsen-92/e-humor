<?php

App::uses('AppController', 'Controller');


class HomeController extends AppController {

public $name='Home';

  public $uses=array();

  public function index1() {
    $this->loadModel('SysConfig.UserAccount');
    $this->loadModel('Laporan.LaporanPckBulanan');
    $id = $this->Session->read('Auth');
    if(!$id){
      $this->redirect('/login');
    }
    $nip = $this->Session->read('Auth.User.nip');
    $year = date('Y');
    $yearr = date('Y')-1;
    // DATA TAHUN INI
    $data = $this->LaporanPckBulanan->query("SELECT bulan,nilai_capaian_kinerja FROM laporan_pck_bulanans LPB WHERE nip = '".$nip."' AND tahun = $year ");

    $nilai = array();
    $nilai_post = array();

    for($i=1;$i<=12;$i++){
      if($i<10){
        $i = '0'.$i;
      }
      $nilai[$i] = 0;
    }
    
    foreach($data as $k=>$v){
      $nilai[$v['LPB']['bulan']] = $v['LPB']['nilai_capaian_kinerja'];
    }
    $nilai_post = implode(',' , $nilai);

    // DATA TAHUN SEBELUMNYA
    $dataa = $this->LaporanPckBulanan->query("SELECT bulan,nilai_capaian_kinerja FROM laporan_pck_bulanans LPB WHERE nip = '".$nip."' AND tahun = $yearr ");
    
    $nilaii = array();
    $nilai_postt = array();

    for($i=1;$i<=12;$i++){
      if($i<10){
        $i = '0'.$i;
      }
      $nilaii[$i] = 0;
    }
    foreach($dataa as $k=>$v){
      $nilaii[$v['LPB']['bulan']] = $v['LPB']['nilai_capaian_kinerja'];
    }
    $nilai_postt = implode(',' , $nilaii);
    $this->set(compact('nilai_post','nilai_postt'));
  }

  function index()
  {
    // $this->layout=false;
  }

}
