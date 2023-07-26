<script>
    jQuery(document).ready(function () {
        $(':input[type="submit"]').prop('class', 'btn btn-danger');
	});

    function cek_passwd(){
        var passwd = $('#UserAccountPassword').val();
        var val = $('#UserAccountRetypePasswd').val();
        if(passwd !== val){
            alert('Password does not Match..!!!, Please retype Password Correctly');
            $('#UserAccountRetypePasswd').val('');
        }
    }
</script>

<h3><b>EDIT DATA USER <?php $model = 'UserAccount'; echo strtoupper($data[$model]['name']); ?></b></h3>
<br>
<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model,array( 'class'=>'form-horizontal'));
?>

<div class="col-md-5">
<?php
    $akses = array(''=>'-- Pilih Level Akses --','0'=>'Admin/Ketua/Wakil Ketua','1'=>'Kasub Kepegawaian','2'=>'Panitera/Sekretaris/Panmud/Kasubbag','3'=>'Staff','4'=>'Hakim');
    $divisi = array(''=>'-- Pilih Divisi --','administrator'=>'Administrator','Ketua'=>'Ketua','Wakil Ketua'=>'Wakil Ketua','Kepaniteraan'=>'Kepaniteraan','Kesekretariatan'=>'Kesekretariatan');
    $status_jabatan = array('definitif'=>'Definitif','Plh'=>'Pelaksana Harian','Plt'=>'Pelaksana Tugas');
    // $sub_divisi = array(''=>'-- Pilih Sub Divisi --','administrator'=>'Administrator','Ketua'=>'Ketua','Wakil Ketua'=>'Wakil Ketua','Panitera'=>'Panitera','Pidana'=>'Pidana','Perdata'=>'Perdata','Hukum'=>'Hukum','Bagian Umum dan Keuangan'=>'Bagian Umum dan Keuangan','Keportala'=>'Keportala','PTIP'=>'PTIP','I'=>'ZI Area I','II'=>'ZI Area II','III'=>'ZI Area III','IV'=>'ZI Area IV','V'=>'ZI Area V','VI'=>'ZI Area VI','PTSP'=>'PTSP','APM'=>'APM');

    echo $this->Form->input('nip', array('label' => array('text' => 'NIP', 'class' => 'col-sm-3 control-label'),'type' => 'text','value'=>$data[$model]['nip'],'readonly', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('name', array('label' => array('text' => 'Name', 'class' => 'col-sm-3 control-label'),'type' => 'text','value'=>$data[$model]['name'],'readonly', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

	echo $this->Form->input('pangkat_gol', array('label' => array('text' => 'Pangkat/Golongan', 'class' => 'col-md-3 control-label'),'type' => 'text','value'=>$data[$model]['pangkat_gol'],'required', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

	echo $this->Form->input('jabatan', array('label' => array('text' => 'jabatan', 'class' => 'col-md-3 control-label'),'type' => 'text','value'=>$data[$model]['jabatan'],'required','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('unit_kerja', array('label' => array('text' => 'Unit Kerja', 'class' => 'col-md-3 control-label'),'type' => 'text', 'value'=>$data[$model]['unit_kerja'],'readonly','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('group_id', array('label' => array('text' => 'Level Jabatan', 'class' => 'col-sm-3 control-label lovfield'), 'type' => 'select','value'=>$data['GroupAccount'][0]['GroupsUser']['group_id'],'readonly','options'=>$group, 'required','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('status_jabatan', array('label' => array('text' => 'Status Jabatan', 'class' => 'col-sm-3 control-label lovfield'), 'type' => 'select','value'=>$data[$model]['status_jabatan'],'options'=>$status_jabatan, 'required','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));
    
?>
</div>

<div class="col-md-5">
<?php

    echo $this->Form->input('type_akses', array('label' => array('text' => 'Level Akses', 'class' => 'col-sm-3 control-label lovfield'), 'type' => 'select','value'=>$data[$model]['type_akses'],'readonly','options'=>$akses, 'required','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('username', array('label' => array('text' => 'Username', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'value'=>$data[$model]['username'],'required','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('password', array('label' => array('text' => 'Password', 'class' => 'col-sm-3 control-label'),'type' => 'password','value'=>$data[$model]['password'],'required', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

	echo $this->Form->input('divisi', array('label' => array('text' => 'Divisi', 'class' => 'col-md-3 control-label'),'type' => 'select','value'=>$data[$model]['divisi'],'required','options'=>$divisi,'required', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    // echo $this->Form->input('sub_divisi', array('label' => array('text' => 'Sub Divisi', 'class' => 'col-md-3 control-label'),'type' => 'select','value'=>$data[$model]['sub_divisi'],'required','options'=>$sub_divisi,'required', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('status', array('label' => array('text' => 'Is Active ?', 'class' => 'col-sm-3 control-label lovfield'), 'type' => 'select','value'=>$data[$model]['status'],'required','options'=>array('1'=>'Active','0'=>'Not Active'), 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));
    
?>
</div>

<?php echo $this->Form->end(__((isset($button)?$button:'SUBMIT'), true));?>