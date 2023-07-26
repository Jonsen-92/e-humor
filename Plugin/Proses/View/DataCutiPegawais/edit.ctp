<style>
	.input .col-sm-7 span {
        color:red;
    }
  	.input .col-sm-8 span {
        color:red;
    }
    .input .col-sm-9 span {
    	color: red;
    }

	.btn1 {
        background-color: #008CBA;
        font-size: 24px;
        padding: 12px 28px;
        border-radius: 8px;
        color: white;
        margin-left:20px;
    }

    .btn1:hover {
        background-color: #4CAF50;
        color: white;
        box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
    }

</style>

<script>
    jQuery(document).ready(function () {
        $(':input[type="submit"]').prop('class', 'btn1');
    });

    function hitungCuti(){
        var CutiTL = Number($('#DataCutiPegawaiSisaCutiTahunLalu').val());
        var CutiTG = Number($('#DataCutiPegawaiCutiDitangguhkan').val());
        var CutiTI = Number($('#DataCutiPegawaiJumlahCutiTahunBerjalan').val());
        var CutiSD = Number($('#DataCutiPegawaiJumlahCutiSudahDiambil').val());

        sisaCuti = CutiTL+CutiTG+CutiTI-CutiSD;
        $('#DataCutiPegawaiSisaCutiTahunBerjalan').val(sisaCuti);
    }
</script>

<h3><b>Form Data Cuti Pegawai</b></h3>
<br>

<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model,array('onsubmit'=>"", 'class'=>'form-horizontal'));
?>

<div class="col-md-6">
    <?php 
        echo $this->Form->input('nip', array('label' => array('text' => 'N I P', 'class' => 'col-sm-3 control-label'),'type' => 'text','class' => 'form-control','value'=>$data[$model]['nip'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('nama', array('label' => array('text' => 'Nama Pegawai', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'class' => 'form-control','value'=>$data[$model]['nama'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('tahun', array('label' => array('text' => 'Tahun', 'class' => 'col-sm-3 control-label'),'type' => 'text','value'=>$data[$model]['tahun'],'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('sisa_cuti_tahun_lalu', array('label' => array('text' => 'Sisa Cuti (N-1)', 'class' => 'col-sm-3 control-label'),'type' => 'text','oninput'=>'hitungCuti()', 'class' => 'form-control','value'=>$data[$model]['sisa_cuti_tahun_lalu'], 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('cuti_ditangguhkan', array('label' => array('text' => 'Jumlah Cuti yang Ditangguhkan', 'class' => 'col-md-3 control-label'),'type' => 'text','value'=>$data[$model]['cuti_ditangguhkan'],'oninput'=>'hitungCuti()','value'=>0,'min'=>0,'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));
        
        echo $this->Form->input('jumlah_cuti_tahun_berjalan', array('label' => array('text' => 'Jumlah Cuti Tahun Ini', 'class' => 'col-md-3 control-label'),'value'=>$data[$model]['jumlah_cuti_tahun_berjalan'],'type' => 'text','oninput'=>'hitungCuti()', 'min'=>0,'class' => 'form-control', 'value'=>12, 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('jumlah_cuti_sudah_diambil', array('label' => array('text' => 'Jumlah Cuti Sudah Terpakai', 'class' => 'col-md-3 control-label'),'type' => 'text','value'=>$data[$model]['jumlah_cuti_sudah_diambil'],'oninput'=>'hitungCuti()','value'=>0, 'min'=>0,'class' => 'form-control',  'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('sisa_cuti_tahun_berjalan', array('label' => array('text' => 'Total Sisa Cuti', 'class' => 'col-md-3 control-label'),'type' => 'text','value'=>$data[$model]['sisa_cuti_tahun_berjalan'], 'readonly', 'min'=>0,'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));
    ?>
</div>
<br>
<?php echo $this->Form->end(__((isset($button)?$button:'SUBMIT'), true));?>