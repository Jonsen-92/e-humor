<?php
	echo $this->Html->script('sweetalert2.min.js');
	echo $this->Html->css('sweetalert2.min.css');
?>

<script>
    jQuery(document).ready(function () {
        $(':input[type="submit"]').prop('class', 'btn btn-danger');
	});

    function cek_passwd(){
        var passwd = $('#UserAccountPassword').val();console.log(passwd);
        var val = $('#UserAccountRetypePasswd').val();console.log(val);
        if(passwd !== val){
            Swal.fire(
							'Password does not Match..!!!',
							'Please retype Password Correctly',
							'question'
						)
            $('#UserAccountRetypePasswd').val('');
        }
    }

    function getDataPegawai(value){
        $.getJSON('/edaran/sys_config/users/getNip/'+value, function(data){
            if(data){
                $('#UserAccountName').val(data.Pegawai.nama);
                $('#UserAccountPangkatGol').val(data.Pegawai.pangkat_golongan);
                $('#UserAccountJabatan').val(data.Pegawai.jabatan);
                $('#UserAccountUnitKerja').val(data.Pegawai.unit_kerja);
            }
            else{
                alert('Data Pegawai Tidak Ditemukan');
                return false;
            }
        })
    }

    function cekUser(nip){
        $.getJSON('/edaran/sys_config/users/getUser/'+nip, function(data){
            if(data){
                Swal.fire(
							'User dengan NIP '+nip+' sudah terdaftar',
							'Silahkan pilih menu tombol edit jika ada perubahan',
							'question'
						)
                document.getElementById("UserAccountAddForm").reset();
            }
            else{
                getDataPegawai(nip);
            }
        })
    }
</script>

<h3><b>NEW USER</b></h3>
<br>
<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model,array( 'class'=>'form-horizontal'));
?>

<div class="col-md-5">
<?php

    $akses = array(''=>'-- Pilih Level Akses --','0'=>'Admin/Ketua/Wakil Ketua','1'=>'Kasub Kepegawaian','2'=>'Panitera/Sekretaris/Panmud/Kasubbag','3'=>'Staff','4'=>'Hakim');
    $divisi = array(''=>'-- Pilih Divisi --','administrator'=>'Administrator','Ketua'=>'Ketua','Wakil Ketua'=>'Wakil Ketua','Kepaniteraan'=>'Kepaniteraan','Kesekretariatan'=>'Kesekretariatan');
    $status_jabatan = array('definitif'=>'Definitif','Plh'=>'Pelaksana Harian','Plt'=>'Pelaksana Tugas');
    // $sub_divisi = array(''=>'-- Pilih Sub Divisi --','administrator'=>'Administrator','Ketua'=>'Ketua','Wakil Ketua'=>'Wakil Ketua','Panitera'=>'Panitera','Pidana'=>'Pidana','Perdata'=>'Perdata','Hukum'=>'Hukum','Bagian Umum dan Keuangan'=>'Bagian Umum dan Keuangan','Keportala'=>'Keportala','PTIP'=>'PTIP','I'=>'ZI Area I','II'=>'ZI Area II','III'=>'ZI Area III','IV'=>'ZI Area IV','V'=>'ZI Area V','VI'=>'ZI Area VI','PTSP'=>'PTSP','APM'=>'APM');

    echo $this->Form->input('nip', array('label' => array('text' => 'NIP', 'class' => 'col-sm-3 control-label'),'type' => 'select','options'=>$nip,'onchange'=>'cekUser(this.value)','required', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('name', array('label' => array('text' => 'Name', 'class' => 'col-sm-3 control-label'),'type' => 'text','readonly','required', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

	echo $this->Form->input('pangkat_gol', array('label' => array('text' => 'Pangkat/Golongan', 'class' => 'col-md-3 control-label'),'type' => 'text','readonly', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

	echo $this->Form->input('jabatan', array('label' => array('text' => 'jabatan', 'class' => 'col-md-3 control-label'),'type' => 'text','readonly', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('unit_kerja', array('label' => array('text' => 'Unit Kerja', 'class' => 'col-md-3 control-label'),'type' => 'text','readonly', 'required','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('group_id', array('label' => array('text' => 'Level Jabatan', 'class' => 'col-sm-3 control-label lovfield'), 'type' => 'select','options'=>$group, 'required','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('status_jabatan', array('label' => array('text' => 'Status Jabatan', 'class' => 'col-sm-3 control-label lovfield'), 'type' => 'select','options'=>$status_jabatan, 'required','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));
    
?>
</div>

<div class="col-md-5">
<?php

    echo $this->Form->input('type_akses', array('label' => array('text' => 'Level Akses', 'class' => 'col-sm-3 control-label lovfield'), 'type' => 'select','options'=>$akses, 'required','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('username', array('label' => array('text' => 'Username', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'required','class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    echo $this->Form->input('password', array('label' => array('text' => 'Password', 'class' => 'col-sm-3 control-label'),'type' => 'password','required', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

	echo $this->Form->input('retype_passwd', array('label' => array('text' => 'Confirm Password', 'class' => 'col-md-3 control-label'),'type' => 'password','onchange'=>'cek_passwd()','required', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

	echo $this->Form->input('divisi', array('label' => array('text' => 'Divisi', 'class' => 'col-md-3 control-label'),'type' => 'select','options'=>$divisi,'required', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    // echo $this->Form->input('sub_divisi', array('label' => array('text' => 'Sub Divisi', 'class' => 'col-md-3 control-label'),'type' => 'select','options'=>$sub_divisi,'required', 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));

    // echo $this->Form->input('status', array('label' => array('text' => 'Is Active ?', 'class' => 'col-sm-3 control-label lovfield'), 'type' => 'select','required','options'=>array('1'=>'Active','0'=>'Not Active'), 'class' => 'form-control', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));
    
?>

    <div class="input text">
        <label for="CustomerStatus" class="col-sm-3 control-label">Is Active?</label>
        <div class="col-sm-9">
            <?php echo $this->Form->checkbox('status', array('value' => 1, 'checked')); ?>
            <p id="CustomerStatusLabel">&nbsp;Hilangkan Centang Untuk Nonaktif</p>
        </div>
    </div>
</div>

<?php echo $this->Form->end(__((isset($button)?$button:'SUBMIT'), true));?>